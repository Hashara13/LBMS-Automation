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
        // ... (previous test case)
    }

    public function testUpdateBookCopies()
    {
        // Insert a book
        $isbn = '9876543210';
        $title = 'Update Test Book';
        $author = 'Update Test Author';
        $category = 'Non-Fiction';
        $price = 39.99;
        $copies = 3;

        $stmt = $this->db->prepare("INSERT INTO book (isbn, title, author, category, price, copies) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdi", $isbn, $title, $author, $category, $price, $copies);
        $stmt->execute();

        // Update the number of copies
        $newCopies = 5;
        $stmt = $this->db->prepare("UPDATE book SET copies = ? WHERE isbn = ?");
        $stmt->bind_param("is", $newCopies, $isbn);
        $stmt->execute();

        // Verify the update
        $result = $this->db->query("SELECT copies FROM book WHERE isbn = '$isbn'");
        $row = $result->fetch_assoc();
        $this->assertEquals($newCopies, $row['copies']);

        // Clean up
        $this->db->query("DELETE FROM book WHERE isbn = '$isbn'");
    }

    public function testSearchBookByTitle()
    {
        // Insert test books
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

        // Search for books with 'Programming' in the title
        $searchTerm = 'Programming';
        $result = $this->db->query("SELECT * FROM book WHERE title LIKE '%$searchTerm%'");
        
        $this->assertEquals(1, $result->num_rows);
        $row = $result->fetch_assoc();
        $this->assertEquals('PHP Programming', $row['title']);

        // Clean up
        $this->db->query("DELETE FROM book WHERE isbn IN ('1111111111', '2222222222', '3333333333')");
    }
}