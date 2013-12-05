CREATE DATABASE IF NOT EXISTS `db-web` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dbweb`;

CREATE TABLE IF NOT EXISTS `choice` (
  `c_number` int(255) NOT NULL,
  `c_text` mediumtext NOT NULL,
  `correct` tinyint(1) NOT NULL,
  KEY `choice_ibfk_1` (`c_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `question` (
  `q_number` int(255) NOT NULL,
  `q_text` varchar(255) NOT NULL,
  PRIMARY KEY (`q_number`),
  UNIQUE KEY `q_number` (`q_number`,`q_text`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `choice`
  ADD CONSTRAINT `choice_ibfk_1` FOREIGN KEY (`c_number`) REFERENCES `question` (`q_number`) ON DELETE CASCADE ON UPDATE CASCADE;
