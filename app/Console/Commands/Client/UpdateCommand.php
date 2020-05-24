<?php

namespace App\Console\Commands\Client;

use Illuminate\Console\Command;

class UpdateCommand extends Command {

    use InteractsWithClientCommandTrait;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:update {id? : ID of the client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a client';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $client = $this->getClient();

        foreach ($this->getFillable() as $column) {
            $this->setClientAttribute($client, $column);
        }

        $client->syncChanges();

        if ($client->isClean()) {
            $this->info('No changes detected. Leaving client unchanged.');

            return 0;
        }

        $this->renderSummaryTable(sprintf('The following changes will be applied to client with ID %s.', $client->id), ['attribute', 'old', 'new'], value(function () use ($client): array {
            $original = $this->getOriginal($client);
            $rows = [];

            foreach ($this->getChanges($client) as $attribute => $value) {
                $rows[] = [
                    'attribute' => $attribute,
                    'old' => array_get($original, $attribute),
                    'new' => $value,
                ];
            }

            return $rows;
        }));

        if ($this->confirm('Do you wish to continue?')) {
            $client->save();
        }
    }
}
