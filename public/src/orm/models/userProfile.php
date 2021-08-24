<?php


include_once __DIR__ . "/../dbManager.php";


class UserProfile
{
    private string $id;
    private string $username;
    private string $avatar;
    private string $timezone;

    public static function fromAssoc(array $userProfileData): UserProfile
    {
        return new UserProfile(
            $userProfileData["userID"],
            $userProfileData["username"],
            $userProfileData["avatar"],
            $userProfileData["timezone"]
        );
    }

    public function __construct(string $id, string $username, string $avatar, string $timezone)
    {
        $this->id = $id;
        $this->username = $username;
        $this->avatar = $avatar;
        $this->timezone = $timezone;
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

    public function getTimezone(): string
    {
        return $this->timezone;
    }
}