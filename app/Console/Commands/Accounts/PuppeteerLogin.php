<?php

namespace App\Console\Commands\Accounts;


use App\Linkedin\Responses\Cookie;
use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
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

        if (strpos($page->url(), $this->challenge_str) > 0) {

            $code = $this->ask('Enter code');

            $codeInput = $page->querySelector('[name="' . $this->code_selector . '"]');
            $codeInput->type($code);
            $page->screenshot(['path' => storage_path('1.png')]);

            $page->querySelector('button[id=email-pin-submit-button')->click();
            $page->waitForNavigation();

            $page->screenshot(['path' => storage_path('3.png')]);
        }

        $page->screenshot(['path' => storage_path('4.png')]);
        $cookies = $page->cookies();
//      $cookies = $page->evaluate(JsFunction::createWithBody("return document.cookie;"));

        foreach ($cookies as $item) {
            $filtered[$item['name']] = str_replace('"', '', $item['value']);
        }

        $cookie = [
            'str' => Helper::cookieToString(collect($filtered)),
            'crfToken' => $filtered['JSESSIONID']
        ];

        $data['cookie_web'] = ['JSESSIONID' => $cookie['crfToken']];
        $data['cookie_str'] = $cookie['str'];
        $account->update($data);

        $browser->close();

        return 1;
    }

}
