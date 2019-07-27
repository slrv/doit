drop table if exists task;

create table task
(
    id int auto_increment primary key,
    id_user int not null,
    title varchar(256) not null,
    priority tinyint(1) not null,
    due_date datetime not null,
    created_at datetime not null,
    done_date datetime null,
    constraint task_user_id_fk
        foreign key (id_user) references user (id)
)
    comment 'User`s tasks';

create unique index task_id_uindex
    on task (id);
