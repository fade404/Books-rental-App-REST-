<?php

require './src/book.php';



switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (!isset($_GET['id']) || $_GET['id'] < 1) {
            //pokaz wszystkie ksiazki

            $sql = "SELECT id FROM books";

            $result = $conn->query($sql);

            $ksiazki = [];
            while ($row = $result->fetch_assoc()) {
                $ksiazki[] = (new Book())->loadFromDb($conn, $row['id'])->getBook();
            }
            echo json_encode($ksiazki);
        } else {
            $id = $_GET['id'];
            $book = new Book();
            $book->loadFromDB($conn, $id);

            echo json_encode($book->getBook());
        }
        break;

    case 'POST':
        $formName = $conn->escape_string(trim($_POST['name']));
        $formAuthor = $conn->escape_string(trim($_POST['author']));
        $formDesc = $conn->escape_string(trim($_POST['desc']));
        
        $book = new Book();
        $res = $book->create($conn, $formName, $formAuthor, $formDesc);
        
        
        $ans = [
            'status' => !!$res
        ];

        echo json_encode($ans);

        break;

    case 'PUT':

        //pobranie danych
        parse_str(file_get_contents('php://input'), $put_vars);
        //$put_vars odtad przechowuje wszystkie przeslane wartosci

        $id = $put_vars['id'];
        $name = $put_vars['name'];
        $author = $put_vars['author'];
        $description = $put_vars['description'];
        
        $book = new Book();
        $book->loadFromDB($conn, $id);
        $res = $book->update($conn, $name, $author, $description);
        
        $ans = [
            'status' => !!$res
        ];
        
        echo json_encode($ans);

        break;
    case 'DELETE':

        $id = $_GET['id'];
        $book = new Book();
        $book->loadFromDB($conn, $id);
        $res = $book->deleteFromDb($conn);

        $ans = [
            'status' => !!$res
        ];

        echo json_encode($ans);


        break;

    default:
        break;
}
    