CREATE TABLE Administrators (
	ID int NOT NULL AUTO_INCREMENT,
	AdminName Varchar(25) NOT NULL,
	AdminPassword Varchar(64) NOT NULL,
	PRIMARY KEY (ID)
);

INSERT INTO administrators (AdminName, AdminPassword)
VALUES ('KelliMK', 'insecure_system');

/* 
Notes

If he needs a login screen, we'll need one of these. We'll also need a password system. Ugh

*/