<?php
require_once("Book.php");
require_once("Magazine.php");
// Clase que administra el CRUD de libros
class BiblioManager {
    private array $books = [];
    private array $magazines = [];
    private string $filePath = 'data/data.json';

    public function __construct() {
        $this->loadPublications();
    }

    public function getBooks(): array {
        return $this->books;
    }
    public function getMagazines(): array {
        return $this->magazines;
    }


    //Métodos para manejar publicaciones (libros y revistas)
    private function loadPublications(): void {
        $this->books = [];
        $this->magazines = [];
        if (file_exists($this->filePath)) {
            $data = json_decode(file_get_contents($this->filePath), true);
        }
        if ($data != null && is_array($data)){
            foreach ($data as $array){
                //libro o revista
                if (array_key_exists('pages', $array)){
                    $this->books[] = Book::fromArray($array);
                }
                if (array_key_exists('type', $array)){
                    $this->magazines[] = Magazine::fromArray($array);
                }
                
            }
        }
    }

    public function addPublication(string $title, string $author, int $year, $var): void {
        $this->loadPublications();
        if (is_numeric($var)){
            $book = new Book($title, $author, $year, $var);
            $this->books[] = $book;
        } else {
            $magazine = new Magazine($title, $author, $year, $var);
            $this->magazines[] = $magazine;
        }
        $this->savePublications();
    }

    public function printPublications(): void {
        foreach($this->books as $object){
            $object->print();
        }
        foreach($this->magazines as $object){
            $object->print();
        }
    }
    
    private function savePublications(): void {
        $jsonBiblio = [];
        if (count($this->books)>0){
            foreach ($this->books as $object){
                $arrayBook = $object->toArray();
                $jsonBiblio[] = $arrayBook;
            }
        }
        if (count($this->magazines)>0){
            foreach ($this->magazines as $object){
                $arrayMagazine = $object->toArray();
                $jsonBiblio[] = $arrayMagazine;
            }
        }
        $jsonString = json_encode($jsonBiblio, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $jsonString);
    }

    //Métodos para manejar libros
    public function readBooks(): array {
        return $this->books;
    }

    public function deleteBook(int $index): void {
        if (isset($this->books[$index])) {
            unset($this->books[$index]);
            $this->books = array_values($this->books);
            $this->savePublications();
        }
    }

    //Métodos para manejar revistas
    public function readMagazines(): array {
        return $this->magazines;
    }

    public function deleteMagazine(int $index): void {
        if (isset($this->magazines[$index])) {
            unset($this->magazines[$index]);
            $this->magazines = array_values($this->magazines);
            $this->savePublications();
        }
    }


    
}