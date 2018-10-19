<?php

namespace Awssat\SyncMigration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema as LaravelSchema;

class Column
{
    protected $schema;

    /**
     * Column constructor.
     * @param $schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }


    public function create($line, $column)
    {
        LaravelSchema::table($this->schema->name, function (Blueprint $table) use ($line) {
            eval($line . ';');
        });

        $this->schema->output()->warn("New column <fg=black;bg=yellow> {$this->schema->name}->{$column[0]} </> was created");
    }

    public function delete($column)
    {
        LaravelSchema::table($this->schema->name, function (Blueprint $table) use ($column) {
            $table->dropColumn($column);
        });

        $this->schema->output()->warn("Column <fg=black;bg=yellow> {$this->schema->name}->{$column} </> was deleted");
    }

    public function rename($column)
    {
        $renameTo = $this->schema->output()->choice('Rename to ? :', $this->schema->schemaUnsyncedColumnsOutput(), 0);

        LaravelSchema::table($this->schema->name, function (Blueprint $table) use ($column, $renameTo) {
            $table->renameColumn($column, $renameTo);
        });

        $this->schema->output()->warn("Column <fg=black;bg=yellow> {$this->schema->name}->{$column} </> was renamed to '{$renameTo}'");
    }

    public function ignore($column)
    {
        $this->schema->output()->info("<fg=black;bg=yellow> {$this->schema->name}->{$column} </> was ignored");
    }
}
