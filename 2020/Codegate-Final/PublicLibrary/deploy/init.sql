CREATE USER 'user'@'localhost' identified by 'v3ryh4rdp455w0rd';

CREATE DATABASE pl;

use pl;
CREATE TABLE guestbook (ip char(64), name varchar(100), age int, purpose varchar(1000));
CREATE TABLE `flag` (`flag` varchar(1000));
INSERT INTO guestbook (ip, name, age, purpose) VALUES ('127.0.0.1', 'admin', 17, 'CODEGATE2020{THISISNOTAFLAGFINDADEEPERSECRET}');
INSERT INTO `flag` (`flag`) VALUES('CODEGATE2020{d0nt_r34d_s3rv3r_s3tt1ng_lol}');

GRANT FILE ON *.* TO 'user'@'localhost';
GRANT ALL PRIVILEGES ON pl.* TO 'user'@'localhost';
FLUSH privileges;

