-- creating a database 
create DATABASE Mydb;

-- choose a database
use MyDB;

-- to delete a database use "drop database MyDB;"

-- creating a table
create table employees(
    employee_id int,
    first_name varchar(50),
    last_name varchar(50),
    hourly_pay decimal(5, 2),
    hire_date date
);

-- to show table use "select * from employees;"
-- rename table using "alter table employees rename to workers;"
-- to delete a table use "drop table employees;"

-- adding a new column
alter table employees
ADD phone_number varchar(15);

-- to change the name of a column
alter table employees
rename column phone_number to email;

-- changing the max character
alter table employees
MODIFY COLUMN email varchar(100);

-- moving column
ALTER TABLE employees
MODIFY email varchar(100) 
AFTER last_name;
 -- this will put email after last_name
 -- to put "email" at the beginning use "FIRST" instead of "AFTER"



-- to delete a column
alter table employees
drop column email;

-- insert a data into a table
INSERT INTO employees
VALUES          (1, "Eugene", "Krabs", 25.50, "2023-01-02"),
                (2, "Squidward", "Tentacles", 15.00, "2023-01-03"), 
                (3, "Spongebob", "Squarepants", 12.50, "2023-01-04"), 
                (4, "Patrick", "Star", 12.50, "2023-01-05"), 
                (5, "Sandy", "Cheeks", 17.25, "2023-01-06");
SELECT * FROM employees;

-- adding to a specific column
INSERT INTO employees (employee_id, first_name, last_name)
VALUES  (6, "Sheldon", "Plankton");
SELECT * FROM employees;

-- how to select data from a table 
select * from employees; -- all data
select first_name, last_name from employees; -- specific columns
SELECT * FROM employees where employee_id = 1; -- specific row of data
SELECT * FROM employees where employee_id !=1; -- all data except for employee_id 1
SELECT * from employees where hire_date is null -- all data where hire_date is null --we could use is not null for the opposite

-- How to UPDATE and DELETE data from a TABLE
    -- updating data from the table
        update employees  
        set hourly_pay = 10.25,
            hire_date = "2023-01-07"
        where employee_id = 6;
        select * from employees;

    -- deleting data from the table
        delete from employees
        where employee_id = 6;