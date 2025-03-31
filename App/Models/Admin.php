<?php
namespace App\Models;

use App\Core\AbstractUser;
use App\Core\AuthInterface;
use App\Core\LoggerTrait;

class Admin extends AbstractUser implements AuthInterface {
    use LoggerTrait;

    public function userRole() {
        return "Admin";
    }

    public function login($email, $password) {
        if ($email === $this->email && password_verify($password, $this->password)) {
            $this->logActivity("Admin $this->name logged in.");
            return "Admin logged in successfully.";
        }
        return "Invalid credentials.";
    }

    public function logout() {
        $this->logActivity("Admin $this->name logged out.");
        return "Admin logged out.";
    }

    public function saveToDatabase($databaseService) {
        $result = $databaseService->saveUser(
            $this->name,
            $this->email,
            $this->password,
            $this->userRole()
        );

        if ($result) {
            $user = $databaseService->getUserByEmail($this->email);
            if ($user) {
                $this->setId($user['id']);
                $databaseService->logUserActivity($this->id, "Admin account created");
                return true;
            }
        }
        return false;
    }
}
?>