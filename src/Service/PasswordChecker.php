<?php



namespace App\Service;

class PasswordChecker
{
    public function isStrong(string $password): bool
    {
        
        return strlen($password) >= 6 && !in_array($password, ['azerty', 'qwerty', '123456']);
    }
}