-- MERGED SQL
-- Final Database

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `ecommerce_2025A_naa_dove`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `ecommerce_2025A_naa_dove`;

-- --------------------------------------------------------
-- TABLE: customer
-- --------------------------------------------------------
CREATE TABLE `customer` (
  `customer_id` int NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(50) NOT NULL,
  `customer_pass` varchar(150) NOT NULL,
  `customer_country` varchar(30) NOT NULL,
  `customer_city` varchar(30) NOT NULL,
  `customer_contact` varchar(15) NOT NULL,
  `customer_image` varchar(100) DEFAULT NULL,
  `user_role` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------
-- TABLE: brands
-- --------------------------------------------------------
CREATE TABLE `brands` (
  `brand_id` int NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `created_by` int NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------
-- TABLE: categories
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `cat_id` int NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  `created_by` int NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------
-- TABLE: products
-- --------------------------------------------------------
CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_cat` int NOT NULL,
  `product_brand` int NOT NULL,
  `product_title` varchar(200) NOT NULL,
  `product_price` double NOT NULL,
  `product_desc` varchar(500) DEFAULT NULL,
  `product_image` varchar(100) DEFAULT NULL,
  `product_keywords` varchar(100) DEFAULT NULL,
  `created_by` int NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------
-- TABLE: cart
-- --------------------------------------------------------
CREATE TABLE `cart` (
  `p_id` int NOT NULL,
  `ip_add` varchar(50) NOT NULL,
  `c_id` int DEFAULT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- TABLE: orders
-- --------------------------------------------------------
CREATE TABLE orders (
    `order_id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `invoice_no` INT NOT NULL UNIQUE,
    `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `order_status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') 
        NOT NULL DEFAULT 'pending',

    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
        ON DELETE CASCADE
);

-- --------------------------------------------------------
-- TABLE: orderdetails
-- --------------------------------------------------------
CREATE TABLE `orderdetails` (
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `qty` INT NOT NULL CHECK (qty > 0),
    PRIMARY KEY `order_id`, `product_id`,
    FOREIGN KEY (order_id) REFERENCES orders (order_id)
        ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products (product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- TABLE: payment
-- --------------------------------------------------------
CREATE TABLE payment (
    `pay_id` INT AUTO_INCREMENT PRIMARY KEY,
    `amt` DOUBLE NOT NULL CHECK (amt >= 0),
    `customer_id` INT NOT NULL,
    `order_id` INT NOT NULL,
    `currency` VARCHAR(10) NOT NULL DEFAULT 'GHS',
    `payment_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    `payment_method` VARCHAR(50) DEFAULT NULL,
    `transaction_ref` VARCHAR(100) DEFAULT NULL UNIQUE,
    `authorization_code` VARCHAR(100) DEFAULT NULL,
    `payment_channel` VARCHAR(50) DEFAULT NULL,

    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
        ON DELETE CASCADE,

    FOREIGN KEY (order_id) REFERENCES orders(order_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- add indexes
ALTER TABLE payment ADD INDEX idx_transaction_ref (transaction_ref);
ALTER TABLE payment ADD INDEX idx_payment_method (payment_method);

-- --------------------------------------------------------
-- INDEXES & FOREIGN KEYS
-- --------------------------------------------------------

ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`),
  ADD KEY `created_by` (`created_by`);

ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `unique_category_per_user` (`cat_name`, `created_by`),
  ADD KEY `fk_categories_customer` (`created_by`);

ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_email` (`customer_email`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_cat` (`product_cat`),
  ADD KEY `product_brand` (`product_brand`),
  ADD KEY `created_by` (`created_by`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

ALTER TABLE `orderdetails`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `payment`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `order_id` (`order_id`);

ALTER TABLE `cart`
  ADD KEY `p_id` (`p_id`),
  ADD KEY `c_id` (`c_id`);

-- --------------------------------------------------------
-- FOREIGN KEYS
-- --------------------------------------------------------
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_ibfk_1` FOREIGN KEY (`created_by`)
  REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_customer` FOREIGN KEY (`created_by`)
  REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`product_cat`) REFERENCES `categories` (`cat_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`product_brand`) REFERENCES `brands` (`brand_id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`c_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

COMMIT;
