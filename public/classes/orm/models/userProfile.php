<?php


include_once __DIR__ . "/../manager.php";


class UserProfile
{
    private string $id;
    private string $username;
    private string $avatar;

    public function fromAssoc(array $userProfileData): UserProfile
    {
        return new UserProfile(
            $userProfileData["id"],
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

    public function getId(): string
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