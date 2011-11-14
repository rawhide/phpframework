create table users(
       id int(11) auto_increment,
       authority_group_id int(11),
       login varchar(255) not null,
       password varchar(255) not null,
       mail varchar(255),
       name varchar(255),
       typecode varchar(255) default 'user',
       created_at datetime,
       updated_at datetime default null,
       deleted_at datetime default null,
       primary key(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create index idx_users_login on users(login);

