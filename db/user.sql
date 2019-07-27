drop table if exists user;

create table user
(
    id int auto_increment primary key,
    email varchar(200) not null,
    password varchar(80) not null,
    token varchar(60) not null,
    created datetime not null
)
    comment 'Users table';

create unique index user_email_uindex
    on user (email);

create unique index user_id_uindex
    on user (id);

create unique index user_token_uindex
    on user (token);
