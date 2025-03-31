<?php
namespace App\Core;

abstract class AbstractUser {
    protected $id;
    protected $name;
    protected $email;
    protected $password;

    public function __construct($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Force child classes to implement userRole()
    abstract public function userRole();

    // Add a method for saving user to database
    abstract public function saveToDatabase($databaseService);
}
?>