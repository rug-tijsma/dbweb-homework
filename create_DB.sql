CREATE DATABASE IF NOT EXISTS `dbweb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dbweb`;

CREATE TABLE IF NOT EXISTS question (
  q_number int(255) NOT NULL,
  q_text varchar(255) NOT NULL,
  PRIMARY KEY (q_number),
);

CREATE TABLE IF NOT EXISTS choice (
  q_number int(255) NOT NULL,
  c_number int(255) NOT NULL,
  c_text mediumtext NOT NULL,
  correct tinyint(1) NOT NULL,
  PRIMARY KEY (q_number, c_number),
  FOREIGN KEY (q_number) references question(q_number)
);
 
