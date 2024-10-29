-- Disable foreign key checks to avoid issues with table dependencies
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables
DROP TABLE IF EXISTS feedback;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    security_question VARCHAR(255) NOT NULL,
    security_answer VARCHAR(255) NOT NULL,
    wallet_balance DECIMAL(10, 2) DEFAULT 5000.00,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    quantity INT NOT NULL
);

-- Create the feedback table if it doesn't already exist
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    username VARCHAR(255),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert initial data into users table
INSERT INTO users (username, email, mobile, password, security_question, security_answer, role) 
VALUES 
('admin', 'admin@infosec.com', '9234567890', 'admin@123', 'What is your favorite color?', 'blue', 'admin'),
('guest', 'guest@example.com', '9876543201', 'qwerty', 'Who is your favorite in sports?', 'virat', 'user'),
('john', 'john@gmail.com', '9534567890', 'john@123', 'Who is your favorite in sports?', 'dhoni', 'user'),
('tom', 'tom@gmail.com', '9087654321', 'tom@456', 'Which year were you born?', '1987', 'user'),
('infosecfolks', 'infosecfolks@infosec.com', '7090654321', 'infosecfolks@123', 'Which year were you born?', '1992', 'user'),    
('shyam', 'shyam@gmail.com', '9234567890', 'shyam9234', 'What is your favorite color?', 'green', 'user'),
('nick', 'nick@gmail.com', '7987654621', 'nick4621', 'What is your favorite color?', 'yellow', 'user');

-- Insert initial data into products table
INSERT INTO products (name, description, price, image, quantity) 
VALUES 
('TV', 'Smart TV 42 inch', 470.00, 'tv.jpg', 10),
('Hoodie', 'Comfortable hoodie', 25.00, 'hoodie.png', 20),
('iPhone', 'Latest iPhone model', 995.00, 'iphone.jpeg', 5);

-- Insert initial data into feedback table
INSERT INTO feedback (product_id, comment, username) 
VALUES 
(1, 'Great TV!', 'john'),
(2, 'Love this hoodie!', 'tom'),
(3, 'Best phone ever!', 'nick');

-- Enable foreign key checks again
SET FOREIGN_KEY_CHECKS = 1;
