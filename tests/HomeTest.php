<?php
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
    protected function setUp(): void
    {
        session_start();
        $_SESSION['type'] = 'librarian';
        define('RUNNING_TESTS', true);
    }

    public function testInsertBookButton()
    {
        ob_start();
        echo '<html><body>';
        echo '<a href="insert_book.php"><input type="button" value="Insert New Book Record" /></a>';
        echo '</body></html>';
        $output = ob_get_clean();

        $this->assertStringContainsString('href="insert_book.php"', $output);
    }

    public function testUpdateCopiesButton()
    {
        ob_start();
        echo '<html><body>';
        echo '<a href="update_copies.php"><input type="button" value="Update Copies of a Book" /></a>';
        echo '</body></html>';
        $output = ob_get_clean();

        $this->assertStringContainsString('href="update_copies.php"', $output);
    }

    public function testDeleteBookButton()
    {
        ob_start();
        echo '<html><body>';
        echo '<a href="delete_book.php"><input type="button" value="Delete Book Records" /></a>';
        echo '</body></html>';
        $output = ob_get_clean();

        $this->assertStringContainsString('href="delete_book.php"', $output);
    }

    public function testDisplayBooksButton()
    {
        ob_start();
        echo '<html><body>';
        echo '<a href="display_books.php"><input type="button" value="Display Available Books" /></a>';
        echo '</body></html>';
        $output = ob_get_clean();

        $this->assertStringContainsString('href="display_books.php"', $output);
    }
}
