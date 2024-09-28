<?php

use PHPUnit\Framework\TestCase;

class BookManagementTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        $this->db = new mysqli("localhost", "root", "", "librarygh");
    }

    protected function tearDown(): void
    {
        $this->db->close();
    }

    public function testInsertAndDeleteBook()
    {
        $isbn = '1234567890';
        $title = 'Test Book';
        $author = 'Test Author';
        $category = 'Fiction';
        $price = 29.99;
        $copies = 5;

        $stmt = $this->db->prepare("INSERT INTO book (isbn, title, author, category, price, copies) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdi", $isbn, $title, $author, $category, $price, $copies);
        $stmt->execute();

        $result = $this->db->query("SELECT * FROM book WHERE isbn = '$isbn'");
        $this->assertEquals(1, $result->num_rows);

        $this->db->query("DELETE FROM book WHERE isbn = '$isbn'");

        $result = $this->db->query("SELECT * FROM book WHERE isbn = '$isbn'");
        $this->assertEquals(0, $result->num_rows);
    }
    public function testUpdateBookCopies()
    {
        $isbn = '9876543210';
        $title = 'Update Test Book';
        $author = 'Update Test Author';
        $category = 'Non-Fiction';
        $price = 39.99;
        $copies = 3;

        $stmt = $this->db->prepare("INSERT INTO book (isbn, title, author, category, price, copies) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdi", $isbn, $title, $author, $category, $price, $copies);
        $stmt->execute();

        $newCopies = 5;
        $stmt = $this->db->prepare("UPDATE book SET copies = ? WHERE isbn = ?");
        $stmt->bind_param("is", $newCopies, $isbn);
        $stmt->execute();

        $result = $this->db->query("SELECT copies FROM book WHERE isbn = '$isbn'");
        $row = $result->fetch_assoc();
        $this->assertEquals($newCopies, $row['copies']);

        $this->db->query("DELETE FROM book WHERE isbn = '$isbn'");
    }

    public function testSearchBookByTitle()
    {
        $books = [
            ['1111111111', 'PHP Programming', 'John Doe', 'Programming', 45.99, 2],
            ['2222222222', 'MySQL Basics', 'Jane Smith', 'Database', 39.99, 3],
            ['3333333333', 'Web Development', 'Bob Johnson', 'Programming', 55.99, 1],
        ];

        $stmt = $this->db->prepare("INSERT INTO book (isbn, title, author, category, price, copies) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($books as $book) {
            $stmt->bind_param("ssssdi", $book[0], $book[1], $book[2], $book[3], $book[4], $book[5]);
            $stmt->execute();
        }

        $searchTerm = 'Programming';
        $result = $this->db->query("SELECT * FROM book WHERE title LIKE '%$searchTerm%'");
        
        $this->assertEquals(1, $result->num_rows);
        $row = $result->fetch_assoc();
        $this->assertEquals('PHP Programming', $row['title']);

        $this->db->query("DELETE FROM book WHERE isbn IN ('1111111111', '2222222222', '3333333333')");
    }
}