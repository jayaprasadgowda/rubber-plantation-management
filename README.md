# rubber-plantation-management
A web-based system to manage rubber plantation operations, including collection, inventory, sales, reports, losses, and employee management
-- database.sql
CREATE DATABASE IF NOT EXISTS rubber_management;
USE rubber_management;

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(50) NOT NULL
);

CREATE TABLE employee_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    payment_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    remarks TEXT,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE expenditures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entry_date DATE NOT NULL,
    type ENUM('Loss', 'Expenditure') NOT NULL,
    reason VARCHAR(100) NOT NULL,
    lost_sheets INT,
    amount DECIMAL(10,2),
    remarks TEXT
);

CREATE TABLE rubber_entry (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entry_date DATE NOT NULL,
    milk_liters DECIMAL(10,2) NOT NULL,
    acid_used DECIMAL(10,2) NOT NULL,
    expected_sheets INT NOT NULL,
    actual_sheets INT NOT NULL,
    rate_per_sheet DECIMAL(10,2) NOT NULL
);

CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_date DATE NOT NULL,
    sheets_sold INT NOT NULL,
    rate_per_sheet DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL
);

CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entry_date DATE NOT NULL,
    type ENUM('IN', 'OUT') NOT NULL,
    sheets INT NOT NULL,
    remarks TEXT
);

-- Sample data (optional, avoid sensitive data)
INSERT INTO employees (name, role) VALUES
('Ramesh Kumar', 'Tapper'),
('Sita Devi', 'Processor'),
('Anil Sharma', 'Supervisor');

INSERT INTO employee_payments (employee_id, payment_date, amount, remarks) VALUES
(1, '2025-06-30', 5000.00, 'Monthly wage'),
(2, '2025-07-01', 4500.00, 'Includes overtime'),
(3, '2025-07-02', 6000.00, 'Supervisor bonus');

INSERT INTO expenditures (entry_date, type, reason, lost_sheets, amount, remarks) VALUES
('2025-06-30', 'Loss', 'Heavy rain', 2, NULL, 'Production halted due to flooding'),
('2025-07-01', 'Loss', 'Poor drying', 1, NULL, 'Sheets damaged due to humidity'),
('2025-07-02', 'Expenditure', 'Employee Wages', NULL, 15500.00, 'Monthly wages for Ramesh, Sita, Anil'),
('2025-07-02', 'Expenditure', 'Acid Purchase', NULL, 2000.00, 'Bought 50 liters of acid');
