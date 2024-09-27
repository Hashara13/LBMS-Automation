<?php

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $con;

    protected function setUp(): void
    {
        $this->con = $this->createMock(mysqli::class);
    }

    public function testValidLogin()
    {
        $_POST['l_login'] = true;
        $_POST['l_user'] = 'harry';
        $_POST['l_pass'] = 'librarian';

        $stmt = $this->createMock(mysqli_stmt::class);
        $result = $this->createMock(mysqli_result::class);

        $this->con->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('bind_param')
            ->with('ss', $_POST['l_user'], $this->anything());

        $stmt->expects($this->once())
            ->method('execute');

        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($result);

        $result->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn(['id' => 1]);

        $isValidLogin = $this->checkLogin($this->con, $_POST['l_user'], $_POST['l_pass']);

        $this->assertTrue($isValidLogin, "Login should be valid with correct credentials");
    }

    public function testInvalidLogin()
    {
        $_POST['l_login'] = true;
        $_POST['l_user'] = 'invaliduser';
        $_POST['l_pass'] = 'invalidpass';

        $stmt = $this->createMock(mysqli_stmt::class);
        $result = $this->createMock(mysqli_result::class);

        $this->con->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('bind_param')
            ->with('ss', $_POST['l_user'], $this->anything());

        $stmt->expects($this->once())
            ->method('execute');

        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($result);

        $result->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn(null);

        $isValidLogin = $this->checkLogin($this->con, $_POST['l_user'], $_POST['l_pass']);

        $this->assertFalse($isValidLogin, "Login should be invalid with incorrect credentials");
    }

    private function checkLogin($con, $username, $password)
    {
        $query = $con->prepare("SELECT id FROM librarian WHERE username = ? AND password = ?;");
        $query->bind_param("ss", $username, sha1($password));
        $query->execute();
        $result = $query->get_result();
        $user = $result->fetch_assoc();
        return $user !== null;
    }
}