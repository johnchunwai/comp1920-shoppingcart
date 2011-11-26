/*
  Assignment 3 - DATABASE shoppingcart
*/

CREATE DATABASE IF NOT EXISTS shoppingcart;

USE shoppingcart;

DROP TABLE IF EXISTS tbl_order_item;
DROP TABLE IF EXISTS tbl_order;
DROP TABLE IF EXISTS tbl_inventory;
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
	prod_name varchar(20) not null,
	prod_desc varchar(200) not null,
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
	ord_date date not null ,
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
