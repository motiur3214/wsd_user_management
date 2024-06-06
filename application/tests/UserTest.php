<?php
// Load Composer's autoloader
//if loading config is giving error please give DB credentials to config.php statically
require_once $_SERVER['DOCUMENT_ROOT'] . 'vendor/autoload.php';
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/repo/Database.php';
require_once dirname(__DIR__) . '/repo/User.php';

// PHPUnit framework for tests
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $mockDatabase;

    public function setUp(): void
    {

        $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        // Create a mock Database class
        $this->mockDatabase = $this->createMock(Database::class);
    }

    public function testLoginSuccess()
    {
        // Mock PDO connection and prepared statement
        $mockPdo = $this->createMock(PDO::class);
        $mockStmt = $this->createMock(PDOStatement::class);

        $user = [
            "email" => "Tana@gmail.com",
            "password" => "123456",
        ];
        $userData = [
            "email" => "Tana@gmail.com",
            "password" => password_hash("123456", PASSWORD_DEFAULT),
        ];

        // Mock successful execution and user fetch with hashed password
        $mockStmt->method("execute")->willReturn(true);
        $mockStmt->method("fetch")->willReturn($userData);
        $mockPdo->method("prepare")->willReturn($mockStmt);

        // Mock Database to return the PDO connection
        $this->mockDatabase->method("getConnection")->willReturn($mockPdo);

        // Create User object with mocked Database
        $userClass = new User($this->mockDatabase);

        $loggedInUser = $userClass->login($user["email"], $user["password"]);


        $this->assertTrue(isset($loggedInUser['email']));
        print_r($this->assertTrue(isset($loggedInUser['email'])));
        // Verify password is not returned in the result
        $this->assertArrayNotHasKey("password", $loggedInUser);
    }

    public function testLoginIncorrectPassword()
    {
        // Mock PDO connection and prepared statement
        $mockPdo = $this->createMock(PDO::class);
        $mockStmt = $this->createMock(PDOStatement::class);

        $user = [
            "email" => "test@example.com",
            "password" => "wrongpassword"
        ];

        // Mock successful execution but no user found (incorrect password)
        $mockStmt->method("execute")->willReturn(true);
        $mockStmt->method("fetch")->willReturn(false);
        $mockPdo->method("prepare")->willReturn($mockStmt);

        // Mock Database to return the PDO connection
        $this->mockDatabase->method("getConnection")->willReturn($mockPdo);

        // Create User object with mocked Database
        $userClass = new User($this->mockDatabase);

        $loggedInUser = $userClass->login($user["email"], $user["password"]);
        $this->assertFalse($loggedInUser);
    }
}
