<?php

namespace App\Console\Commands\Accounts;

use App\Linkedin\Api;
use App\Linkedin\Responses\Connection;
use App\Models\Account;
use App\Linkedin\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

class PuppeteerLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:login-linkedin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected string $cookie_path;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cookie_path = storage_path('linkedin/cookies');

        parent::__construct();
    }


    /**
     * @var string
     */
    protected string $username_selector = 'username';

    /**
     * @var string
     */
    protected string $password_selector = 'password';

    /**
     * @var string
     */
    protected string $code_selector = 'pin';

    /**
     * @var string
     */
    protected string $challenge_str = 'checkpoint/challenge';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {

        if (!File::exists(storage_path('login'))) {
            File::makeDirectory(storage_path('login'));
        }

        $accounts = Account::pluck('login')->toArray();

        $choice = $this->choice('Login as ?', $accounts);

        $account = Account::whereLogin($choice)->first();


        $browser = (new Puppeteer)->launch([
            'headless' => true,
            'args' => ['--no-sandbox']
        ]);

        $page = $browser->newPage();

        $page->goto('https://www.linkedin.com/login', [
            'timeout' => 90000
        ]);

        $email = $page->querySelector('[id="' . $this->username_selector . '"]');

        $email->type($account->login);

        $password = $page->querySelector('[id="' . $this->password_selector . '"]');

        $password->type($account->password);

        $page->querySelector('button[type=submit')->click();

        $page->waitForNavigation();
        $page->screenshot(['path' => storage_path('login/afterSubmit_'.$account->id.'.png')]);

        if (strpos($page->url(), $this->challenge_str) > 0) {

            $code = $this->ask('Enter code');
            $page->screenshot(['path' => storage_path('login/beforeTypePin_'.$account->id.'.png')]);

            $codeInput = $page->querySelector('[name="' . $this->code_selector . '"]');
            $codeInput->type($code);
            $page->screenshot(['path' => storage_path('login/afterTypePin_'.$account->id.'.png')]);

            $page->querySelector('button[id=email-pin-submit-button')->click();
//            $page->evaluate(JsFunction::createWithBody('return document.documentElement.outerHTML'));
            $page->waitForNavigation([  'timeout' => 900000]);

            $page->screenshot(['path' => storage_path('login/redirectAfterChallenge_'.$account->id.'.png')]);
        }

        $page->screenshot(['path' => storage_path('login/login_'.$account->id.'.png')]);
        $cookies = $page->cookies();
        $browser->close();

        foreach ($cookies as $item) {
            $filtered[$item['name']] = str_replace('"', '', $item['value']);
        }

        $cookie = [
            'str' => Helper::cookieToString(collect($filtered)),
            'crfToken' => $filtered['JSESSIONID']
        ];

        $data['jsessionid'] = $cookie['crfToken'];
        $data['cookie_web_str'] = $cookie['str'];
        $data['cookie_socket_str'] = $cookie['str'];
        $account->update($data);

        $resp = Api::profile($account)->getOwnProfile();
        if ($resp['status'] === 200 && $resp['success']) {
            $resp = Connection::parseSingle((array)$resp['data']);

            $account->update($resp);

            if (!File::exists(storage_path('linkedin'))) {
                File::makeDirectory(storage_path('linkedin'));
            }

            if (!File::exists(storage_path('linkedin/ecosystem.json'))) {
                File::put(storage_path('linkedin/ecosystem.json'), null);
            }

            $app = [
                'name' => $account->login,
                'script' => app_path('Linkedin/Node/index.js'),
                'watch' => false,
                'max_memory_restart' => '200M',
                'shutdown_with_message'=> true,
                'env' => [
                    'COOKIE' => [
                        'str' => $account->cookie_socket_str,
                        'crfToken' => $account->jsessionid,
                    ],
                    'ACCOUNT_LOGIN' => $account->login,
                    'ACCOUNT_ID' => $account->id,
                    'APP_URL' => config('app.url')
                ],
            ];

            File::put(storage_path('linkedin/' . $account->login . '.json'), json_encode($app));

//            shell_exec('pm2 start ' . storage_path('linkedin/' . $account->login . '.json'));
        }


        return 1;
    }

}
