<?php

namespace App\Console\Commands\Client;

use App\Models\Client;
use Hash;
use Illuminate\Console\Command;

class CreateCommand extends Command {

    use InteractsWithClientCommandTrait;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a client';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $secret = generate_client_secret();
        $client = new Client([
            'client_id' => generate_client_id(),
            'client_secret' => Hash::make($secret),
        ]);

        foreach ($this->getFillable() as $column) {
            $this->setClientAttribute($client, $column);
        }

        $this->renderSummaryTable('The following client will be created. Please write down client_id and client_secret as we will not show it again.', ['attribute', 'value'], value(function () use ($client, $secret): array {
            $rows = [];

            foreach ($this->getAttributes($client) as $attribute => $value) {
                $rows[] = [
                    'attribute' => $attribute,
                    'value' => $attribute === 'client_secret' ? $secret : $value,
                ];
            }


            return $rows;
        }));

        if ($this->confirm('Do you wish to continue?')) {
            $client->save();
        }
    }
}
