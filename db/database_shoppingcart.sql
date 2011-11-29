/*
  Assignment 3 - DATABASE shoppingcart
*/

CREATE DATABASE IF NOT EXISTS shoppingcart;

USE shoppingcart;

DROP TABLE IF EXISTS tbl_order_item;
DROP TABLE IF EXISTS tbl_order;
DROP TABLE IF EXISTS tbl_inventory;
DROP TABLE IF EXISTS tbl_product;
DROP TABLE IF EXISTS tbl_customer;


CREATE TABLE IF NOT EXISTS tbl_customer(
	cus_id int not null primary key auto_increment,
	cus_name varchar(50) not null,
	cus_loginname varchar(20) not null unique,
	cus_password varchar(30) not null ,
	cus_email varchar(200) not null
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tbl_product(
	prod_id int not null primary key auto_increment,
	prod_name varchar(100) not null,
	prod_desc varchar(1000) not null,
	prod_image varchar(500) not null 
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tbl_inventory(
	cus_id int not null,
	prod_id int not null,
	qty int not null,
    primary key (cus_id, prod_id),
    CONSTRAINT fk_tbl_customer_tbl_customer_cus_id FOREIGN KEY (cus_id) REFERENCES tbl_customer (cus_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_tbl_inventory_tbl_product_prod_id FOREIGN KEY (prod_id) REFERENCES tbl_product (prod_id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tbl_order(
	ord_id int not null primary key auto_increment,
	cus_id int not null,
	ord_time int not null ,
    CONSTRAINT fk_tbl_order_tbl_customer_cus_id FOREIGN KEY (cus_id) REFERENCES tbl_customer (cus_id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tbl_order_item(
	ord_id int not null,
	prod_id int not null,
	ord_qty int not null,
    primary key (ord_id, prod_id), 
    CONSTRAINT fk_tbl_order_item_tbl_order_ord_id FOREIGN KEY (ord_id) REFERENCES tbl_order (ord_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_tbl_order_item_tbl_product_prod_id FOREIGN KEY (prod_id) REFERENCES tbl_product (prod_id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

/*
	Test Data
*/
INSERT INTO tbl_customer (cus_name, cus_loginname, cus_password, cus_email) VALUES
	('Chun Wai Chan', 'cchan', 'comp1920', 'cchan331@my.bcit.ca'),
	('Bill Yu', 'byu', 'comp1920', 'byu39@my.bcit.ca'),
	('Patrick Kowalski', 'pkowalski', 'comp1920', 'pkowalski@my.bcit.ca'),
	('Sahra Dilmaghanyan', 'sdilmaghanyan', 'comp1920', 'sdilmaghanyan@my.bcit.ca'),
	('Sami Jashromi', 'sjashromi', 'comp1920', 'samiii1254@gmail.com');

INSERT INTO tbl_product (prod_name, prod_desc, prod_image) VALUES
	('Radioactive Blue Cheese', 'Accidental by-product of an experiemental drug.', './img/radioactive_blue_inv.gif'),
	('Dry Cow Dung', 'Quite a lot of skill needed to be applied while making these piles.', './img/dry_cow_dung.jpg'),
	('Monopoly Bill', 'A monopoly bill where you can write any amount onto it.', './img/bill.jpg');