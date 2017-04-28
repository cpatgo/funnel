<?php

require_once('wp-password.php');

class WP_Helper {
    // Database Connection
    private $dbConnection;

    // Connect to Database
    private function connect($dbHost, $dbUser, $dbPassword, $dbName) {
        try {
            $this->dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Error connecting to database.", 1);
        }
    }

    // Execute MySQL Query
    private function executeQuery($statement, $parameters) {
        $query = $this->dbConnection->prepare($statement);

        if (!empty($parameters)) {
            $result = $query->execute($parameters);
        } else {
            $result = $query->execute();
        }

        return $result;
    }

    // Contstructor
    public function __construct($dbHost = 'localhost', $dbUser = 'root', $dbPassword = 'root', $dbName = 'wordpress') {
        $this->connect($dbHost, $dbUser, $dbPassword, $dbName);
    }

    // Register User
    public function registerUser($firstName, $lastName, $emailAddress, $password) {
        // Set user full name
        $fullName = $firstName . ' ' . $lastName;

        // Hash user password
        $t_hasher   = new PasswordHash(8, TRUE);
        $hash       = $t_hasher->HashPassword($password);

        // Add user to database
        $this->executeQuery("INSERT INTO `wpeu_users` (`user_login`, `user_pass`, `user_nicename`, `user_email`, `user_registered`, `user_status`, `display_name`)
            VALUES (:username, :password, :fullName, :emailAddress, NOW(), '0', :displayName);", [
            ':username'     => $emailAddress,
            ':fullName'     => $fullName,
            ':emailAddress' => $emailAddress,
            ':password'     => $hash,
            ':displayName'  => $fullName
        ]);

        // Get user id
        $userID = $this->dbConnection->lastInsertId('id');

        // Add user information
        $this->executeQuery("INSERT INTO `wpeu_usermeta` (`user_id`, `meta_key`, `meta_value`)
            VALUES (:userID, 'first_name', :firstName);", [
            ':userID'       => $userID,
            ':firstName'    => $firstName
        ]);

        $this->executeQuery("INSERT INTO `wpeu_usermeta` (`user_id`, `meta_key`, `meta_value`)
            VALUES (:userID, 'last_name', :lastName);", [
            ':userID'       => $userID,
            ':lastName'    => $lastName
        ]);

        // Create subscriber user role
        $this->executeQuery("INSERT INTO `wpeu_usermeta` (`user_id`, `meta_key`, `meta_value`)
            VALUES (:userID, 'wpeu_capabilities', 'a:2:{s:4:\"free\";b:1;s:9:\"wpas_user\";b:1;}');", [
            ':userID' => $userID
        ]);

        $this->executeQuery("INSERT INTO `wpeu_usermeta` (`user_id`, `meta_key`, `meta_value`)
            VALUES (:userID, 'wpeu_user_level', '0');", [
            ':userID' => $userID
        ]);

        $this->executeQuery("INSERT INTO `wpeu_usermeta` (`user_id`, `meta_key`, `meta_value`)
            VALUES (:userID, 'membership', 'Free');", [
            ':userID' => $userID
        ]);
    }
}
