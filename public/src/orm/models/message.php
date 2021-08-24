<?php
include_once __DIR__ . "/../dbManager.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/room.php";


class Message
{
    private string $id;
    private User $user;
    private UserProfile $userProfile;
    private Room $room;
    private int $postDate;
    private string $content;

    private string $oldDate = "Y:m:d H:i";
    private string $recentDate = "H:i";


    public static function fromAssoc(array $messageData): Message
    {
        return new Message(
            $messageData["id"],
            new User($messageData["userID"], $messageData["email"], $messageData["passwordHash"]),
            new UserProfile($messageData["userID"], $messageData["username"], $messageData["avatar"], $messageData["timezone"]),
            new PublicRoom($messageData["roomID"], $messageData["roomName"]),
            $messageData["postDate"],
            $messageData["content"]
        );
    }

    public function getAllAsAssoc(): array
    {
        return [
            "id" => $this->id,
            "userID" => $this->user->getID(),
            "roomID" => $this->room->getID(),
            "email" => $this->user->getEmail(),
            "username" => $this->userProfile->getUsername(),
            "avatar" => $this->userProfile->getAvatar(),
            "content" => $this->content,
            "postDate" => $this->getFormattedPostDate()
        ];
    }

    public function __construct(
        string $id, User $user,
        UserProfile $userProfile, Room $room,
        int $postDate, string $content
    )
    {
        $this->id = $id;
        $this->user = $user;
        $this->userProfile = $userProfile;
        $this->room = $room;
        $this->postDate = $postDate;
        $this->content = $content;

    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserProfile(): UserProfile
    {
        return $this->userProfile;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function getPostDate(): int
    {
        return $this->postDate;
    }

    public function getFormattedPostDate(): string
    {
        $tomorrow = new DateTime("tomorrow");
        $then =  DateTime::createFromFormat(
            "U",
            $this->postDate,
            new DateTimeZone("UTC")
        );
        $then->setTimezone(new DateTimeZone($this->userProfile->getTimezone()));
        if (($diff = $tomorrow->getTimestamp() - $then->getTimestamp()) < 86400 ) { // difference less than one day
            return "Today at "  . $then->format($this->recentDate);
        } else if ($diff < 172800) { // difference less than two days
            return "Yesterday at " . $then->format($this->recentDate);
        }
        return $then->format($this->oldDate);
    }

    public function getContent(): string
    {
        return $this->content;
    }
}