CREATE TABLE IF NOT EXISTS UserInfo (
    user_ID INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    name VARCHAR(255),
    address TEXT,
    age INT CHECK (age >= 13)
);

CREATE TABLE IF NOT EXISTS Product (
    product_ID INT AUTO_INCREMENT PRIMARY KEY,
    size VARCHAR(50),
    Price DECIMAL(10, 2),
    Name VARCHAR(255),
    stock INT,
    pic BLOB
);

CREATE TABLE IF NOT EXISTS OrderHistory (
    order_ID INT AUTO_INCREMENT PRIMARY KEY,
    user_ID INT,
    order_price DECIMAL(10, 2),
    discounted_price DECIMAL(10, 2),
    order_size INT,
    order_date DATE,
    FOREIGN KEY (user_ID) REFERENCES UserInfo(user_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Wishlist (
    user_ID INT,
    wishlist_name VARCHAR(255),
    product_ID INT,
    PRIMARY KEY (user_ID, product_ID),
    FOREIGN KEY (user_ID) REFERENCES UserInfo(user_ID) ON DELETE CASCADE,
    FOREIGN KEY (product_ID) REFERENCES Product(product_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS CartItem (
    product_ID INT,
    user_ID INT,
    quantity INT,
    PRIMARY KEY (product_ID, user_ID),
    FOREIGN KEY (user_ID) REFERENCES UserInfo(user_ID) ON DELETE CASCADE,
    FOREIGN KEY (product_ID) REFERENCES Product(product_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Reviews (
    user_ID INT,
    product_ID INT,
    comment_date DATE,
    usercomment TEXT,
    PRIMARY KEY (user_ID, product_ID),
    FOREIGN KEY (user_ID) REFERENCES UserInfo(user_ID) ON DELETE CASCADE,
    FOREIGN KEY (product_ID) REFERENCES Product(product_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS OrderInfo (
    order_ID INT,
    product_ID INT,
    quantity INT,
    price DECIMAL(10, 2),
    PRIMARY KEY (order_ID, product_ID),
    FOREIGN KEY (product_ID) REFERENCES Product(product_ID),
    FOREIGN KEY (order_ID) REFERENCES OrderHistory(order_ID) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS Coupon (
    coupon_ID VARCHAR(255) PRIMARY KEY,
    value DECIMAL(10, 2)
);

CREATE TABLE IF NOT EXISTS Applied (
    coupon_ID VARCHAR(255),
    user_ID INT,
    PRIMARY KEY (coupon_ID, user_ID),
    FOREIGN KEY (user_ID) REFERENCES UserInfo(user_ID) ON DELETE CASCADE,
    FOREIGN KEY (coupon_ID) REFERENCES Coupon(coupon_ID)
);

DELIMITER $$
CREATE TRIGGER updatestockTrigger
BEFORE INSERT ON OrderInfo
FOR EACH ROW
BEGIN
    UPDATE Product
    SET stock = stock - new.quantity
    WHERE product_ID = new.product_ID;
END
$$
DELIMITER ;

INSERT INTO UserInfo(email, password, name, address, age)
VALUES
("ejc9ht@virginia.edu", "Abc!", "Emily", "123 North South Street, Alabama", 20),
("uqs3dq@virginia.edu", "Abc!", "Bereket", "123 North South Street, Alabama", 20),
("cfr5spw@virginia.edu", "Abc!", "Carlos Revilla", "123 North South Street, Alabama", 20),
("ncn7tvy@virginia.edu", "Abc!", "Natalie N.", "123 North South Street, Alabama", 20),
("4@gmail.com", "Abc!", "Four Four", "123 North South Street, Alabama", 20);


INSERT INTO Product(size, Price, Name, stock)
VALUES
("8oz", 14.99, "Soothing Shine", 20),
("12oz", 24.99, "Soothing Shine", 20),
("8oz", 12.99, "Cozy Vanilla", 20),
("12oz", 20.99, "Cozy Vanilla", 20),
("8oz", 12.99, "Fresh Roses", 20),
("12oz", 20.99, "Fresh Roses", 20),
("8oz", 12.99, "Night Glow", 20),
("12oz", 20.99, "Night Glow", 20),
("8oz", 14.99, "Lazy Lullaby Lemon", 20),
("12oz", 24.99, "Lazy Lullaby Lemon", 20),
("8oz", 17.99, "Girl Scout Cookies", 20),
("12oz", 27.99, "Girl Scout Cookies", 20);

INSERT INTO Wishlist(user_ID, product_ID, wishlist_name) 
VALUES 
(3, 1, "Girly"), 
(3, 6, "Girly"), 
(1, 8, "Summer");

INSERT INTO CartItem(product_ID, user_ID, quantity) 
VALUES 
(1, 3, 1), 
(6,3,1), 
(4,3,2);

INSERT INTO Reviews(user_ID, product_ID, comment_date, usercomment) 
VALUES
(1, 4, "2024-03-21", "bad"),
(3, 4, "2024-03-21", "not bad"),
(2, 5, "2024-03-21", "super bad");

INSERT INTO OrderHistory(user_ID, order_ID, order_price, discounted_price, order_size, order_date) VALUES
(1, 1, 63.96, 54.37, 4, "2024-03-21"),
(1, 2, 45.98, 45.98, 2, "2024-03-22"),
(2, 3, 62.97, 62.97, 3, "2024-03-22"),
(3, 4, 45.98,  45.98, 2, "2024-03-22"),
(4, 5, 27.99, 27.99, 1, "2024-03-22");

INSERT INTO OrderInfo (order_ID, product_ID, quantity, price) 
VALUES 
(1, 1, 1, 24.99),
(1, 6, 1, 12.99),
(1, 4, 2, 25.98),
(2, 1, 1, 24.99),
(2, 5, 1, 20.99),
(3, 7, 3, 62.97),
(4, 7, 1, 20.99),
(4, 9, 1, 24.99),
(5, 11, 1, 27.99);

INSERT INTO Coupon(coupon_ID, value) 
VALUES 
('15OFF', 0.15),
('20OFF', 0.2),
('25OFF', 0.25);

INSERT INTO Applied(coupon_ID, user_ID) 
VALUES 
('15OFF', 1);

## SQL COMMANDS

## These functionalities require insert statements, which we wrote above.
# When products are added to cart
# When products are added to wishlist
# When checkout, order added to orderhistory and and each product added to orderinfo
# Add things to product table

# User removes items from their cart or wishlist
DELETE FROM CartItem WHERE user_ID = 3 AND product_ID = 1;
DELETE FROM Wishlist WHERE user_ID = 3 AND product_ID = 1;

## User filters search by price
SELECT * 
FROM Product
WHERE price <= 20.00;

## User wants to update the quantity of a specific product in their cart
UPDATE CartItem SET quantity = 2 WHERE user_ID = 3 AND product_ID = 6;

## When we want to update stock 
UPDATE Product SET stock = 25 WHERE product_ID = 1;

## User deletes their account
DELETE FROM UserInfo WHERE user_ID = 2;

## When the stock of an item increases
UPDATE Product SET stock = 25 WHERE product_ID = 1;

## Check if a user has used a coupon before
## assume user = 1, coupon "15OFF"
SELECT COUNT(*)
FROM Applied
WHERE user_ID = 1 AND coupon_ID = "15OFF";

## When a user moves their wishlist items to cart
## user = 1, default quantity to 1
INSERT INTO CartItem (product_ID, user_ID, quantity)
SELECT product_ID, user_ID, 1
FROM Wishlist
WHERE user_ID = 1;
DELETE FROM Wishlist
WHERE user_ID = 1;

## When a user checks out their cart
## Add order to order history (assume user = 3 and coupon is 15% off)
INSERT INTO OrderHistory(user_ID, order_price, discounted_price, order_size, order_date) 
SELECT 3, SUM(price*quantity), SUM(price*quantity)*(1-0.15), SUM(quantity), CURRENT_DATE
FROM CartItem NATURAL JOIN Product
WHERE user_ID = 3;
## Add all items from order to OrderInfo
DELIMITER //

CREATE PROCEDURE InsertCartItemsIntoOrder(IN user_id_param INT)
BEGIN
    DECLARE recent_order_id INT;

    ## Get the most recent order ID for the user
    SELECT MAX(order_Id) INTO recent_order_id
    FROM OrderHistory
    WHERE user_ID = user_id_param;

    ## Create a temporary table to hold cart items with price to not edit product table
    CREATE TEMPORARY TABLE temp_cart_items AS
    SELECT c.product_id, c.quantity, p.price
    FROM CartItem c
    JOIN Product p ON c.product_id = p.product_id
    WHERE c.user_ID = user_id_param;

    ## Insert cart items into OrderInfo
    INSERT INTO OrderInfo (order_ID, product_ID, quantity, price)
    SELECT recent_order_id, product_id, quantity, price
    FROM temp_cart_items;

    ## Drop the temporary table
    DROP TEMPORARY TABLE IF EXISTS temp_cart_items;

    ## Delete cart items after inserting into OrderInfo
    DELETE FROM CartItem
    WHERE user_ID = user_id_param;
END//

DELIMITER ;
## To run this procedure on any given user once they order, run 
## CALL InsertCartItemsIntoOrder(user_ID);
## And fill in user_id based on who is logged into the session

