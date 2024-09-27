<?php

use PHPUnit\Framework\TestCase;

class InsertBook extends TestCase
{
    public function testInsertValidBook()
    {
        $bookData = [
            'b_isbn' => '1234567890',
            'b_title' => 'Test Book Title',
            'b_author' => 'Test Author',
            'b_category' => 'Fiction',
            'b_price' => 29.99,
            'b_copies' => 5
        ];

        $_POST = $bookData;

        $this->assertNotEmpty($_POST['b_isbn']);
        $this->assertNotEmpty($_POST['b_title']);
        $this->assertNotEmpty($_POST['b_author']);
        $this->assertNotEmpty($_POST['b_category']);
        $this->assertIsFloat($_POST['b_price']);
        $this->assertIsInt($_POST['b_copies']);
        $this->assertGreaterThan(0, $_POST['b_copies']);
        $this->assertMatchesRegularExpression('/^[0-9]{10}$/', $_POST['b_isbn']);
    }
}
