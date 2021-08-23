<?php


include_once __DIR__ . "/../dbManager.php";


class UserProfile
{
    private string $id;
    private string $username;
    private string $avatar;

    public static function fromAssoc(array $userProfileData): UserProfile
    {
        return new UserProfile(
            $userProfileData["userID"],
            $userProfileData["username"],
            $userProfileData["avatar"]
        );
    }

    public function __construct(string $id, string $username, string $avatar)
    {
        $this->id = $id;
        $this->username = $username;
        $this->avatar = $avatar;
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }
}