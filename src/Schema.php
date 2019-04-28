<?php

namespace Awssat\SyncMigration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Spatie\Regex\Regex;
use Illuminate\Support\Facades\Schema as LaravelSchema;

class Schema
{
    public $schema;
    public $name;
    public $table;
    public $writeIn;
    public $synced = false;

    /**
     * Schema constructor.
     * @param $schema
     * @param $writeIn
     */
    public function __construct($schema, SyncMigrateCommand $writeIn)
    {
        $this->schema = $schema;
        $this->writeIn = $writeIn;

        $this->name = $this->getName($schema->group(1));
        $this->table = DB::getTablePrefix() . $this->name;
    }

    public function process()
    {
        $action = $this->tabeExist() ? 'sync' : 'create';

        $this->$action();
    }

    public function output()
    {
        $this->synced = true;
        return $this->writeIn;
    }

    protected function create()
    {
        if($this->columnsList()->isEmpty()) {
            return $this->output()->error("Table <fg=black;bg=white> {$this->table} </> does not have any columns");
        }

        LaravelSchema::create($this->name, function (Blueprint $table) {
            eval($this->schema->group(2));
        });

        $this->output()->warn("New table <fg=white;bg=green> {$this->table} </> was created");
    }

    protected function sync()
    {
        if($this->schemaEmpty()) {
            return $this->dropSchema();
        }

        $this->dbUnsyncedColumns()->each(function ($type, $column)  {
            $this->output()->info("Column <fg=black;bg=yellow> {$this->table}->{$column} </> is renamed or deleted !!");
            $action = $this->output()->choice('What we should do ?', $this->syncChoices(), 0);

            (new Column($this))->$action($column);
        });

        $this->schemaUnsyncedColumns()->each(function ($column, $line) {
            (new Column($this))->create($line, $column);
        });
    }

    protected function dropSchema()
    {
        $this->output()->error("Table <fg=black;bg=yellow> {$this->table} </> does not have any columns");
        $this->output()->confirm("Do you want to drop <fg=white;bg=red> {$this->table} </> ?",
            true) && LaravelSchema::dropIfExists($this->name);
    }

    protected function schemaEmpty()
    {
        return $this->dbColumns()->diffKeys($this->dbUnsyncedColumns())->isEmpty();
    }

    protected function getName($name)
    {
        //TODO: https://github.com/awssat/laravel-sync-migration/issues/2
        if(preg_match('/[\'|"]\s?\.\s?\$/', $name) || preg_match('/\$[a-zA-z0-9-_]+\s?\.\s?[\'|"]/', $name)) {
            $this->output()->error("Using variables as table names (<fg=black;bg=white> {$name} </>) is not supported currentlly, see <href=https://github.com/awssat/laravel-sync-migration/issues/2> issue#2 </>");
            exit;
        }

        return str_replace(['\'', '"'], '', $name);
    }

    public function schemaUnsyncedColumnsOutput()
    {
        return $this->schemaUnsyncedColumns()->values()->flatten()->toArray();
    }

    protected function dbUnsyncedColumns()
    {
        return $this->dbColumns()->reject(function ($type, $column) {
            return $this->columnsList()->values()->flatten()->contains($column);
        });
    }

    protected function schemaUnsyncedColumns()
    {
        return $this->columnsList()->reject(function ($column) {
            return $this->dbColumns()->has($column);
        });
    }

    protected function syncChoices()
    {
        return $this->schemaUnsyncedColumns()->isEmpty() ? ['Delete', 'Ignore'] :
            ['Delete', 'Rename', 'Ignore'];
    }

    protected function dbColumns()
    {
        return collect(DB::select('DESCRIBE ' . $this->table))
            ->mapWithKeys(function ($column) {
            return [$column->Field => $column->Type];
        });
    }

    protected function columnsList()
    {
        return collect(explode(';', $this->schema->group(2)))->mapWithKeys(function ($line) {
            $line = trim($line);

            if(starts_with($line, ['//', '#', '/*'])) {
                return [];
            }

            try {
                $column = Regex::match('~(["\'])([^"\']+)\1~', $line);
                $column = $column->hasMatch() ? $column->group(2) : null;
                $types = $this->columnsTypes($column);
                $type = Regex::match('/\$.*->(.*)\(/', $line)->group(1);

                return [$line => in_array($type, array_keys($types)) ? $types[$type] : [$column]];
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    protected function columnsTypes($column)
    {
        return  [
            'rememberToken' => ['remember_token'],
            'softDeletes' => ['deleted_at'],
            'softDeletesTz' => ['deleted_at'],
            'timestamps' => ['created_at', 'updated_at'],
            'timestampsTz' => ['created_at', 'updated_at'],
            'morphs' => ["{$column}_id", "{$column}_type"],
        ];
    }
    
    protected function tabeExist()
    {
        return DB::getSchemaBuilder()->hasTable($this->name);
    }
}
