-- Create users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    Password VARCHAR(100) NOT NULL,
    First_name VARCHAR(50) NOT NULL,
    Last_name VARCHAR(50) NOT NULL
);

-- Create consumption table
CREATE TABLE consumption (
    consumption_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    electricity_consumed_kWh DECIMAL(10, 2) NOT NULL,
    water_consumed_m3 DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Insert sample users
INSERT INTO users (Username, Password, First_name, Last_name) VALUES
('user1', 'pass1', 'John', 'Doe'),
('user2', 'pass2', 'Jane', 'Smith'),
('user3', 'pass3', 'Alice', 'Johnson'),
('user4', 'pass4', 'Bob', 'Brown'),
('user5', 'pass5', 'Charlie', 'Davis');

-- Insert sample consumption data
INSERT INTO consumption (user_id, date, electricity_consumed_kWh, water_consumed_m3) VALUES
(1, '2024-01-31', 300.50, 10.30),
(2, '2024-01-31', 420.75, 12.80),
(3, '2024-01-31', 315.40, 11.25),
(4, '2024-01-31', 280.60, 9.90),
(5, '2024-01-31', 450.20, 13.70);