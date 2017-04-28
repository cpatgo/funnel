<?php

class AEM_Helper {
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
    public function __construct($dbHost = 'localhost', $dbUser = 'root', $dbPassword = 'root', $dbName = 'awebdesk') {
        $this->connect($dbHost, $dbUser, $dbPassword, $dbName);
    }

    // Register User
    public function registerUser($firstName, $lastName, $emailAddress, $password) {
        // Add user to database
        $this->executeQuery("INSERT INTO `awebdesk_user` (`username`, `first_name`, `last_name`, `email`)
            VALUES (:username, :firstName, :lastName, :emailAddress);", [
            ':username'     => $emailAddress,
            ':firstName'    => $firstName,
            ':lastName'     => $lastName,
            ':emailAddress' => $emailAddress,
        ]);

        $this->executeQuery("INSERT INTO `aweb_globalauth` (`username`, `first_name`, `last_name`, `email`, `password`)
            VALUES (:username, :firstName, :lastName, :emailAddress, :password);", [
            ':username'     => $emailAddress,
            ':firstName'    => $firstName,
            ':lastName'     => $lastName,
            ':emailAddress' => $emailAddress,
            ':password'     => md5($password)
        ]);
    }
}
