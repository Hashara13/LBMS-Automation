<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use LMS\Librarian;
use Mockery;

class LibrarianTest extends TestCase {
    protected $librarianMock;
    protected $dbMock;

    protected function setUp(): void {
        $this->dbMock = Mockery::mock('mysqli');
        $this->librarianMock = new Librarian($this->dbMock);
    }

    protected function tearDown(): void {
        Mockery::close();
    }

    public function testLoginSuccess() {
        $stmtMock = Mockery::mock('mysqli_stmt');
        $this->dbMock->shouldReceive('prepare')->once()->andReturn($stmtMock);
        $stmtMock->shouldReceive('bind_param')->once()->with('ss', 'harry', 'librarian');
        $stmtMock->shouldReceive('execute')->once();
        $stmtMock->shouldReceive('get_result')->once()->andReturn(Mockery::mock('mysqli_result')
            ->shouldReceive('num_rows')->andReturn(1)->getMock());

        $result = $this->librarianMock->login('harry', 'librarian');
        $this->assertTrue($result);
    }

    public function testLoginFailure() {
        $stmtMock = Mockery::mock('mysqli_stmt');
        $this->dbMock->shouldReceive('prepare')->once()->andReturn($stmtMock);
        $stmtMock->shouldReceive('bind_param')->once()->with('ss', 'wrong_user', 'wrong_password');
        $stmtMock->shouldReceive('execute')->once();
        $stmtMock->shouldReceive('get_result')->once()->andReturn(Mockery::mock('mysqli_result')
            ->shouldReceive('num_rows')->andReturn(0)->getMock());

        $result = $this->librarianMock->login('wrong_user', 'wrong_password');
        $this->assertFalse($result);
    }

    public function testInsertBook() {
        $stmtMock = Mockery::mock('mysqli_stmt');
        $this->dbMock->shouldReceive('prepare')->once()->andReturn($stmtMock);
        $stmtMock->shouldReceive('bind_param')->once()->with('sssdii', '1234567890', 'Test Book', 'Test Author', 'Fiction', 19.99, 5);
        $stmtMock->shouldReceive('execute')->once()->andReturn(true);

        $result = $this->librarianMock->insertBook('1234567890', 'Test Book', 'Test Author', 'Fiction', 19.99, 5);
        $this->assertTrue($result);
    }

    public function testUpdateBookCopies() {
        $stmtMock = Mockery::mock('mysqli_stmt');
        $this->dbMock->shouldReceive('prepare')->once()->andReturn($stmtMock);
        $stmtMock->shouldReceive('bind_param')->once()->with('is', 10, '1234567890');
        $stmtMock->shouldReceive('execute')->once()->andReturn(true);

        $result = $this->librarianMock->updateBookCopies('1234567890', 10);
        $this->assertTrue($result);
    }

    public function testDeleteBook() {
        $stmtMock = Mockery::mock('mysqli_stmt');
        $this->dbMock->shouldReceive('prepare')->once()->andReturn($stmtMock);
        $stmtMock->shouldReceive('bind_param')->once()->with('s', '1234567890');
        $stmtMock->shouldReceive('execute')->once()->andReturn(true);

        $result = $this->librarianMock->deleteBook('1234567890');
        $this->assertTrue($result);
    }

    public function testDisplayBooks() {
        $stmtMock = Mockery::mock('mysqli_stmt');
        $this->dbMock->shouldReceive('prepare')->once()->andReturn($stmtMock);
        $stmtMock->shouldReceive('execute')->once();
        $stmtMock->shouldReceive('get_result')->once()->andReturn(Mockery::mock('mysqli_result')
            ->shouldReceive('fetch_all')->andReturn([
                ['isbn' => '1234567890', 'title' => 'Test Book', 'author' => 'Test Author', 'genre' => 'Fiction', 'price' => 19.99, 'copies' => 5]
            ])->getMock());

        $books = $this->librarianMock->displayBooks();
        $this->assertIsArray($books);
        $this->assertCount(1, $books);
        $this->assertEquals('Test Book', $books[0]['title']);
    }
}
