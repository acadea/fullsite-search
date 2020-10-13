<?php

namespace Acadea\FullSite\Facades\Commands;

use Illuminate\Console\Command;

class FullSiteCommand extends Command
{
    public $signature = 'fullsite-search';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
