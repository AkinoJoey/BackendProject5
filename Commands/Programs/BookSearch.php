<?php

namespace Commands\Programs;

use Database\MySQLWrapper;
use Commands\AbstractCommand;
use Commands\Argument;

class BookSearch extends AbstractCommand{
    protected static ?string $alias = 'book-search';
    protected MySQLWrapper $mysqli;
    protected static bool $requiredCommandValue = true;

    public function __construct()
    {
        parent::__construct();
        $this->mysqli = new MySQLWrapper();
    }

    public static function getArguments(): array

    {
        return [
        
        ];
    }

    public function execute(): int
    {
        $this->search();
        return 0;
    }

    public function search() : void {
        $isbn = $this->getCommandValue();
        
        // テーブルを作成する
        $this->mysqli->query(file_get_contents(dirname(__FILE__,3) . '/Database/Examples/books.sql'));

        // データがあるかどうか確認
        $query = sprintf("SELECT data FROM books WHERE id = 'book-search-isbn-%s';", $isbn);
        $data_exists = $this->mysqli->query($query);
        print($query);
        print_r($data_exists);

        if($data_exists === 0){
            // データをAPIから取得して、挿入する
            $url = sprintf('https://openlibrary.org/api/books?bibkeys=ISBN:%s&jscmd=data&format=json', $isbn);
            $json_data = file_get_contents($url);
            print("Book Data: " . PHP_EOL . $json_data . PHP_EOL);

            $key = sprintf('book-search-isbn-%s', $isbn);
            $query = sprintf("INSERT INTO books (id, data) values ('%s', '%s')", $key, $json_data);
            $this->mysqli->query($query);
            
        }elseif($data_exists === 1){
            // created_atが30日以内か確認する
            $query = sprintf("SELECT updated_at FROM books WHERE id = 'book-search-isbn-%s';", $isbn);
            $updated_at = $this->mysqli->query($query);
            echo $updated_at;

            // データベースから取得して出力する
            $query = sprintf("SELECT data FROM books WHERE id = 'book-search-isbn-%s';", $isbn);
            $json_data = $this->mysqli->query($query);
            // print("Book Data: " . PHP_EOL . $json_data . PHP_EOL);

        }
        
    }
}

// php console book-search --isbn 0385472579

// ・キャッシュにデータがあるかどうか確認
// ・ない場合はデータを取得して保存。
// ・ある場合は、30日以上経過しているか確認
// ・経過していない場合は、キャッシュのデータを使う
// ・経過している場合は、データを取得して更新

