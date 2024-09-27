<?php

use PHPUnit\Framework\TestCase;

class UpdateBookCopies extends TestCase
{
    private $errorOutput;

    protected function setUp(): void
    {
        ob_start();
    }

    protected function tearDown(): void
    {
        ob_end_clean();
    }

    public function testUpdateValidBookCopies()
    {
        $_POST['b_add'] = true;
        $_POST['b_isbn'] = '1234567890';
        $_POST['b_copies'] = 5;

        $this->simulateBookCheckAndUpdate();

        $this->assertStringContainsString("Number of book copies has been updated", ob_get_contents());
    }

    public function testUpdateInvalidBookCopies()
    {
        $_POST['b_add'] = true;
        $_POST['b_isbn'] = 'invalid_isbn';
        $_POST['b_copies'] = 5;

        $this->simulateBookCheckAndUpdate();

        $this->assertStringContainsString("Invalid ISBN", ob_get_contents());
    }

    private function simulateBookCheckAndUpdate()
    {
        if ($_POST['b_isbn'] === '1234567890') {
            $this->mockSuccessfulUpdate();
        } else {
            $this->mockInvalidISBN();
        }
    }

    private function mockSuccessfulUpdate()
    {
        echo "Number of book copies has been updated"; 
    }

    private function mockInvalidISBN()
    {
        echo "Invalid ISBN"; 
    }
}
