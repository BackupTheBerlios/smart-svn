#
# Table structure for table `groups`
#

CREATE TABLE groups (
  gid int(11) NOT NULL default '0',
  name varchar(50) default NULL,
  UNIQUE KEY gid (gid)
);

#
# Dumping data for table `groups`
#

INSERT INTO groups VALUES (1, 'sysadmins');

# --------------------------------------------------------

#
# Table structure for table `permissions`
#

CREATE TABLE smart_user_permission (
  id int(11) NOT NULL default '0',
  id_type enum('group','user') NOT NULL default 'group',
  application varchar(100) default NULL,
  part varchar(50) default NULL,
  detail varchar(100) default NULL,
  perms set('read','delete','modify','add') default NULL
);

#
# Dumping data for table `permissions`
#

INSERT INTO smart_user_permissions VALUES (1, 'group', 'user', '', '', 'read,modify');
INSERT INTO smart_user_permissions VALUES (1, 'user', 'user', '', '', 'read,delete,modify,add');

# --------------------------------------------------------

#
# Table structure for table `usergroups`
#

CREATE TABLE smart_user_group_rel (
  id int(10) NOT NULL auto_increment,
  uid int(11) NOT NULL default '0',
  gid int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

#
# Dumping data for table `usergroups`
#

INSERT INTO user_group VALUES (1, 1, 1);


# --------------------------------------------------------

#
# Table structure for table `users`
#

CREATE TABLE users (
  uid int(10) unsigned NOT NULL default '0',
  username varchar(20) NOT NULL default '',
  passwd varchar(20) NOT NULL default '',
  forename varchar(100) default NULL,
  lastname varchar(100) default NULL,
  email varchar(200) default NULL,
  nologin tinyint(1) NOT NULL default '0',
  first_login datetime default NULL,
  last_login datetime default NULL,
  count_logins int(10) unsigned NOT NULL default '0',
  count_pages int(10) unsigned NOT NULL default '0',
  time_online int(11) NOT NULL default '0',
  PRIMARY KEY  (uid),
  KEY username (username)
);

#
# Dumping data for table `users`
#

INSERT INTO users VALUES (1, 'admin', 'admin', 'Charles', 'Buckowsky', 'charles@exit0.net', 0, '2002-12-27 10:33:55', '2004-06-03 12:05:57', 32, 76, 72809);
