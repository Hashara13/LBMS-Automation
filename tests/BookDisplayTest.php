<?php

use PHPUnit\Framework\TestCase;

class BookDisplayTest extends TestCase
{
    protected static $con;

    public static function setUpBeforeClass(): void
    {
        self::$con = new mysqli("localhost", "root", "", "librarygh");
        if (self::$con->connect_error) {
            die("Connection failed: " . self::$con->connect_error);
        }

        self::$con->query("INSERT INTO book (isbn, title, author, category, price, copies) VALUES 
            ('9780141036144', 'Test Book 1', 'Author 1', 'Fiction', 500, 10),
            ('9780141036145', 'Test Book 2', 'Author 2', 'Non-Fiction', 600, 5)");
    }

    public static function tearDownAfterClass(): void
    {
        self::$con->query("DELETE FROM book WHERE isbn IN ('9780141036144', '9780141036145')");
        self::$con->close();
    }

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['type'] = 'librarian'; 
    }

    public function testBookDisplayPage()
    {
        ob_start(); 
        include '../librarian/display_books.php';
        $output = ob_get_clean(); 

        $this->assertStringContainsString('<table', $output);
        $this->assertStringContainsString('2 books found', $output);
    }

    public function testIndividualBookDetails()
    {
        ob_start();
        include '../librarian/display_books.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('9780141036144', $output);
    }
}
