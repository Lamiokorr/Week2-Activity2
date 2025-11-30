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

-- sample data
INSERT INTO `customer`
(`customer_id`, `customer_name`, `customer_email`, `customer_pass`,
`customer_country`, `customer_city`, `customer_contact`,
`customer_image`, `user_role`)
VALUES
(1, 'Peggy', 'test3@gmail.com', '$2y$10$jcgx3Ev...', 'USA', 'Houston', '2938048496', NULL, 1),
(9, 'Test One', 'test@gmail.com', '$2y$10$YWr6/...', 'Canada', 'Ontario', '0394029394', NULL, 1),
(10, 'Test Two', 'test2@gmail.com', '$2y$10$vJ72...', 'Ghana', 'Tema', '0495038495', NULL, 2),
(12, 'Elikem GaleZoyiku', 'egalezoyiku@gmail.com', '$2y$10$u92g...', 'Ghana', 'Aburi', '+233507586382', NULL, 1);

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
CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `invoice_no` int NOT NULL,
  `order_date` date NOT NULL,
  `order_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- TABLE: orderdetails
-- --------------------------------------------------------
CREATE TABLE `orderdetails` (
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- TABLE: payment
-- --------------------------------------------------------
CREATE TABLE `payment` (
  `pay_id` int NOT NULL,
  `amt` double NOT NULL,
  `customer_id` int NOT NULL,
  `order_id` int NOT NULL,
  `currency` text NOT NULL,
  `payment_date` date NOT NULL,

  -- from modifications.sql
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'Payment method: paystack, cash, etc.',
  `transaction_ref` varchar(100) DEFAULT NULL COMMENT 'Paystack transaction reference',
  `authorization_code` varchar(100) DEFAULT NULL COMMENT 'Authorization code',
  `payment_channel` varchar(50) DEFAULT NULL COMMENT 'Payment channel: card, momo, etc.'
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
