<?php
// Load Composer's autoloader
//if loading config is giving error please give DB credentials to config.php statically

require_once'../../vendor/autoload.php';
require_once  '../config.php';
require_once   '../repo/Database.php';
require_once '../repo/User.php';

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

    public function testUserFound()
    {
        // Mock PDO connection and prepared statement
        $mockPdo = $this->createMock(PDO::class);
        $mockStmt = $this->createMock(PDOStatement::class);

        $user = [
            "id" => 1,
            "name" => "John Doe",
            "email" => "john.doe@example.com"
        ];

        // Mock successful execution and user fetch
        $mockStmt->method("execute")->willReturn(true);
        $mockStmt->method("fetch")->willReturn($user);
        $mockPdo->method("prepare")->willReturn($mockStmt);

        // Mock Database to return the PDO connection
        $this->mockDatabase->method("getConnection")->willReturn($mockPdo);

        // Create User object with mocked Database
        $userClass = new User($this->mockDatabase);

        // Call getUserById
        $fetchedUser = $userClass->getUserById(1);

        // Assertions
        $this->assertEquals($user, $fetchedUser);
    }

    public function testUserNotFound()
    {
        // Mock PDO connection and prepared statement
        $mockPdo = $this->createMock(PDO::class);
        $mockStmt = $this->createMock(PDOStatement::class);

        // Mock successful execution but no user found
        $mockStmt->method("execute")->willReturn(true);
        $mockStmt->method("fetch")->willReturn(false);
        $mockPdo->method("prepare")->willReturn($mockStmt);

        // Mock Database to return the PDO connection
        $this->mockDatabase->method("getConnection")->willReturn($mockPdo);

        // Create User object with mocked Database
        $userClass = new User($this->mockDatabase);

        // Call getUserById
        $fetchedUser = $userClass->getUserById(10);

        // Assertions
        $this->assertNull($fetchedUser);
    }

    public function testSearchUsersNoMatch()
    {
        // Mock PDO connection and prepared statement
        $mockPdo = $this->createMock(PDO::class);
        $mockStmt = $this->createMock(PDOStatement::class);

        // Mock successful execution but no rows returned
        $mockStmt->method("execute")->willReturn(true);
        $mockStmt->method("fetch")->willReturn([]);
        $mockPdo->method("prepare")->willReturn($mockStmt);

        // Mock Database to return the PDO connection
        $this->mockDatabase->method("getConnection")->willReturn($mockPdo);

        // Mock logged-in user ID
        $_SESSION['user_id'] = 1;

        // Create User object with mocked Database
        $userClass = new User($this->mockDatabase);

        // Call searchUsers
        $searchResults = $userClass->searchUsers("Unknown Name"); // No match

        // Assertions
        $this->assertEmpty($searchResults); // Assert no users found
    }

    public function testEmailExists()
    {
        // Mock PDO connection and prepared statement
        $mockPdo = $this->createMock(PDO::class);
        $mockStmt = $this->createMock(PDOStatement::class);

        // Mock successful execution and one row returned
        $mockStmt->method("execute")->willReturn(true);
        $mockStmt->method("rowCount")->willReturn(1);
        $mockPdo->method("prepare")->willReturn($mockStmt);

        // Mock Database to return the PDO connection
        $this->mockDatabase->method("getConnection")->willReturn($mockPdo);

        // Create User object with mocked Database
        $userClass = new User($this->mockDatabase);

        // Call isEmailExists
        $emailExists = $userClass->isEmailExists("john.doe@example.com");

        // Assertions
        $this->assertTrue($emailExists);
    }

    public function testEmailDoesNotExist()
    {
        // Mock PDO connection and prepared statement
        $mockPdo = $this->createMock(PDO::class);
        $mockStmt = $this->createMock(PDOStatement::class);

        // Mock successful execution but no rows returned
        $mockStmt->method("execute")->willReturn(true);
        $mockStmt->method("rowCount")->willReturn(0);
        $mockPdo->method("prepare")->willReturn($mockStmt);

        // Mock Database to return the PDO connection
        $this->mockDatabase->method("getConnection")->willReturn($mockPdo);

        // Create User object with mocked Database
        $userClass = new User($this->mockDatabase);

        // Call isEmailExists
        $emailExists = $userClass->isEmailExists("unknown@example.com");

        // Assertions
        $this->assertFalse($emailExists);
    }

}
