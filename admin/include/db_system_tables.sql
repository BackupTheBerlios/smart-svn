create table info ( 
name varchar(50) not null, 
version varchar(50) not null, 
status varchar(10) not null );

CREATE TABLE modules (
name VARCHAR(50) NOT NULL PRIMARY KEY, 
version VARCHAR(20) NOT NULL, 
info TEXT NOT NULL );

CREATE TABLE options ( 
db VARCHAR(30) NOT NULL DEFAULT 'smart', 
css_folder VARCHAR(30) NOT NULL DEFAULT 'default' );