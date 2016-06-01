
CREATE TABLE users (
	id int(11) not null auto_increment primary key,
	username varchar(255) unique,
	password varchar(255),
	token varchar(255),
	created_at datetime,
	updated_at datetime
);

CREATE TABLE todos (
	`id` int(11) not null auto_increment primary key,
	`title` varchar(255),
	`body` text,
       
	`created_at` datetime
);

