<?php

use PHPUnit\Framework\TestCase;

class LibrarySystemTest extends TestCase
{
    private $baseUrl = 'http://localhost:8000/';

    public function testCompleteLibraryWorkflow()
    {
        $loginResponse = $this->sendPostRequest($this->baseUrl . 'librarian/index.php', [
            'l_login' => true,
            'l_user' => 'harry',
            'l_pass' => 'librarian'
        ]);
        $this->assertStringContainsString('Login successful', $loginResponse);

        $insertBookResponse = $this->sendPostRequest($this->baseUrl . 'librarian/insert_book.php', [
            'b_isbn' => '1234567890',
            'b_title' => 'Test Book',
            'b_author' => 'Test Author',
            'b_category' => 'Fiction',
            'b_price' => 29.99,
            'b_copies' => 5
        ]);
        $this->assertStringContainsString('Book inserted successfully', $insertBookResponse);

        $displayBooksResponse = $this->sendGetRequest($this->baseUrl . 'librarian/display_books.php');
        $this->assertStringContainsString('1234567890', $displayBooksResponse);
        $this->assertStringContainsString('Test Book', $displayBooksResponse);

        $updateCopiesResponse = $this->sendPostRequest($this->baseUrl . 'librarian/update_copies.php', [
            'b_isbn' => '1234567890',
            'b_copies' => 10
        ]);
        $this->assertStringContainsString('Number of book copies has been updated', $updateCopiesResponse);

        $deleteBookResponse = $this->sendPostRequest($this->baseUrl . 'librarian/delete_book.php', [
            'rd_book' => '1234567890'
        ]);
        $this->assertStringContainsString('Book deleted successfully', $deleteBookResponse);
    }

    public function testBookSearch()
    {
        $this->sendPostRequest($this->baseUrl . 'librarian/index.php', [
            'l_login' => true,
            'l_user' => 'librarian',
            'l_pass' => 'password'
        ]);

        $books = [
            ['4444444444', 'Java Fundamentals', 'Alice Brown', 'Programming', 49.99, 3],
            ['5555555555', 'Python for Beginners', 'Charlie Green', 'Programming', 39.99, 4],
            ['6666666666', 'Data Structures', 'David White', 'Computer Science', 59.99, 2],
        ];

        foreach ($books as $book) {
            $this->sendPostRequest($this->baseUrl . 'librarian/insert_book.php', [
                'b_isbn' => $book[0],
                'b_title' => $book[1],
                'b_author' => $book[2],
                'b_category' => $book[3],
                'b_price' => $book[4],
                'b_copies' => $book[5]
            ]);
        }

        $searchResponse = $this->sendGetRequest($this->baseUrl . 'librarian/search_books.php?query=Python');
        $this->assertStringContainsString('Python for Beginners', $searchResponse);
        $this->assertStringNotContainsString('Java Fundamentals', $searchResponse);

        foreach ($books as $book) {
            $this->sendPostRequest($this->baseUrl . 'librarian/delete_book.php', [
                'rd_book' => $book[0]
            ]);
        }
    }

    public function testBookCheckoutAndReturn()
    {
        $this->sendPostRequest($this->baseUrl . 'login.php', [
            'l_login' => true,
            'l_user' => 'librarian',
            'l_pass' => 'password'
        ]);

        $isbn = '7777777777';
        $this->sendPostRequest($this->baseUrl . 'librarian/insert_book.php', [
            'b_isbn' => $isbn,
            'b_title' => 'Test Checkout Book',
            'b_author' => 'Test Author',
            'b_category' => 'Fiction',
            'b_price' => 29.99,
            'b_copies' => 2
        ]);

        $checkoutResponse = $this->sendPostRequest($this->baseUrl . 'librarian/checkout_book.php', [
            'member_id' => '12345',
            'book_isbn' => $isbn
        ]);
        $this->assertStringContainsString('Book checked out successfully', $checkoutResponse);

        $displayBooksResponse = $this->sendGetRequest($this->baseUrl . 'librarian/display_books.php');
        $this->assertStringContainsString('Test Checkout Book', $displayBooksResponse);
        $this->assertStringContainsString('Copies: 1', $displayBooksResponse);

        $returnResponse = $this->sendPostRequest($this->baseUrl . 'librarian/return_book.php', [
            'member_id' => '12345',
            'book_isbn' => $isbn
        ]);
        $this->assertStringContainsString('Book returned successfully', $returnResponse);

        $displayBooksResponse = $this->sendGetRequest($this->baseUrl . 'librarian/display_books.php');
        $this->assertStringContainsString('Test Checkout Book', $displayBooksResponse);
        $this->assertStringContainsString('Copies: 2', $displayBooksResponse);

        $this->sendPostRequest($this->baseUrl . 'librarian/delete_book.php', [
            'rd_book' => $isbn
        ]);
    }

    private function sendPostRequest($url, $data)
    {
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context  = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }

    private function sendGetRequest($url)
    {
        return file_get_contents($url);
    }
}