<?php

namespace App\Console\Commands;


use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use App\Linkedin\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

class LinkedinPuppeteerLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:LinkedinPuppeteerLogin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $cookie_path;

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
    protected string $code_selector = 'pin';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $accounts = (new AccountRepository())->getAllRealAccounts();

        foreach ($accounts as $account) {
            try {




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


                $code = $this->ask('Enter code');

                $codeInput = $page->querySelector('[name="' . $this->code_selector . '"]');
                $codeInput->type($code);
                $page->screenshot(['path' => storage_path('1.png')]);

                $page->querySelector('button[id=email-pin-submit-button')->click();
                $page->waitForNavigation();

                $page->screenshot(['path' => storage_path('2.png')]);

                $cookies = $page->cookies();
//                $pageFunction = JsFunction::createWithParameters(['element'])
//                    ->body("return document.cookie");

                $dimensions = $page->evaluate(JsFunction::create("return document.cookie;"));
                dump($dimensions,11);
                $filtered = [];

//                foreach ($cookies as $item) {
//                    $filtered[$item['name']] = str_replace('"', '', $item['value']);
//                }
//
//                $cookie = [
//                    'str' => Helper::cookieToString(collect($filtered)),
//                    'crfToken' => $filtered['JSESSIONID']
//                ];
//
//                if (!File::exists($this->cookie_path)) {
//                    File::makeDirectory($this->cookie_path, $mode = 0777, true, true);
//                }
//
//                File::put($this->cookie_path . '/' . $account->login . '.json', json_encode($cookie));

                $page->screenshot(['path' => storage_path($account->login . '.png')]);

                $browser->close();
            } catch (\Exception $exception) {
                $this->error($exception->getMessage() . '   ' . $account->login);
            }
        }


        return 1;
    }

}
