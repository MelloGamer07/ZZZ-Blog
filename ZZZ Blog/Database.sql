CREATE TABLE Utente (
	Id INT AUTO_INCREMENT PRIMARY KEY,
	Email VARCHAR(320) NOT NULL UNIQUE,
Username VARCHAR(25) NOT NULL UNIQUE,
	PasswordHash VARCHAR(30) NOT NULL,
	Avatar INT DEFAULT 0,
	Ruolo ENUM('user','admin') DEFAULT 'user',
	DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
DataAggiornamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO Utente (Email, Username, PasswordHash, Ruolo)
VALUES
('ambrogio68@gmail.com', 'Just4mbrogio', '4mbrogi@', 'admin'),
('Zenin.Toji@gmail.com', 'TheOneWhoLeftItAllBehind', 'i4m4apple', 'admin');