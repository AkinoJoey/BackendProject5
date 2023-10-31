<?php

namespace Commands\Programs;

use Database\MySQLWrapper;
use Commands\AbstractCommand;
use Commands\Argument;
use DateTime;
use Exception;

class BookSearch extends AbstractCommand{
    protected static ?string $alias = 'book-search';
    protected MySQLWrapper $mysqli;

    public function __construct()
    {
        parent::__construct();
        $this->mysqli = new MySQLWrapper();
    }

    public static function getArguments(): array

    {
        return [
            (new Argument('isbn'))->description('引数のisbnを基に本の情報を検索します。')->required(true)->allowAsShort(true),
        ];
    }

    public function execute(): int
    {
        $isbn = $this->getArgumentValue('isbn');
        if($isbn === true){
            throw new Exception("isbnの入力が必要です。");
        }else{
            $this->search($isbn);
        }

        return 0;
    }

    public function search($isbn) : void {
        // テーブルを作成する
        $this->mysqli->query(file_get_contents(dirname(__FILE__,3) . '/Database/Examples/books.sql'));

        $id = 'book-search-isbn-' . $isbn;

        // データベースにデータがあるかどうか確認
        $book_data_assoc = $this->getBookData($id);

        if(is_null($book_data_assoc)){
            $this->saveBookDataToDatabase($isbn, $id);
            $book_data_assoc = $this->getBookData($id);
        }else{
            $isWithin30days = $this->isWithin30days($book_data_assoc['updated_at']);

            if(!$isWithin30days){
                $this->updateBookData($isbn, $id);
                $book_data_assoc = $this->getBookData($id);
            }
        }

        $this->log($book_data_assoc['data']);
        
    }

    public function saveBookDataToDatabase(string $isbn, string $id): void{
        $json_data = $this->fetchApiBookData($isbn);

        $query = "INSERT INTO books (id, data) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ss", $id, $json_data);
        $stmt->execute();
        $stmt->close();
    }

    public function getBookData(string $id): ?array
    {
        $query = "SELECT data, updated_at FROM books WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    public function updateBookData(string $isbn, string $key) : void {
        $json_data = $this->fetchApiBookData($isbn);
        $query = "UPDATE books SET data = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";

        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ss", $json_data, $key);
        $stmt->execute();
        $stmt->close();
    }

    public function fetchApiBookData(string $isbn) : string {
        $url = sprintf('https://openlibrary.org/api/books?bibkeys=ISBN:%s&jscmd=data&format=json', $isbn);
        $json_data = file_get_contents($url);
        return $json_data;
    }

    public function isWithin30days($date): bool{
        $date = new DateTime($date);
        $currentDate = new DateTime();
        $diff = $date->diff($currentDate);
        $daysDifference = $diff->days;
        return $daysDifference <= 30;
    }


}