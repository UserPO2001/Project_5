-- Create the User table with ID as Primary Key
CREATE TABLE User (
    ID INT AUTO_INCREMENT PRIMARY KEY, 
    Username VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    First_name VARCHAR(50),
    Last_name VARCHAR(50)
);

-- Insert a new user into the User table
INSERT INTO User (Username, Password, First_name, Last_name)
VALUES ('Ustam', 'root', 'Akkar', 'Akdag');
