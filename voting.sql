CREATE TABLE `pkn_votes` (
  `pkn_vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `primary` varchar(255) DEFAULT NULL,
  `secondary` varchar(255) DEFAULT NULL,
  `tertiary` varchar(255) DEFAULT NULL,
  `ignored` char(1) DEFAULT NULL,
  PRIMARY KEY (`pkn_vote_id`)
