<?php

class User
{
    private PDO $connection;

    public function __construct(Database $db)
    {
        $this->connection = $db->getConnection();
    }

    public function login($email, $password)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user["password"])) {
                    unset($user['password']);
                    return $user;
                } else {
                    return false; // Incorrect password
                }
            } else {
                return false; // User not found
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getUserById($user_id)
    {
        try {

            $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :user_id");

            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Specify integer binding
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                return $user;
            } else {
                return null; // User not found
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function countUsers()
    {
        try {
            $stmt = $this->connection->prepare("SELECT COUNT(*) AS total_users FROM users");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total_users']; // Return total user count
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getAllUsers($offset = 0, $limit = 10) // Add optional parameters
    {
        try {
            $user_id = $_SESSION['user_id'];
            $stmt = $this->connection->prepare("
            SELECT * FROM users
            WHERE id != :user_id
            LIMIT :limit OFFSET :offset
        ");

            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $users = array();
            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $user; // Add user data to array
            }
            return $users; // Return array of user data
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function searchUsers($searchText)
    {
        try {
            $user_id = $_SESSION['user_id']; // Exclude the logged-in user
            $searchText = urldecode($searchText);
            $searchTerm = "%$searchText%"; // Add wildcards for partial name or email matching
            $stmt = $this->connection->prepare("
            SELECT * FROM users
            WHERE id != :user_id
                AND (name LIKE :searchTerm OR email LIKE :searchTerm)
        ");

            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':searchTerm', $searchTerm);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return array of matching user data
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateUser(array $user): bool
    {
        // Base SQL statement
        $sql = "UPDATE users SET name = :name, email = :email";

        // Add password to the SQL statement if it is provided
        if (!empty($user["password"])) {
            $sql .= ", password = :password";
        }

        // Append the condition for the specific user ID
        $sql .= " WHERE id = :id";

        // Prepare the SQL statement
        $stmt = $this->connection->prepare($sql);

        // Bind the required parameters
        $stmt->bindParam(":name", $user["name"]);
        $stmt->bindParam(":email", $user["email"]);
        $stmt->bindParam(":id", $user["id"], PDO::PARAM_INT);

        // Bind the password parameter only if it is provided
        if (!empty($user["password"])) {
            $stmt->bindParam(":password", $user["password"]);
        }

        // Execute the statement and check for success
        if ($stmt->execute()) {
            return true; // Update successful
        } else {
            // Handle update failure (e.g., log the error)
            error_log("Update failed: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }


    public function deleteUser(int $userId): bool
    {
        // Prepare SQL statement to delete a user by ID
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        // Bind the user ID parameter
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);

        // Execute the delete statement and check for success
        if ($stmt->execute()) {
            return true; // Deletion successful
        } else {
            // Handle deletion failure (e.g., log the error)
            error_log("Deletion failed: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }

    public function isEmailExists($email)
    {
        try {

            $stmt = $this->connection->prepare("SELECT id FROM users WHERE email = :email");

            $stmt->bindParam(':email', $email, PDO::PARAM_STR); // Specify string binding for email

            $stmt->execute();

            $rowCount = $stmt->rowCount(); // Count rows returned

            return $rowCount > 0;  // Return true if email exists (at least one row)
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function isEmailDuplicate($email, $user_id)
    {
        try {

            $stmt = $this->connection->prepare("SELECT id FROM users WHERE email = :email and id != :user_id");

            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $user_id);

            $stmt->execute();

            $rowCount = $stmt->rowCount(); // Count rows returned

            return $rowCount > 0;  // Return true if email exists (at least one row)
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function registration(array $user): bool
    {
        // Prepare SQL statement (using parameterized query for security)
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->connection->prepare($sql);
        // Bind values to prevent SQL injection vulnerabilities
        $stmt->bindParam(":name", $user["name"]);
        $stmt->bindParam(":email", $user["email"]);
        $stmt->bindParam(":password", $user["password"]);
        $stmt->bindParam(":role", $user["role"], PDO::PARAM_INT);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            // Handle registration failure (e.g., log the error)
            error_log("Registration failed: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }
}
