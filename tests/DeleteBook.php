<?php

use PHPUnit\Framework\TestCase;

class DeleteBook extends TestCase
{
 
    public function testRemoveBookFunctionality()
    {
        $_POST['rd_book'] = '1234567890'; 
        
        ob_start();
        require __DIR__ . '/../librarian/delete_book.php'; 
        $output = ob_get_clean();

        $this->assertStringContainsString('Book deleted successfully', $output);
    }
}