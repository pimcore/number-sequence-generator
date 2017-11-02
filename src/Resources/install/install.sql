CREATE TABLE `bundle_number_sequence_generator_register` (
  `register` VARCHAR(50) NOT NULL,
  `counter` BIGINT(12) NULL DEFAULT 0,
  PRIMARY KEY (`register`))
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB;
