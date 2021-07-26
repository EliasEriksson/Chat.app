<?php

include_once __DIR__ . "/../uuid.php";
include_once __DIR__ . "/models/user.php";
include_once __DIR__ . "/models/userProfile.php";
include_once __DIR__ . "/models/session.php";
include_once __DIR__ . "/models/publicRoom.php";
include_once __DIR__ . "/models/privateRoom.php";
include_once __DIR__ . "/models/message.php";

// Database manager
// login info:
// host: $_SERVER["DB_HOSTNAME"]
// username: $_SERVER["DB_USER"]
// password: $_SERVER["DB_PASS"]
// database name: $_SERVER["DB_NAME"]


class DbManager
{
    private mysqli $dbConn;

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

    public function createUserProfile(User $user, string $username, string $avatar): ?UserProfile
    {
        $id = $user->getID();
        if (!$avatar) {
            $avatar = "media/assets/defaultAvatar.png";
        }
        $query = $this->dbConn->prepare(
            'insert into userProfiles 
                   values (uuid_to_bin(?), ?, ?);'
        );
        $username = strip_tags($username);

        if ($query->bind_param("sss", $id, $username, $avatar) && $query->execute()) {
            return new UserProfile($id, $username, $avatar);
        }
        return null;
    }

    public function createRoom(string $name, ?string $password): PrivateRoom|PublicRoom|null
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

    public function createMembership(PrivateRoom|PublicRoom $room, User $user): bool
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

    public function updateUserProfile(UserProfile $userProfile, ?string $username = null, ?string $avatar = null): ?UserProfile
    {
        $id = $userProfile->getID();
        if (!$username) {
            $username = $userProfile->getUsername();
        }
        if (!$avatar) {
            $avatar = $userProfile->getAvatar();
        }
        $query = $this->dbConn->prepare(
            "update userProfiles 
                   set username = ?, avatar = ? 
                   where userID = uuid_to_bin(?);"
        );

        if ($query->bind_param("sss", $username, $avatar, $id) && $query->execute()) {
            return new UserProfile($id, $username, $avatar);
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
                   values (uuid_to_bin(?), uuid_to_bin(?)) 
                   on duplicate key update sessionID = uuid_to_bin(?);"
        );
        if ($query->bind_param("sss", $id, $sessionID, $sessionID) && $query->execute()) {
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
            "select bin_to_uuid(userID) as userID, username, avatar 
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

    public function getRoom(string $id): PrivateRoom|PublicRoom|null
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

    public function isMember(User $user, PrivateRoom|PublicRoom $room): bool
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

    public function deleteRoom(PrivateRoom|PublicRoom $room): bool
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

    public function __destruct()
    {
        $this->dbConn->close();
    }
}
