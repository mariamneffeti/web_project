CREATE DATABASE IF NOT EXISTS web_project;
USE web_project;

-- Users & Authentication


CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('normal', 'employee', 'company') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE companies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    industry VARCHAR(100),
    address TEXT,
    phone VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    company_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    position VARCHAR(100),
    department VARCHAR(100),
    hire_date DATE,
    salary DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Clients Management

CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    client_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    client_type ENUM('B2B', 'B2C', 'B2G') DEFAULT 'B2C',
    status ENUM('Active', 'Inactive', 'Prospect') DEFAULT 'Active',
    total_spent DECIMAL(12, 2) DEFAULT 0.00,
    last_purchase_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    INDEX idx_company_client (company_id, client_name)
);

-- Sales Management

CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    sku VARCHAR(100),
    category VARCHAR(100),
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

CREATE TABLE sales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    transaction_id VARCHAR(50) UNIQUE NOT NULL,
    company_id INT NOT NULL,
    employee_id INT,
    client_id INT NOT NULL,
    sale_date DATE NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    discount DECIMAL(12, 2) DEFAULT 0.00,
    tax DECIMAL(12, 2) DEFAULT 0.00,
    total_amount DECIMAL(12, 2) NOT NULL,
    payment_method ENUM('Cash', 'Credit Card', 'Bank Transfer', 'Mobile Payment') NOT NULL,
    payment_status ENUM('Paid', 'Pending', 'Overdue') DEFAULT 'Paid',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_transaction (transaction_id),
    INDEX idx_company_date (company_id, sale_date)
);

CREATE TABLE sale_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sale_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    discount_percent DECIMAL(5, 2) DEFAULT 0.00,
    total_price DECIMAL(12, 2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Invoices

CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    sale_id INT UNIQUE NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE,
    status ENUM('Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled') DEFAULT 'Draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
);

-- Sample Data


-- Insert sample company user
INSERT INTO users (email, password, role) VALUES 
('company@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'company'); -- password: password

-- Insert company
INSERT INTO companies (user_id, company_name, industry, phone) VALUES 
(1, 'TechCorp Solutions', 'Technology', '+216-70-123-456');

-- Insert employee user
INSERT INTO users (email, password, role) VALUES 
('employee@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee');

-- Insert employee
INSERT INTO employees (user_id, company_id, first_name, last_name, position, department, hire_date, salary) VALUES 
(2, 1, 'Sarah', 'Connor', 'Sales Manager', 'Sales', '2023-01-15', 45000.00);

-- Insert sample clients
INSERT INTO clients (company_id, client_name, email, phone, client_type, status, total_spent, last_purchase_date) VALUES 
(1, 'Acme Corporation', 'contact@acme.com', '+216-71-234-567', 'B2B', 'Active', 15420.00, '2024-01-15'),
(1, 'John Smith LLC', 'john@company.com', '+216-71-345-678', 'B2B', 'Active', 8750.50, '2024-01-10'),
(1, 'Global Industries', 'info@global.com', '+216-71-456-789', 'B2B', 'Active', 23100.00, '2024-01-20');

-- Insert sample products
INSERT INTO products (company_id, product_name, sku, category, price, stock_quantity, description) VALUES 
(1, 'Cloud CRM Pro', 'CRM-001', 'Software', 29.99, 999, 'Monthly subscription with full access'),
(1, 'Security Suite', 'SEC-9021', 'Security', 150.00, 250, 'Advanced firewall & antivirus protection'),
(1, 'Premium Support Pack', 'SUP-300', 'Support', 500.00, 100, '24/7 premium support & consulting'),
(1, 'Enterprise Router', 'HW-450', 'Hardware', 299.00, 50, 'High-speed business router'),
(1, 'Office Suite License', 'LIC-200', 'License', 450.00, 500, '5-user business license');

-- Insert sample sales
INSERT INTO sales (transaction_id, company_id, employee_id, client_id, sale_date, subtotal, discount, tax, total_amount, payment_method, payment_status) VALUES 
('TX-2024-001', 1, 1, 1, '2024-01-15', 150.00, 15.00, 13.50, 148.50, 'Credit Card', 'Paid'),
('TX-2024-002', 1, 1, 2, '2024-01-10', 500.00, 0.00, 50.00, 550.00, 'Bank Transfer', 'Paid'),
('TX-2024-003', 1, 1, 3, '2024-01-20', 750.00, 50.00, 70.00, 770.00, 'Cash', 'Paid');

-- Insert sample sale items
INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, discount_percent, total_price) VALUES 
(1, 2, 'Security Suite', 1, 150.00, 10.00, 135.00),
(2, 3, 'Premium Support Pack', 1, 500.00, 0.00, 500.00),
(3, 4, 'Enterprise Router', 2, 299.00, 5.00, 568.10),
(3, 5, 'Office Suite License', 1, 450.00, 10.00, 405.00);

-- Insert invoices
INSERT INTO invoices (invoice_number, sale_id, issue_date, due_date, status) VALUES 
('INV-2024-001', 1, '2024-01-15', '2024-02-15', 'Paid'),
('INV-2024-002', 2, '2024-01-10', '2024-02-10', 'Paid'),
('INV-2024-003', 3, '2024-01-20', '2024-02-20', 'Sent');
