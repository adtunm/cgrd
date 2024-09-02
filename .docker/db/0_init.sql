create table if not exists project.news
(
    id      int auto_increment
        primary key,
    title   varchar(255) not null,
    content text         null
);

create table if not exists project.users
(
    id       int auto_increment
        primary key,
    login    varchar(255) not null,
    password varchar(255) not null
);

insert into project.users (login, password)
VALUES ('admin', '$2a$12$voAqQK5ih2Tgc9oPKvxVqu/IN3EFKRv/mDmmW4HKhSM/kxXQuyH7e');
