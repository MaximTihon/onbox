CREATE TABLE `content` (
  `id_content` INT(2)  NOT NULL  AUTO_INCREMENT,
  `name_content` VARCHAR(100),
  `content` TEXT,
  PRIMARY KEY(`id_content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE account ADD `name` VARCHAR(255);

CREATE TABLE `category`(
  `id_category` int(10) NOT NULL AUTO_INCREMENT,
  `name_category` VARCHAR(100) NOT NULL,
  `description_category` TEXT NOT NULL,
  `active` INT(1) NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `brand`(
  `id_brand` int(10) NOT NULL AUTO_INCREMENT,
  `id_category` int(10) NOT NULL,
  `name_brand` VARCHAR(100) NOT NULL,
  `description_brand` TEXT NOT NULL,
  `img_brand` VARCHAR(255),
  `active` INT(1) NOT NULL,
  PRIMARY KEY (`id_brand`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `datm`(
  `id_datm` INT(10) NOT NULL AUTO_INCREMENT,
  `logo` VARCHAR(50) NOT NULL,
  `pass` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id_datm`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `img_product`(
  `id_product` INT(10) NOT NULL,
  `main_pic` TEXT,
  `min_pic_1` VARCHAR(255) NULL ,
  `min_pic_2` VARCHAR(255) NULL ,
  `min_pic_3` VARCHAR(255) NULL ,
  `min_pic_4` VARCHAR(255) NULL ,
  PRIMARY KEY (`id_product`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `static_buy`(
  `id_product` INT(10) NOT NULL,
  `date` DATETIME,
  PRIMARY KEY (`id_product`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `static_view`(
  `id_product` INT(10) NOT NULL,
  `date` DATETIME,
  PRIMARY KEY (`id_product`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `order` ADD status_order INT(1) DEFAULT 1;

CREATE TABLE `order`(
  `id_order` INT(15)  NOT NULL AUTO_INCREMENT ,
  `data_order` TEXT,
  `date` TEXT,
  PRIMARY KEY(`id_order`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `account`(
  `id_account` INT(10)  NOT NULL AUTO_INCREMENT,
  `mail` VARCHAR(50) NOT NULL,
  PRIMARY KEY(`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `price`(
  `id_product` int(10) NOT NULL,
  `price_product` int(10) NOT NULL,
  `sale_product`  int(10) NULL,
  PRIMARY KEY  (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `product`(
  `id_product` int(10) NOT NULL AUTO_INCREMENT,
  `id_category` int(10) NOT NULL,
  `id_brand` int(10) NOT NULL,
  `name_product` VARCHAR(100) NOT NULL ,
  `description_product` TEXT NOT NULL,
  `mini_desc` TEXT NOT NULL,
  `status` INT(1) NOT NULL,
  `article` VARCHAR(50) NOT NULL,
  `data_product` TEXT NULL,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

