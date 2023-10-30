<?php

namespace Commands\Programs;

use Database\MySQLWrapper;
use Commands\AbstractCommand;
use Commands\Argument;

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
        return [
            (new Argument('backup'))->description('データベースのバックアップを作成します')->required(false)->allowAsShort(true),
        ];
    }

    public function execute(): int
    {
        $backup = $this->getArgumentValue('backup');
        if($backup){
            $this->log('データベースのバックアップ作成を開始します。');
            $this->dump();
        }
        $this->log('データベースのデリートを開始します。');
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
        $this->log('データベースのバックアップを作成しています。');
        $command = sprintf('mysqldump -u %s -p %s > backup.sql' ,$this->mysqli->getUserName(), $this->mysqli->getDatabaseName());
        system($command);
        $this->log('backup.sqlを作成しました。');
    }
}