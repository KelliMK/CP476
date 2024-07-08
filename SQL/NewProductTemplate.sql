/* For web user use, as user will not provide a ProductID */
INSERT INTO Product_Table (Name, Description, Price, Quantity, Status, SupplierID)
VALUES (Name, Description, 00.00, 0, A, SupplierID);

/* For inserting products that have assigned IDs */
INSERT INTO Product_Table
VALUES (ID, Name, Description, 00.00, 0, A, SupplierID);

/*
Notes:

Users should pick Supplier ID from some form of a dropdown menu, since they shouldn't be able to create new suppliers by creating a new product

*/