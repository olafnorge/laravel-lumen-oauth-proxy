<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Application;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use olafnorge\Database\Providers\MigrateCommandProvider;

class Kernel extends ConsoleKernel {


    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Client\CreateCommand::class,
        Commands\Client\ListCommand::class,
        Commands\Client\ResetCommand::class,
        Commands\Client\UpdateCommand::class,
    ];


    public function __construct(Application $app) {
        parent::__construct($app);

        $app->register(MigrateCommandProvider::class);
    }


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        //
    }
}
