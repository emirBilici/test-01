# Create the project database
CREATE SCHEMA dictionary_project COLLATE utf8_general_ci;
USE `dictionary_project`;

# Create Users Table
CREATE TABLE `dictionary_project`.`users` ( `user_id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL , `password` VARCHAR(255) NOT NULL , `user_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`user_id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;
ALTER TABLE `users` ADD `user_about` TEXT NOT NULL AFTER `password`;
ALTER TABLE `users` ADD `notifications` INT(1) NOT NULL DEFAULT '1' AFTER `user_about`;

# Create Posts Table
create table posts
(
    post_id            int,
    post_title         varchar(75)                            not null,
    post_featured_code varchar(255) default null              null,
    post_description   text                                   not null,
    post_tag           int                                    not null,
    post_user_id       int                                    not null,
    created_post       datetime     default CURRENT_TIMESTAMP not null,
    constraint posts_users_user_id_fk
        foreign key (post_user_id) references users (user_id)
);

create unique index posts_post_id_uindex
    on posts (post_id);

alter table posts
    add constraint posts_pk
        primary key (post_id);

alter table posts
    modify post_id int auto_increment;

ALTER TABLE `posts` ADD `puID` VARCHAR(8) NOT NULL AFTER `post_user_id`;
ALTER TABLE `posts` DROP FOREIGN KEY `posts_users_user_id_fk`; ALTER TABLE `posts` ADD CONSTRAINT `posts_users_user_id_fk` FOREIGN KEY (`post_user_id`) REFERENCES `users`(`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `posts` ADD `sub_entry` INT NOT NULL DEFAULT '0' AFTER `puID`;

# Create tags table
create table tags
(
    tag_id      int,
    slug        varchar(255)                       null,
    name        varchar(255)                       null,
    created_tag DATETIME default current_timestamp not null
);

create unique index tags_name_uindex
    on tags (name);

create unique index tags_slug_uindex
    on tags (slug);

create unique index tags_tag_id_uindex
    on tags (tag_id);

alter table tags
    add constraint tags_pk
        primary key (tag_id);

alter table tags
    modify tag_id int auto_increment;

# Create vote table
CREATE TABLE `dictionary_project`.`vote` ( `vote_id` INT NOT NULL AUTO_INCREMENT , `vote_entry` VARCHAR(8) NOT NULL , `status` INT(1) NOT NULL DEFAULT '0' , `created_vote` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`vote_id`)) ENGINE = InnoDB;
ALTER TABLE `vote` ADD `user_vote` INT NOT NULL AFTER `vote_entry`;

# Create report table
create table reports
(
    report_id         int,
    reporter_id       int                                null,
    reported_post_key varchar(8)                         null,
    report_message    text                               null,
    created_report    datetime default CURRENT_TIMESTAMP null
);

create unique index reports_report_id_uindex
    on reports (report_id);

alter table reports
    add constraint reports_pk
        primary key (report_id);

alter table reports
    modify report_id int auto_increment;

# Create notifications table
create table notifications
(
    notification_id      int,
    notify_text          text                               not null,
    notify_user          int                                null,
    created_notification datetime default CURRENT_TIMESTAMP null
);

create unique index notifications_notification_id_uindex
    on notifications (notification_id);

alter table notifications
    add constraint notifications_pk
        primary key (notification_id);

alter table notifications
    modify notification_id int auto_increment;

# Create admin settings table
CREATE TABLE `dictionary_project`.`admin_settings` ( `email` VARCHAR(255) NOT NULL , `password` VARCHAR(255) NOT NULL , `homepage_default_tag` INT(255) NULL DEFAULT NULL , `settings_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB;
INSERT INTO `admin_settings` (`email`, `password`, `homepage_default_tag`, `settings_updated`) VALUES ('admin@admin.com', '47c93414eb44e5e19fb0dfd9269edc43', 1, CURRENT_TIMESTAMP);