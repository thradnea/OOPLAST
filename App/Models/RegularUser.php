<?php
namespace App\Models;

use App\Core\AbstractUser;
use App\Core\AuthInterface;

class RegularUser extends AbstractUser implements AuthInterface {
    public function userRole() {
        return "Regular User";
    }

    public function login($email, $password) {
        if ($email === $this->email && password_verify($password, $this->password)) {
            return "User logged in successfully.";
        }
        return "Invalid credentials.";
    }

    public function logout() {
        return "User logged out.";
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
                $databaseService->logUserActivity($this->id, "Regular user account created");
                return true;
            }
        }
        return false;
    }
}
?>