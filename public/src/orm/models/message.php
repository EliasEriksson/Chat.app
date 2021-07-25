<?php


include_once __DIR__ . "/../dbManager.php";


class Message
{
    private string $id;
    private string $userID;
    private string $roomID;
    private DateTime $postDate;
    private string $content;


    public static function fromAssoc(array $messageData): Message
    {

        return new Message(
            $messageData["id"],
            $messageData["userID"],
            $messageData["roomID"],
            $messageData["postDate"],
            $messageData["content"]
        );
    }

    public function __construct(string $id, string $userID, string $roomID, string $postDate, string $content)
    {
        $format = "Y-m-d H:i:s";
        $this->id = $id;
        $this->userID = $userID;
        $this->roomID = $roomID;
        $this->postDate = DateTime::createFromFormat($format, $postDate, new DateTimeZone("utc"));
        $this->content = $content;
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getUserID(): string
    {
        return $this->userID;
    }

    public function getRoomID(): string
    {
        return $this->roomID;
    }

    public function getPostDate(): DateTime|bool
    {
        return $this->postDate;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}