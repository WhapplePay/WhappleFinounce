<?php

namespace App\Console\Commands;

use App\Models\Configure;
use App\Models\Currency;
use App\Models\Investment;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Artisan;

class Cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron for Currency Rate Update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
