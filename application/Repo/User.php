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

}
