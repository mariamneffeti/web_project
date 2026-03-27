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
    payment_status ENUM('Paid', 'Pending') DEFAULT 'Pending',
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
);

-- Job Offers Management

CREATE TABLE IF NOT EXISTS job_icons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    icon_name VARCHAR(50) NOT NULL,
    bootstrap_class VARCHAR(100) NOT NULL,
    default_color VARCHAR(7) DEFAULT '#388087'
);

CREATE TABLE IF NOT EXISTS job_offers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    icon_id INT,
    title VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    type ENUM('Full-time', 'Contract', 'Urgent', 'Part-time', 'Internship') NOT NULL,
    category ENUM('tech', 'design', 'data', 'marketing', 'finance', 'hr') NOT NULL,
    salary_min INT,
    salary_max INT,
    experience_level ENUM('junior', 'mid', 'senior', 'lead') NOT NULL,
    description TEXT,
    tags TEXT, 
    status ENUM('active', 'closed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (icon_id) REFERENCES job_icons(id) ON DELETE SET NULL
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
INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, total_price) VALUES 
(1, 2, 'Security Suite', 1, 150.00, 135.00),
(2, 3, 'Premium Support Pack', 1, 500.00, 500.00),
(3, 4, 'Enterprise Router', 2, 299.00, 568.10),
(3, 5, 'Office Suite License', 1, 450.00, 405.00);

-- Insert invoices
INSERT INTO invoices (invoice_number, sale_id, issue_date, due_date) VALUES 
('INV-2024-001', 1, '2024-01-15', '2024-02-15'),
('INV-2024-002', 2, '2024-01-10', '2024-02-10'),
('INV-2024-003', 3, '2024-01-20', '2024-02-20');

-- Insert job icons
INSERT INTO job_icons (icon_name, bootstrap_class, default_color) VALUES 
('Stripe', 'bi-stripe', '#635bff'),
('Nvidia', 'bi-nvidia', '#76b900'),
('Finance/Bank', 'bi-bank', '#0f172a'),
('Code/Tech', 'bi-code-slash', '#388087'),
('Data/Charts', 'bi-bar-chart-fill', '#8d9b6a'),
('Marketing/News', 'bi-megaphone', '#e23e5a'),
('Design/Art', 'bi-brush', '#a855f7'),
('Mobile/Phone', 'bi-phone', '#555555'),
('Cloud/Azure', 'bi-cloud', '#0078d4'),
('AI/CPU', 'bi-cpu', '#10b981'),
('Management', 'bi-kanban', '#0f172a'),
('DevOps/Gear', 'bi-gear-wide-connected', '#388087'),
('HR/People', 'bi-people', '#8d9b6a'),
('Audit/Check', 'bi-clipboard-check', '#de7200'),
('Growth/Graph', 'bi-graph-up', '#f97316'),
('Stack/Fullstack', 'bi-stack', '#0f172a'),
('Badge/ID', 'bi-person-badge', '#f97316'),
('Security/Shield', 'bi-shield-check', '#388087'),
('Play/Motion', 'bi-play-circle', '#ec4899'),
('Lock/Cyber', 'bi-shield-lock', '#ef4444'),
('Briefcase', 'bi-briefcase', '#8d9b6a');

-- Insert sample job offers
INSERT INTO job_offers (company_id, icon_id, title, location, type, category, salary_min, salary_max, experience_level, tags, description) VALUES 
(1, 1, 'Backend Engineer', 'Remote, Europe', 'Full-time', 'tech', 4000, 4500, 'senior', 'Ruby, Go, API', 'Build financial infrastructure.'),
(1, 21, 'UI/UX Designer', 'Tunis', 'Contract', 'design', 3000, 3700, 'mid', 'Figma, Prototyping', 'Design beautiful experiences.'),
(1, 2, 'AI Research Lead', 'Germany', 'Urgent', 'data', 2000, 3500, 'lead', 'PyTorch, CUDA', 'Push boundaries of deep learning.'),
(1, 4, 'Frontend Developer', 'Tunis', 'Full-time', 'tech', 2500, 3200, 'mid', 'React, TypeScript', 'Build modern web interfaces.'),
(1, 5, 'Data Analyst', 'Sfax', 'Full-time', 'data', 1800, 2400, 'junior', 'SQL, Python', 'Transform raw data into insights.'),
(1, 11, 'Product Manager', 'Remote', 'Full-time', 'tech', 3500, 4800, 'senior', 'Agile, Roadmap', 'Own the product roadmap.'),
(1, 6, 'Social Media Manager', 'Tunis', 'Contract', 'marketing', 1500, 2200, 'junior', 'Meta, Content', 'Grow social presence.'),
(1, 12, 'DevOps Engineer', 'Tunis', 'Full-time', 'tech', 3000, 4000, 'mid', 'Docker, K8s', 'Optimize deployment pipelines.'),
(1, 7, 'Graphic Designer', 'Tunis', 'Part-time', 'design', 1200, 1800, 'junior', 'Illustrator, Photoshop', 'Create visual assets.'),
(1, 3, 'Financial Analyst', 'Tunis', 'Full-time', 'finance', 2200, 3000, 'mid', 'Excel, Risk', 'Analyze financial data.'),
(1, 13, 'HR Business Partner', 'Tunis', 'Full-time', 'hr', 2000, 2800, 'mid', 'Recruitment, L&D', 'Partner with business leaders.'),
(1, 8, 'Mobile Developer (iOS)', 'Tunis', 'Full-time', 'tech', 2800, 3600, 'mid', 'Swift, SwiftUI', 'Build sleek iOS applications.'),
(1, 21, 'Content Strategist', 'Remote', 'Contract', 'marketing', 1400, 2000, 'junior', 'SEO, Copywriting', 'Craft content strategies.'),
(1, 9, 'Cloud Architect', 'Remote', 'Full-time', 'tech', 5000, 6000, 'lead', 'Azure, Terraform', 'Design cloud infrastructure.'),
(1, 14, 'Audit Intern', 'Tunis', 'Internship', 'finance', 600, 900, 'junior', 'Excel, Audit', 'Support audit teams.'),
(1, 10, 'Machine Learning Engineer', 'Tunis', 'Full-time', 'data', 4000, 5500, 'senior', 'TensorFlow, Python', 'Develop ML models.'),
(1, 15, 'Marketing Analyst', 'Tunis', 'Full-time', 'marketing', 1800, 2500, 'junior', 'Google Ads, GA4', 'Analyze campaign performance.'),
(1, 16, 'Full-Stack Developer', 'Tunis', 'Full-time', 'tech', 2600, 3400, 'mid', 'Spring, Angular', 'Maintain core banking platforms.'),
(1, 17, 'Talent Acquisition Specialist', 'Tunis', 'Contract', 'hr', 1700, 2300, 'junior', 'LinkedIn, ATS', 'Lead end-to-end recruitment.'),
(1, 18, 'Risk Manager', 'Tunis', 'Full-time', 'finance', 2800, 3800, 'senior', 'Basel III, Risk', 'Oversee risk frameworks.'),
(1, 19, 'Motion Designer', 'Tunis', 'Part-time', 'design', 1400, 2100, 'mid', 'After Effects, Lottie', 'Produce motion graphics.'),
(1, 20, 'Cybersecurity Analyst', 'Tunis', 'Urgent', 'tech', 2500, 3500, 'mid', 'SOC, Pentest', 'Defend critical infrastructure.'),
(1, 21, 'Business Developer', 'Tunis', 'Full-time', 'marketing', 2000, 3000, 'mid', 'B2B, Negotiation', 'Convert new opportunities.'),
(1, 10, 'Embedded Systems Engineer', 'Sousse', 'Full-time', 'tech', 3200, 4200, 'senior', 'C, RTOS, CAN Bus', 'Develop automotive systems.');