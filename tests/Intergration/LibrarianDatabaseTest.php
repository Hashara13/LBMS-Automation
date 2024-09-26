<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use LMS\Librarian;
use mysqli;

class LibrarianDatabaseTest extends TestCase
{
    protected $librarian;
    protected $db;

    protected function setUp(): void
    {
        $this->db = new mysqli('localhost', '', '', 'lms_test');
        $this->librarian = new Librarian($this->db);
    }

    protected function tearDown(): void
    {
        $this->db->query("DELETE FROM book WHERE isbn = '1234567890'");
        $this->db->close();
    }

    public function testLogin()
    {
        $result = $this->librarian->login('harry', 'librarian');
        $this->assertTrue($result);
    }

    public function testInsertBook()
    {
        $result = $this->librarian->insertBook('1234567890', 'Test Book', 'Test Author', 'Fiction', 19.99, 5);
        $this->assertTrue($result);

        $bookResult = $this->db->query("SELECT * FROM book WHERE isbn = '1234567890'");
        $book = $bookResult->fetch_assoc();
        $this->assertEquals('Test Book', $book['title']);
    }

    public function testUpdateBookCopies()
    {
        $this->librarian->insertBook('1234567890', 'Test Book', 'Test Author', 'Fiction', 19.99, 5);
        $result = $this->librarian->updateBookCopies('1234567890', 10);
        $this->assertTrue($result);

        $bookResult = $this->db->query("SELECT copies FROM book WHERE isbn = '1234567890'");
        $book = $bookResult->fetch_assoc();
        $this->assertEquals(15, $book['copies']);
    }

    public function testDeleteBook()
    {
        $this->librarian->insertBook('1234567890', 'Test Book', 'Test Author', 'Fiction', 19.99, 5);
        $result = $this->librarian->deleteBook('1234567890');
        $this->assertTrue($result);

        $bookResult = $this->db->query("SELECT * FROM book WHERE isbn = '1234567890'");
        $this->assertEquals(0, $bookResult->num_rows);
    }

    public function testDisplayBooks()
    {
        $this->librarian->insertBook('1234567890', 'Test Book', 'Test Author', 'Fiction', 19.99, 5);
        $books = $this->librarian->displayBooks();
        $this->assertIsArray($books);
        $this->assertGreaterThan(0, count($books));
        $this->assertEquals('Test Book', $books[0]['title']);
    }
}