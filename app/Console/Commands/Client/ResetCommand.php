<?php

namespace App\Console\Commands\Client;

use Hash;
use Illuminate\Console\Command;

class ResetCommand extends Command {

    use InteractsWithClientCommandTrait;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:reset {id? : ID of the client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a client';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $client = $this->getClient();

        $secret = generate_client_secret();
        $client->client_id = generate_client_id();
        $client->client_secret = Hash::make($secret);
        $client->activated = true;
        $client->syncChanges();

        $this->renderSummaryTable(
            sprintf('The client with ID %s will be reset to the following values. Please write down client_id and client_secret as we will not show it again.', $client->id),
            ['attribute', 'old', 'new'],
            value(function () use ($client, $secret): array {
                $original = array_except($client->getOriginal(), ['id', 'created_at', 'updated_at']);
                $rows = [];

                foreach ($client->getChanges() as $attribute => $value) {
                    $rows[] = [
                        'attribute' => $attribute,
                        'old' => $attribute === 'client_secret' ? 'hashed secret' : array_get($original, $attribute),
                        'new' => $attribute === 'client_secret' ? $secret : $value,
                    ];
                }

                return $rows;
            })
        );

        if ($this->confirm('Do you wish to continue?')) {
            $client->save();
        }
    }
}
