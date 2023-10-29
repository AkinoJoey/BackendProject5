<?php

namespace Commands\Programs;

use Database\MySQLWrapper;
use Commands\AbstractCommand;


class DatabaseDump extends AbstractCommand{
    protected static ?string $alias = 'db-wipe';
    protected MySQLWrapper $mysqli;

    public function __construct()
    {
        parent::__construct();
        $this->mysqli = new MySQLWrapper();
    }

    public static function getArguments(): array
    
    {
        return [];
    }

    public function execute(): int
    {
        $this->drop();
        return 0;
    }

    public function drop() : void {
        $this->log('Deleting the database ...');
        $database = $this->mysqli->getDatabaseName();

        $query = sprintf('DROP DATABASE %s', $database);
        $this->mysqli->query($query);
        $info = sprintf('Deleted %s ...', $database);
        $this->log($info);
    }

    public function dump(): void{
        $command = sprintf('mysqldump -u %s -p %s > backup.sql', $this->mysqli->getUserName(), $this->mysqli->getDatabaseName());
        system($command);
    }

    public function restore(): void{
        $command = sprintf('mysql -u %s -p %s < backup.sql', $this->mysqli->getUserName(), $this->mysqli->getDatabaseName());
        system($command);
    }
}


