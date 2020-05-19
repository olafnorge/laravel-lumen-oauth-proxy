<?php

namespace App\Console\Commands\Client;

use App\Models\Client;
use Illuminate\Console\Command;

class ListCommand extends Command {

    use InteractsWithClientCommandTrait;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all clients';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->renderListClientsTable(Client::all());
    }
}
