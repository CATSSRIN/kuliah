CREATE TABLE customers (
     customer_id INT PRIMARY KEY AUTO_INCREMENT,
     first_name VARCHAR(50),
     last_name VARCHAR(50)
);
INSERT INTO customers (first_name, last_name)
VALUES  ("Fred", "Fish"),
                ("Larry", "Lobster"),
                ("Bubble", "Bass");
SELECT * FROM customers;

-- Add a named foreign key constraint to a new table
CREATE TABLE transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    amount DECIMAL(5, 2),
    customer_id INT,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);
ALTER TABLE transactions
AUTO_INCREMENT = 1000;

-- Add a named foreign key constraint to an existing table
ALTER TABLE customers
ADD CONSTRAINT fk_customer_id
FOREIGN KEY (customer_id) REFERENCES customers(customer_id);