drop database if exists chatapp;
create database chatapp;
use chatapp;

create table users
(
    id           char(36) unique default uuid(),
    email        varchar(255) unique not null,
    passwordHash varchar(255) not null,

    constraint usersEmail check ( email regexp '^[^@]+@[^.]+\..+' and length(email) >= 5),
    constraint usersPasswordHash check ( length(passwordHash) > 0 ),
    constraint usersPK primary key (id)
);

create table userProfiles
(
    userID   char(36) unique not null,
    username varchar(255) not null,
    avatar   varchar(100) default 'media/assets/defaultAvatar.png',

    constraint userProfilesAvatar check ( avatar rlike '(^media/users/)|(^media/assets/defaultAvatar.png$)' ),
    constraint userProfilesPK primary key (userID)
);

create table sessions
(
    userID char(36) unique not null,
    sessionID varchar(128) not null,
    constraint sessionsSessionID check ( sessionID rlike '^[-,a-zA-Z0-9]{1,128}$'),
    constraint sessionsPK primary key (userID)
);

alter table userProfiles
    add constraint userProfilesUserFK
        foreign key (userID)
            references users (id);

alter table sessions
    add constraint sessionsUserFK
        foreign key (userID)
            references users (id);
