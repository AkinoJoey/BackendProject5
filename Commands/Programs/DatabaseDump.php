<?php

namespace Commands\Programs;

use Database\MySQLWrapper;
use Commands\AbstractCommand;
use Commands\Argument;

class DatabaseDump extends AbstractCommand{
    protected static ?string $alias = 'db-wipe';

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
        $mysqli = new MySQLWrapper();
        $database = $mysqli->getDatabaseName();

        $query = sprintf('DROP DATABASE %s', $database);
        $mysqli->query($query);
        $info = sprintf('Deleted %s ...', $database);
        $this->log($info);
    }

    public function dump(): void{
        $this->log('データベースのバックアップを作成しています。');
        $mysqli = new MySQLWrapper();
        $command = sprintf('mysqldump -u %s -p %s > backup.sql' ,$mysqli->getUserName(), $mysqli->getDatabaseName());
        system($command);
        $this->log('backup.sqlを作成しました。');
    }
}