

CREATE TABLE `wio_forms_structs` (
    `form_struct_id` VARCHAR(128) NOT NULL ,
    `name` VARCHAR(128) NOT NULL ,
    `version` VARCHAR(128) NOT NULL ,
    `used` TINYINT(4) NOT NULL ,
    `data_struct` TEXT NOT NULL ,
    PRIMARY KEY (`form_struct_id`)
) ENGINE = InnoDB;

CREATE TABLE `wio_forms_entries` (
    `id` INT(11) NOT NULL AUTO_INCREMENT ,
    `form_struct_id` VARCHAR(128) NOT NULL ,
    `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `previous_version` INT(11) NOT NULL ,
    `is_current_version` INT(3) NOT NULL ,
    `entry_data` TEXT NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
