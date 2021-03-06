<?php
include_once __DIR__ . "/../uuid.php";
include_once __DIR__ . "/models/user.php";
include_once __DIR__ . "/models/userProfile.php";
include_once __DIR__ . "/models/session.php";
include_once __DIR__ . "/models/publicRoom.php";
include_once __DIR__ . "/models/privateRoom.php";
include_once __DIR__ . "/models/message.php";


class DbManager
{
    private mysqli $dbConn;
    private int $pageLimit;

    function __construct()
    {
        $this->dbConn = new mysqli(
            $_SERVER["DB_HOSTNAME"],
            $_SERVER["DB_USER"],
            $_SERVER["DB_PASS"],
            $_SERVER["DB_NAME"]
        );
        if ($this->dbConn->connect_errno !== 0) {
            die("Could not connect. Error: " . $this->dbConn->connect_errno . " " . $this->dbConn->connect_error);
        }
        $this->pageLimit = 5;
    }

    /**
     * searches the error after a message stating that the given key resulted in errno 1062.
     *
     * if the specified key threw the error return true else false.
     *
     * @param string $key
     * @return bool
     */
    private function checkDuplicateKey(string $key): bool
    {
        if ($this->dbConn->errno === 1062) {
            if (preg_match("/^Duplicate\sentry\s'[^ ]+'\sfor\skey\s'$key'$/", $this->dbConn->error)) {
                return true;
            }
        }
        return false;
    }

    /**
     * attempts to create a new user in the database with given email and password
     *
     * if the creation fails it is attempted up to 5 times
     *
     * @param string $email
     * @param string $password
     * @param int $failedRetries should not be manually set but can be if you want less retries
     * @return User|null
     */
    public function createUser(string $email, string $password, int $failedRetries = 0): ?User
    {
        $query = $this->dbConn->prepare(
            "insert into users 
                   values (uuid_to_bin(?), ?, ?);"
        );
        $uuid = uuid();
        $email = strip_tags($email);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // binds 2 string parameters to the prepared SQL statement and
        // executes it the parameters was bound successfully
        if ($query->bind_param("sss", $uuid, $email, $passwordHash) && $query->execute()) {
            return new User($uuid, $email, $passwordHash);
        }

        // checks if the error from above was a duplicate for the table key `id`
        // the id is a generated uuid and have a very low chance to be duplicated but this
        // if it was a duplicate key error it tries up to 5 times
        if ($failedRetries < 5 && $this->checkDuplicateKey("id")) {
            return $this->createUser($email, $password, ++$failedRetries);
        }
        return null;
    }

    public function createUserProfile(User $user, string $username, string $avatar, string $timezone): ?UserProfile
    {
        $id = $user->getID();
        if (!$avatar) {
            $avatar = "media/assets/defaultAvatar.png";
        }
        $query = $this->dbConn->prepare(
            'insert into userProfiles 
                   values (uuid_to_bin(?), ?, ?, ?);'
        );
        $username = strip_tags($username);

        if ($query->bind_param("ssss", $id, $username, $avatar, $timezone) && $query->execute()) {
            return new UserProfile($id, $username, $avatar, $timezone);
        }
        return null;
    }

    public function createRoom(string $name, ?string $password): ?Room
    {
        $id = uuid();
        $name = strip_tags($name);
        if (!is_null($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $passwordHash = null;
        }

        $query = $this->dbConn->prepare(
            "insert into rooms
                   values (uuid_to_bin(?), ?, ?);"
        );
        if ($query->bind_param("sss", $id, $name, $passwordHash) && $query->execute()) {
            if (is_null($passwordHash)) {
                return new PublicRoom($id, $name);
            } else {
                return new PrivateRoom($id, $name, $passwordHash);
            }
        }
        return null;
    }

    public function createMembership(Room $room, User $user): bool
    {
        $userID = $user->getID();
        $roomID = $room->getID();
        $query = $this->dbConn->prepare(
            "insert into members
                   values (default, uuid_to_bin(?), uuid_to_bin(?));"
        );

        if ($query->bind_param("ss", $userID, $roomID) && $query->execute()) {
            return true;
        }
        return false;
    }

    public function updateUser(User $user, ?string $email = null, ?string $password = null): ?User
    {
        $id = $user->getID();
        if (!$email) {
            $email = $user->getEmail();
        }

        if ($password) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $passwordHash = $user->getPasswordHash();
        }

        $query = $this->dbConn->prepare(
            "update users 
                   set email = ?, passwordHash = ? 
                   where id = uuid_to_bin(?);"
        );

        if ($query->bind_param("sss", $email, $passwordHash, $id) && $query->execute()) {
            return new User($id, $email, $passwordHash);
        }
        return null;
    }

    public function updateUserProfile(UserProfile $userProfile, ?string $username = null, ?string $avatar = null, ?string $timezone = null): ?UserProfile
    {
        $id = $userProfile->getID();
        if (!$username) {
            $username = $userProfile->getUsername();
        }
        if (!$avatar) {
            $avatar = $userProfile->getAvatar();
        }
        if (!$timezone) {
            $timezone = $userProfile->getTimezone();
        }

        $query = $this->dbConn->prepare(
            "update userProfiles 
                   set username = ?, avatar = ?, timezone = ?
                   where userID = uuid_to_bin(?);"
        );

        if ($query->bind_param("ssss", $username, $avatar, $timezone, $id) && $query->execute()) {
            return new UserProfile($id, $username, $avatar, $timezone);
        }
        return null;
    }

    public function updateSession(User $user): bool
    {
        session_regenerate_id();
        $id = $user->getID();
        $sessionID = session_id();
        $query = $this->dbConn->prepare(
            "insert into sessions (userID, sessionID) 
                   values (uuid_to_bin(?), ?) 
                   on duplicate key update sessionID = ?;"
        );
        if ($query->bind_param("sss", $id, $sessionID, $sessionID) && $query->execute()) {
            echo "queried successfully" . "<br>";
            return true;
        }
        return false;
    }

    public function getUser(string $id): ?User
    {
        $query = $this->dbConn->prepare(
            "select bin_to_uuid(id) as id, email, passwordHash 
                   from users where id = uuid_to_bin(?);"
        );
        if ($query->bind_param("s", $id) && $query->execute()) {
            // if there is a result and the result have rows
            if (($result = $query->get_result()) && $result->num_rows) {
                return User::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    public function getUserFromEmail(string $email): ?User
    {
        $query = $this->dbConn->prepare(
            "select bin_to_uuid(id) as id, email, passwordHash 
                   from users 
                   where email = ?;"
        );
        if ($query->bind_param("s", $email) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return User::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    public function getUserProfile(User $user): ?UserProfile
    {
        $id = $user->getID();
        $query = $this->dbConn->prepare(
            "select bin_to_uuid(userID) as userID, username, avatar, timezone
                   from userProfiles 
                   where userID = uuid_to_bin(?);"
        );

        if ($query->bind_param("s", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return UserProfile::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    public function getUserRooms(User $user): array // array of Room NOT necessarily private
    {
        $id = $user->getID();
        $query = $this->dbConn->prepare(
            "select rooms.id as id, name, passwordHash
                   from ((select roomID from members where userID = uuid_to_bin(?)) as m
                            join rooms on m.roomID = rooms.id);"
        );
        $rooms = [];
        if ($query->bind_param("s", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                while ($roomData = $result->fetch_assoc()) {
                    array_push($rooms, PrivateRoom::fromAssoc($roomData));
                }
            }
        }
        return $rooms;
    }

    public function getRoomUserProfileList(Room $room): array
    {
        $roomID = $room->getID();

        $query = $this->dbConn->prepare(
            "select bin_to_uuid(userProfiles.userID) as userID, username, avatar, timezone
                   from ((select userID
                       from members where roomID = uuid_to_bin(?)) as m
                        join userProfiles on m.userID = userProfiles.userID);"
        );

        $users = [];
        if ($query->bind_param("s", $roomID) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                while ($userProfileData = $result->fetch_assoc()) {
                    array_push($users, UserProfile::fromAssoc($userProfileData));
                }
            }
        }
        return $users;
    }

    public function getRoom(string $id): ?Room
    {
        $query = $this->dbConn->prepare(
            "select bin_to_uuid(id) as id, name, passwordHash 
                   from rooms 
                   where id = uuid_to_bin(?);"
        );
        if ($query->bind_param("s", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return PrivateRoom::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    public function getMessages(Room $room, string $before = null): array
    {
        $roomID = $room->getID();
        if ($before) {
            $query = $this->dbConn->prepare(
                "select bin_to_uuid(mt.id) as id, bin_to_uuid(users.id) as userID, 
                         bin_to_uuid(rooms.id) as roomID, email, users.passwordHash as passwordHash, 
                         username, avatar, timezone, name as roomName, unix_timestamp(postDate) as postDate, content 
                       from (select * from messages 
                       where roomID = uuid_to_bin(?) 
                         and postDate < (select postDate from messages where id=uuid_to_bin(?))
                       order by postDate desc limit ?) as mt 
                         join users on mt.userID = users.id 
                         join userProfiles on users.id = userProfiles.userID 
                         join rooms on mt.roomID = rooms.id 
                       ;"
            );
            if (!$query->bind_param("ssi", $roomID, $before, $this->pageLimit) || !$query->execute()) {
                return [];
            }
        } else {
            $query = $this->dbConn->prepare(
                "select bin_to_uuid(mt.id) as id, bin_to_uuid(users.id) as userID, 
                         bin_to_uuid(rooms.id) as roomID, email, users.passwordHash as passwordHash, 
                         username, avatar, timezone, name as roomName, unix_timestamp(postDate) as postDate, content 
                       from (select * from messages 
                       where roomID = uuid_to_bin(?) 
                       order by postDate desc limit ?) as mt 
                         join users on mt.userID = users.id 
                         join userProfiles on users.id = userProfiles.userID 
                         join rooms on mt.roomID = rooms.id 
                       order by postDate;"
            );
            if (!$query->bind_param("si", $roomID, $this->pageLimit) || !$query->execute()) {
                return [];
            }
        }
        if (($result = $query->get_result()) && $result->num_rows) {
            $messages = [];
            while ($messageData = $result->fetch_assoc()) {
                array_push($messages, Message::fromAssoc($messageData));
            }
            return $messages;
        }
        return [];
    }

    public function isMember(User $user, Room $room): bool
    {
        $userID = $user->getID();
        $roomID = $room->getID();

        $query = $this->dbConn->prepare(
            "select exists (
                     select 1 
                     from members
                     where userID = uuid_to_bin(?) and roomID = uuid_to_bin(?)
                   ) as member;"
        );
        if ($query->bind_param("ss", $userID, $roomID) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                if ($result->fetch_assoc()["member"]) {
                    return true;
                }
            }
        }
        return false;
    }

    public function deleteSession(User $user): bool
    {
        $id = $user->getID();
        $query = $this->dbConn->prepare(
            "delete from sessions 
                   where userID = uuid_to_bin(?);"
        );
        if ($query->bind_param("s", $id) && $query->execute()) {
            return true;
        }
        return false;
    }

    public function deleteRoom(Room $room): bool
    {
        $id = $room->getID();
        $query = $this->dbConn->prepare(
            "delete from rooms
                   where id = uuid_to_bin(?);"
        );
        if ($query->bind_param("s", $id) && $query->execute()) {
            return true;
        }
        return false;
    }

    public function deleteMembership(User $user, Room $room): bool
    {
        $userID = $user->getID();
        $roomID = $room->getID();

        $query = $this->dbConn->prepare(
            "delete from members where userID = uuid_to_bin(?) and roomID = uuid_to_bin(?); "
        );
        if ($query->bind_param("ss", $userID, $roomID) && $query->execute()) {
            return true;
        }
        return false;
    }

    public function __destruct()
    {
        $this->dbConn->close();
    }
}
