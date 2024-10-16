CREATE TABLE IF NOT EXISTS `products` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`name` VARCHAR(255) NOT NULL,
`price` DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS `orders` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `order_products` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`order_id` INT NOT NULL,
`product_id` INT NOT NULL,
`quantity` INT NOT NULL,
FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`),
FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
);

-- Вставка двух товаров
INSERT INTO `products` (`name`, `price`) VALUES
('товар1', 100.00),
('товар2', 200.00);

-- Вставка заказа
INSERT INTO `orders` (created_at) VALUES (CURRENT_TIMESTAMP);

INSERT INTO `order_products` (`order_id`, `product_id`, `quantity`) VALUES
(1, 1, 3),
(1, 2, 2);