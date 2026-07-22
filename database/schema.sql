CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE currencies (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE customers (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  address TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE sales (
  id INT AUTO_INCREMENT,
  customer_id INT NOT NULL,
  currency_id INT NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (currency_id) REFERENCES currencies(id)
);

CREATE TABLE user_permissions (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  page_name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO currencies (name, description) VALUES
('USD', 'United States Dollar'),
('EUR', 'Euro'),
('GBP', 'British Pound');

INSERT INTO customers (name, email, phone, address) VALUES
('John Doe', 'john@example.com', '1234567890', '123 Main St'),
('Jane Doe', 'jane@example.com', '0987654321', '456 Elm St');

INSERT INTO sales (customer_id, currency_id, amount) VALUES
(1, 1, 100.00),
(2, 2, 200.00);

INSERT INTO user_permissions (user_id, page_name) VALUES
(1, 'الصفحة الرئيسية'),
(1, 'قائمة العملات'),
(1, 'قائمة المشتريين'),
(1, 'قائمة المبيعات'),
(1, 'تسجيل الدخول'),
(1, 'تسجيل جديد');