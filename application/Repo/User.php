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

}
