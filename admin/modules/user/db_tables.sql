CREATE TABLE user_groups (
  gid  INTEGER NOT NULL PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  desc TEXT NOT NULL);

CREATE TABLE user_usersgroups (
  uid INTEGER NOT NULL,
  gid INTEGER NOT NULL);

CREATE TABLE user_users (
  uid      INTEGER NOT NULL PRIMARY KEY,
  login    VARCHAR(30) NOT NULL,
  passwd   CHAR(32) NOT NULL,
  forename VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  email    VARCHAR(300) NOT NULL,
  desc     TEXT NOT NULL);

CREATE TABLE user_permissions (
  id          INTEGER NOT NULL default '0',
  id_type     VARCHAR(6) NOT NULL default 'group',
  part        VARCHAR(50) default NULL,
  part_id     VARCHAR(100) default NULL,
  perm_read   TINYINT default NULL,
  perm_delete TINYINT default NULL,
  perm_modify TINYINT default NULL,
  perm_add    TINYINT default NULL);
