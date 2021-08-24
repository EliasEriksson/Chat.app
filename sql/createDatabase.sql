drop database if exists chatapp;
create database chatapp;
use chatapp;

DELIMITER //

CREATE FUNCTION uuid_to_bin(_uuid char(36))
    RETURNS BINARY(16)
    LANGUAGE SQL DETERMINISTIC
    CONTAINS SQL SQL SECURITY INVOKER
    RETURN
        UNHEX(CONCAT(
                SUBSTR(_uuid, 15, 4),
                SUBSTR(_uuid, 10, 4),
                SUBSTR(_uuid, 1, 8),
                SUBSTR(_uuid, 20, 4),
                SUBSTR(_uuid, 25)));
//
CREATE FUNCTION bin_to_uuid(_bin BINARY(16))
    RETURNS char(36)
    LANGUAGE SQL DETERMINISTIC
    CONTAINS SQL SQL SECURITY INVOKER
    RETURN
        LCASE(CONCAT_WS('-',
                        HEX(SUBSTR(_bin, 5, 4)),
                        HEX(SUBSTR(_bin, 3, 2)),
                        HEX(SUBSTR(_bin, 1, 2)),
                        HEX(SUBSTR(_bin, 9, 2)),
                        HEX(SUBSTR(_bin, 11))
            ));

//
DELIMITER ;


create table users
(
    id           binary(16) unique   not null,
    email        varchar(255) unique not null,
    passwordHash varchar(255)        not null,

    constraint usersEmail check ( email regexp '^[^@]+@[^.]+\..+' and length(email) >= 5),
    constraint usersPasswordHash check ( length(passwordHash) > 0 ),
    constraint primary key (id)
);

create table userProfiles
(
    userID   binary(16) unique not null,
    username varchar(255)      not null,
    avatar   varchar(100) default 'media/assets/defaultAvatar.png',
    timezone varchar(50)       not null,

    constraint userProfilesAvatar check ( avatar rlike '(^media/users/)|(^media/assets/defaultAvatar.png$)' ),
    constraint primary key (userID)
);

create table sessions
(
    userID    binary(16) unique not null,
    sessionID varchar(128)      not null,
    constraint sessionsSessionID check ( sessionID rlike '^[-,a-zA-Z0-9]{1,128}$'),
    constraint primary key (userID)
);

create table rooms
(
    id           binary(16) unique not null,
    name         varchar(255)      not null,
    passwordHash varchar(255),

    constraint primary key (id)
);

create table members
(
    id     int auto_increment not null,
    userID binary(16)         not null,
    roomID binary(16)         not null,

    constraint uniqueMembership unique key (userID, roomID),
    constraint primary key (id)
);

create table messages
(
    id       binary(16) unique not null,
    userID   binary(16)        not null,
    roomID   binary(16)        not null,
    postDate timestamp         not null,
    content  text              not null,

    constraint primary key (id)
);

create table oldMessages
(
    id       binary(16) unique not null,
    userID   binary(16) unique not null,
    roomID   binary(16) unique not null,
    postDate timestamp         not null,
    content  text              not null,

    constraint primary key (id)
);

alter table userProfiles
    add constraint userProfilesUserFK foreign key (userID) references users (id);

alter table sessions
    add constraint sessionsUserFK foreign key (userID) references users (id);

alter table members
    add constraint membersUserFK foreign key (userID) references users (id);

alter table members
    add constraint membersRoomFK foreign key (roomID) references rooms (id);

alter table messages
    add constraint messagesUserFK foreign key (userID) references users (id);

alter table messages
    add constraint messagesRoomFK foreign key (roomID) references rooms (id);

alter table oldMessages
    add constraint oldMessagesUserFK foreign key (userID) references users (id);

alter table oldMessages
    add constraint oldMessagesRoomFK foreign key (roomID) references rooms (id);
