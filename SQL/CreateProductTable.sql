CREATE TABLE Product_Table (
	ID int NOT NULL AUTO_INCREMENT,
	Name varchar(25) NOT NULL,
	Description varchar(64),
	Price float(24) NOT NULL,
	Quantity int NOT NULL,
	Status varchar(1) NOT NULL,
	SupplierID int NOT NULL,
	PRIMARY KEY (ID)
);