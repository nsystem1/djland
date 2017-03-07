CREATE TABLE `fundrive_donors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_amount` varchar(10) DEFAULT NULL,
  `swag` varchar(1) DEFAULT NULL,
  `tax_receipt` varchar(1) DEFAULT NULL,
  `show_inspired` tinytext,
  `prize` varchar(45) DEFAULT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `address` varchar(90) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `province` varchar(4) DEFAULT NULL,
  `postalcode` varchar(6) DEFAULT NULL,
  `country` varchar(60) DEFAULT NULL,
  `phonenumber` varchar(12) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `payment_method` varchar(45) DEFAULT NULL,
  `mail_yes` varchar(1) DEFAULT NULL,
  `postage_paid` varchar(30) DEFAULT NULL,
  `recv_updates_citr` varchar(1) DEFAULT NULL,
  `recv_updates_alumni` varchar(1) DEFAULT NULL,
  `donor_recognition_name` varchar(45) DEFAULT NULL,
  `LP_yes` varchar(1) DEFAULT NULL,
  `notes` text,
  `paid` varchar(1) DEFAULT NULL,
  `prize_picked_up` varchar(1) DEFAULT NULL,
  `UPDATED_AT` timestamp NULL DEFAULT NULL,
  `CREATED_AT` timestamp NULL DEFAULT NULL,
  `LP_amount` varchar(5) NOT NULL,
  `status` varchar(45) DEFAULT 'unsaved',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8;
