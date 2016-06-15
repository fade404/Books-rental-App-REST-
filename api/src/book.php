<?php

$conn = new mysqli('localhost', 'root', '', 'warsztaty');
if ($conn->connect_error) {
    die('Przepraszamy, pracujemy nad bledem.<br>' . $conn->connect_error);
}

class Book {

    private $id;
    private $name;
    private $author;
    private $description;

    public function __construct() {

        $this->id = -1;
        $this->name = '';
        $this->author = '';
        $this->description = '';
    }

    public function loadFromDb(&$conn, $id) {
        if (!is_numeric($id) || $id < 1) {
            return false;
        }

        $sql = "SELECT * FROM books WHERE id=$id";

        $result = $conn->query($sql);
        if (count($result) !== 1) {
            return false;
        }
        $linia = $result->fetch_assoc();
        $this->id = $id;
        $this->name = $linia['nazwa'];
        $this->author = $linia['autor'];
        $this->description = $linia['opis'];

        return $this;
    }

    public function create(&$conn, $name, $author, $description) {
        if (!(is_string($name) && is_string($author) && is_string($description))) {
            return false;
        }
        
        if (empty($name) && empty($author) && empty($description)) {
            return false;
        }

        $sql = "INSERT INTO books (nazwa, autor, opis) VALUES ('" . addslashes($name) . "', '" . addslashes($author) . "', '" . addslashes($description) . "' )";

        $conn->query($sql);

        $this->id = $conn->insert_id;
        $this->name = $name;
        $this->author = $author;
        $this->description = $description;

        return $this;
    }

    public function update(&$conn, $name, $author, $description) {
        if ($this->id < 1) {
            return false;
        }
        if (!(is_string($name) && is_string($author) && is_string($description))) {
            return false;
        }
        
        if (empty($name) && empty($author) && empty($description)) {
            return false;
        }

        $sql = "UPDATE books SET nazwa='" . addslashes($name) . "', autor='" . addslashes($author) . "', opis='" . addslashes($description) . "' WHERE id=$this->id";

        $conn->query($sql);

        $this->name = $name;
        $this->author = $author;
        $this->description = $description;

        return $this;
    }

    public function deleteFromDb(&$conn) {
        if ($this->id < 1) {
            return false;
        }
        
        $sql = "DELETE FROM books WHERE id=$this->id";

        $conn->query($sql);       
    }

    public function getBook() {
        if ($this->id < 1) {
            return false;
        }
        return array(
            'name' => $this->name,
            'id' => $this->id,
            'author' => $this->author,
            'desc' => $this->description
        );
    }

}
