CREATE SCHEMA IF NOT EXISTS `ContactBook` DEFAULT CHARACTER SET utf8 ;
USE `ContactBook`;

DROP TABLE IF EXISTS `ContactBook`.`Agenda` ;
CREATE TABLE `ContactBook`.`Agenda`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` NVARCHAR(80) NOT NULL,
    `birthday` DATE NOT NULL,
    `phone` VARCHAR(80) NOT NULL,
    PRIMARY KEY (`id`),
	UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE
)
ENGINE = InnoDB;