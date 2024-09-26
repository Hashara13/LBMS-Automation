<?php

namespace Tests\System;

use PHPUnit\Framework\TestCase;
use LMS\Librarian;
use mysqli;

class LibraryManagementTest extends TestCase
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

    public function testLibrarianWorkflow()
    {
        $loginResult = $this->librarian->login('harry', 'librarian');
        $this->assertTrue($loginResult);

        $insertResult = $this->librarian->insertBook('1234567890', 'Test Book', 'Test Author', 'Fiction', 19.99, 5);
        $this->assertTrue($insertResult);

        $updateResult = $this->librarian->updateBookCopies('1234567890', 10);
        $this->assertTrue($updateResult);

        $books = $this->librarian->displayBooks();
        $this->assertIsArray($books);
        $this->assertGreaterThan(0, count($books));
        $testBook = array_filter($books, function($book) {
            return $book['isbn'] === '1234567890';
        });
        $this->assertCount(1, $testBook);
        $this->assertEquals(15, reset($testBook)['copies']);

        $deleteResult = $this->librarian->deleteBook('1234567890');
        $this->assertTrue($deleteResult);

        $booksAfterDelete = $this->librarian->displayBooks();
        $testBookAfterDelete = array_filter($booksAfterDelete, function($book) {
            return $book['isbn'] === '1234567890';
        });
        $this->assertCount(0, $testBookAfterDelete);
    }
}