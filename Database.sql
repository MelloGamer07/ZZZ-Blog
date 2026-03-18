/*   UTENTE   */

CREATE TABLE Utente (
	Id INT AUTO_INCREMENT PRIMARY KEY,
	Email VARCHAR(320) NOT NULL UNIQUE,
  Username VARCHAR(20) NOT NULL UNIQUE,
	PasswordHash VARCHAR(255) NOT NULL,
	Avatar INT DEFAULT 0,
	Ruolo ENUM('user','admin', 'onThinIce') DEFAULT 'user',
	DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  DataAggiornamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO Utente (Email, Username, PasswordHash, Avatar, Ruolo)
VALUES
('ambrogio68@gmail.com', 'Just4mbrogio', 'Password123#', 29, 'admin'),
('Zenin.Toji@gmail.com', 'TheOneWhoLeftItAllBehind', 'Password123#', 20, 'admin');

/*   ARTICOLO   */
CREATE TABLE Articolo (
	Id INT AUTO_INCREMENT PRIMARY KEY,
	IdUtente INT NOT NULL,
	Title VARCHAR(200) NOT NULL,
	Img VARCHAR(100),
	Descrizione VARCHAR(1000) NOT NULL, 
	Pubblicato BOOLEAN DEFAULT TRUE,
  DataCreazione  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (IdUtente) REFERENCES Utente(Id) ON UPDATE CASCADE ON DELETE CASCADE
);

/*SELECT COUNT(*) FROM LikeArticolo WHERE IdArticolo = ???;*/

INSERT INTO Articolo (IdUtente, Title, Img, Descrizione)
VALUES
(
  1,
  'The Proxy’s Invisible Burden',
  'ASSETS/IMG/LoadingScreens/2.jpg',
  'Playing as a Proxy is less about heroics and more about responsibility. Unlike many action RPG protagonists, the Proxy rarely takes direct credit for victories. Instead, they coordinate, observe, and manage risk from the sidelines. This narrative choice reframes power as foresight rather than brute strength. Every decision about which agents to deploy, which commissions to accept, and how much danger to tolerate reflects the quiet pressure of leadership. Zenless Zone Zero uses this role to explore how influence often operates unseen. You are not the strongest fighter in the room, but you are the reason the fight happens at all. That subtle shift in perspective adds emotional weight to even routine missions. In embracing the Proxy’s role, the game challenges traditional ideas of power and highlights responsibility as its true cost.'
),

(
  2,
  'The Proxy’s Invisible Burden',
  'ASSETS/IMG/LoadingScreens/2.jpg',
  'Playing as a Proxy is less about heroics and more about responsibility. Unlike many action RPG protagonists, the Proxy rarely takes direct credit for victories. Instead, they coordinate, observe, and manage risk from the sidelines. This narrative choice reframes power as foresight rather than brute strength. Every decision about which agents to deploy, which commissions to accept, and how much danger to tolerate reflects the quiet pressure of leadership. Zenless Zone Zero uses this role to explore how influence often operates unseen. You are not the strongest fighter in the room, but you are the reason the fight happens at all. That subtle shift in perspective adds emotional weight to even routine missions. In embracing the Proxy’s role, the game challenges traditional ideas of power and highlights responsibility as its true cost.'
);

/*   CATEGORIA   */
CREATE TABLE Categoria(
  Id INT AUTO_INCREMENT PRIMARY KEY, 
  Nome VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE CategoriaArticolo(
  IdArticolo INT NOT NULL, 
  IdCategoria INT NOT NULL, 

  PRIMARY KEY(IdArticolo, IdCategoria),

  FOREIGN KEY(IdArticolo) REFERENCES Articolo(Id), 
  FOREIGN KEY(IdCategoria) REFERENCES Categoria(Id)
);

/*   COMMENTO   */

CREATE TABLE Commento(
  Id INT AUTO_INCREMENT PRIMARY KEY,
  Content  VARCHAR(400) NOT NULL, 
  IdUtente INT NOT NULL,
  IdArticolo INT NOT NULL,
  isApproved BOOLEAN DEFAULT TRUE,
  DataCreazione  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (IdArticolo) REFERENCES Articolo(Id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (IdUtente) REFERENCES Utente(Id) ON UPDATE CASCADE ON DELETE CASCADE
);

/*SELECT COUNT(*) FROM LikeCommento WHERE IdArticolo = ???;*/

/*   SEGNALAZIONE COMMENTO   */
CREATE TABLE Segnalazione_Commento(
  Id INT AUTO_INCREMENT PRIMARY KEY, 
  IdCommento INT NOT NULL, 
  IdUtente INT NOT NULL, 
  UNIQUE(IdCommento, IdUtente), 
  Ragione VARCHAR(400), 
  DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (IdCommento) REFERENCES Commento(Id),
  FOREIGN KEY (IdUtente) REFERENCES Utente(Id)
);

INSERT INTO Commento(IdUtente, IdArticolo, Content)
VALUES(
  1, 
  1, 
  'Yuh uh'
),
(
  2, 
  1, 
  'Nuh uh'
);

/*   LIKE   */
CREATE TABLE LikeArticolo(
  IdUtente INT NOT NULL,
  IdArticolo INT NOT NULL,
  PRIMARY KEY(IdUtente, IdArticolo),
  FOREIGN KEY (IdUtente) REFERENCES Utente(Id) ON UPDATE CASCADE,
  FOREIGN KEY (IdArticolo) REFERENCES Articolo(Id) ON UPDATE CASCADE
);

CREATE TABLE LikeCommento(
	IdUtente INT NOT NULL,
	IdCommento INT NOT NULL,
	
	PRIMARY KEY(IdUtente, IdCommento),
	
	FOREIGN KEY (IdUtente) REFERENCES Utente(Id),
  FOREIGN KEY (IdCommento) REFERENCES Commento(Id) ON DELETE CASCADE
);

/*   ADMIN STUFF   */
CREATE TABLE AdminLogs (
  Id INT AUTO_INCREMENT PRIMARY KEY,
  IdAdmin INT NOT NULL,
  AzionePresa VARCHAR(255) NOT NULL,
  IdTargetUtente INT,
  DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (IdAdmin) REFERENCES Utente(Id),
  FOREIGN KEY (IdTargetUtente) REFERENCES Utente(Id)

);

CREATE TABLE ParoleBan(
  Id INT AUTO_INCREMENT PRIMARY KEY, 
  Parola VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE AdminLogs_ParoleBan(
  IdAdminLog INT,
  IdParolaBan INT,

  PRIMARY KEY(IdAdminLog, IdParolaBan),

  FOREIGN KEY(IdAdminLog) REFERENCES AdminLogs(Id), 
  FOREIGN KEY(IdParolaBan) REFERENCES ParoleBan(Id)
);

/*  RICERCHE VELOCI DA CAPIRE E VEDERE */

/*CREATE INDEX idx_utenti_username ON Utente(Username);
CREATE INDEX idx_articoli_title ON Articolo(Title);*/
