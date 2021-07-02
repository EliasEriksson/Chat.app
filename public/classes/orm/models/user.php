<?php


include_once __DIR__ . "/../dbManager.php";


class User
{
    private string $id;
    private string $email;
    private string $passwordHash;

    public static function fromAssoc(array $userData): User
    {
        return new User(
            $userData["id"],
            $userData["email"],
            $userData["passwordHash"]
        );
    }

    public function __construct(string $id, string $email, string $passwordHash)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function authenticate(string $password): bool
    {
        if (password_verify($password, $this->passwordHash)) {
            return true;
        }
        return false;
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}