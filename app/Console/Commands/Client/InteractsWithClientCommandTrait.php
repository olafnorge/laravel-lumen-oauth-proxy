<?php
namespace App\Console\Commands\Client;

use App\Models\Client;
use Illuminate\Support\Collection;
use Schema;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;

trait InteractsWithClientCommandTrait {

    /**
     * Attributes hidden from diffs and output
     *
     * @var array
     */
    private $hiddenAttributes = [
        'id',
        'created_at',
        'updated_at',
    ];


    /**
     * Return attributes of a client
     *
     * @param Client $client
     * @return array
     */
    private function getAttributes(Client $client): array {
        return array_except($client->getAttributes(), $this->hiddenAttributes);
    }


    /**
     * Return changed values of a client
     *
     * @param Client $client
     * @return array
     */
    private function getChanges(Client $client): array {
        return array_except($client->getChanges(), $this->hiddenAttributes);
    }


    /**
     * Read a client from the storage
     *
     * @return Client
     */
    private function getClient(): Client {
        return value(function (): Client {
            $client = Client::where('id', $this->argument('id'))->first();

            while (!($client instanceof Client)) {
                $this->renderListClientsTable(Client::all());

                $id = $this->ask('Which client do you want to reset? Please provide an ID:');
                $client = Client::where('id', $id)->first();
            };

            return $client;
        });
    }


    /**
     * Returns a set of fillable fields
     *
     * @return array
     */
    private function getFillable(): array {
        return array_flip(array_except(array_flip((new Client())->getFillable()), ['client_id', 'client_secret']));
    }


    /**
     * Return original data of a client
     *
     * @param Client $client
     * @return array
     */
    private function getOriginal(Client $client): array {
        return array_except($client->getOriginal(), array_merge($this->hiddenAttributes, ['client_id', 'client_secret']));
    }


    /**
     * Returns a list of table headers feed by visible columns of Client model
     *
     * @return array
     */
    private function getTableHeaders(): array {
        $model = new Client();

        return array_flip(array_except(array_flip(Schema::getColumnListing($model->getTable())), $model->getHidden()));
    }


    /**
     * @param Client $client
     * @param string $column
     */
    private function setClientAttribute(Client $client, string $column): void {
        do {
            $exists = $client->exists;

            if ($client->hasCast($column, ['bool', 'boolean'])) {
                $value = $this->confirm(sprintf('Do you want to %s the client?', str_replace_last('d', '', $column)), (bool)($exists ? $client->{$column} : true));
            } else {
                $value = $this->ask(
                    $exists
                        ? sprintf('Please provide a value for %s. To skip just hit enter.', $column, $client->{$column})
                        : sprintf('Please provide a value for %s.', $column),
                    $client->{$column}
                );
            }

            $dirty = $exists
                ? $client->{$column} !== $value
                : true;
        } while ($dirty && !$this->validates([$column => $value]));

        $client->{$column} = $value;
    }


    /**
     * Renders a list of Clients
     *
     * @param Collection $clients
     */
    private function renderListClientsTable(Collection $clients): void {
        $this->table($this->getTableHeaders(), $clients);
    }


    /**
     * @param string $headline
     * @param array $headers
     * @param array $rows
     */
    private function renderSummaryTable(string $headline, array $headers, array $rows): void {
        $table = new Table($this->getOutput());
        $table->setHeaders([
            [new TableCell($headline, ['colspan' => count($headers)])],
            $headers,
        ]);
        $table->setRows($rows);
        $table->render();
    }


    /**
     * Validates a client record
     *
     * @param array $parameters
     * @return bool
     */
    private function validates(array $parameters): bool {
        $validator = Client::getValidator($parameters);
        $passes = $validator->passes();

        if (!$passes) {
            $errors = $validator->errors()->toArray();
            $lineLength = max(array_map(function ($item) {
                return is_array($item)
                    ? max(array_map('strlen', $item))
                    : strlen($item);
            }, $errors)) + 4;

            $this->error(str_repeat(' ', $lineLength));

            foreach ($errors as $error) {
                if (is_array($error)) {
                    foreach ($error as $line) {
                        $this->error(sprintf('  %s', str_pad($line, $lineLength - 2, ' ', STR_PAD_RIGHT)));
                    }
                } else {
                    $this->error(sprintf('  %s', str_pad($error, $lineLength - 2, ' ', STR_PAD_RIGHT)));
                }
            }

            $this->error(str_repeat(' ', $lineLength));
        }

        return $passes;
    }
}
