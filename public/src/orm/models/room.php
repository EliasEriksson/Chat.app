<?php

include_once __DIR__ . "/../dbManager.php";

abstract class Room
{
    private string $id;
    private string $name;

    public abstract static function fromAssoc(array $roomData): Room;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }


    public function getID(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public abstract function havePassword(): bool;
}