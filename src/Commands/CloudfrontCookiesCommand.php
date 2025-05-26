<?php

namespace Maize\CloudfrontCookies\Commands;

use Illuminate\Console\Command;

class CloudfrontCookiesCommand extends Command
{
    public $signature = 'laravel-cloudfront-cookies';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
