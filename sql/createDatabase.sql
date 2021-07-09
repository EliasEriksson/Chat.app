drop database if exists chatapp;
create database chatapp;
use chatapp;

create table users
(
    id           char(36) unique default uuid(),
    email        varchar(255) unique ,
    passwordHash varchar(255),

    constraint usersEmail check ( email regexp '^[^@]+@[^.]+\..+' and length(email) >= 5),
    constraint usersPasswordHash check ( length(passwordHash) > 0 ),
    constraint usersPK primary key (id)
);

create table userProfiles (
    userID char(36) unique not null,
    username varchar(255),
    avatar varchar(511),

    constraint userProfilesAvatar check ( '^media/users/' ),
    constraint userProfilesPK primary key ( userID )
);

alter table userProfiles
    add constraint userProfilesUserFK
        foreign key ( userID )
            references users ( id );
