/*   UTENTE   */




CREATE TABLE Utente (
  Id INT AUTO_INCREMENT PRIMARY KEY,
  Email VARCHAR(320) NOT NULL UNIQUE,
  Username VARCHAR(20) NOT NULL UNIQUE,
  PasswordHash VARCHAR(255) NOT NULL,
  Avatar INT DEFAULT 0,
  Descrizione VARCHAR(200),
  Ruolo ENUM('user','admin', 'onThinIce') DEFAULT 'user',
  XP INT UNSIGNED DEFAULT 0,
  DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  DataAggiornamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);




INSERT INTO Utente (Email, Username, PasswordHash, Avatar, Ruolo)
VALUES
('ambrogio68@gmail.com', 'Just4mbrogio', '4mbrogi@', 29, 'admin'),
('Zenin.Toji@gmail.com', 'TheOneWhoLeftItAllBehind', 'Password123#', 20, 'admin');
/* NOTE: Fixed swapped Avatar/PasswordHash columns in the second insert */




/* Follow */
CREATE TABLE Follow (
  IdUtente INT NOT NULL,
  IDUtenteFollow INT NOT NULL,


  PRIMARY KEY(IdUtente, IDUtenteFollow),


  FOREIGN KEY (IdUtente) REFERENCES Utente(Id) ON UPDATE CASCADE,
  FOREIGN KEY (IDUtenteFollow) REFERENCES Utente(Id) ON UPDATE CASCADE
);




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




INSERT INTO Articolo (IdUtente, Title, Img, Descrizione)
VALUES
(
  1,
  'The Proxy''s Invisible Burden',
  'ASSETS/IMG/LoadingScreens/2.jpg',
  'Playing as a Proxy is less about heroics and more about responsibility. Unlike many action RPG protagonists, the Proxy rarely takes direct credit for victories. Instead, they coordinate, observe, and manage risk from the sidelines. This narrative choice reframes power as foresight rather than brute strength. Every decision about which agents to deploy, which commissions to accept, and how much danger to tolerate reflects the quiet pressure of leadership. Zenless Zone Zero uses this role to explore how influence often operates unseen. You are not the strongest fighter in the room, but you are the reason the fight happens at all. That subtle shift in perspective adds emotional weight to even routine missions. In embracing the Proxy''s role, the game challenges traditional ideas of power and highlights responsibility as its true cost.'
),
(
  2,
  'The Proxy''s Invisible Burden',
  'ASSETS/IMG/LoadingScreens/2.jpg',
  'Playing as a Proxy is less about heroics and more about responsibility. Unlike many action RPG protagonists, the Proxy rarely takes direct credit for victories. Instead, they coordinate, observe, and manage risk from the sidelines. This narrative choice reframes power as foresight rather than brute strength. Every decision about which agents to deploy, which commissions to accept, and how much danger to tolerate reflects the quiet pressure of leadership. Zenless Zone Zero uses this role to explore how influence often operates unseen. You are not the strongest fighter in the room, but you are the reason the fight happens at all. That subtle shift in perspective adds emotional weight to even routine missions. In embracing the Proxy''s role, the game challenges traditional ideas of power and highlights responsibility as its true cost.'
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


INSERT INTO Commento(IdUtente, IdArticolo, Content)
VALUES
(1, 1, 'Yuh uh'),
(2, 1, 'Nuh uh');




/*   LIKE   */
CREATE TABLE LikeArticolo(
  IdUtente INT NOT NULL,
  IdArticolo INT NOT NULL,
  PRIMARY KEY(IdUtente, IdArticolo),
  FOREIGN KEY (IdUtente) REFERENCES Utente(Id) ON UPDATE CASCADE,
  FOREIGN KEY (IdArticolo) REFERENCES Articolo(Id) ON UPDATE CASCADE
);


/*SELECT COUNT(*) FROM LikeArticolo WHERE IdArticolo = ???;*/


CREATE TABLE LikeCommento(
  IdUtente INT NOT NULL,
  IdCommento INT NOT NULL,
 
  PRIMARY KEY(IdUtente, IdCommento),
 
  FOREIGN KEY (IdUtente) REFERENCES Utente(Id),
  FOREIGN KEY (IdCommento) REFERENCES Commento(Id) ON DELETE CASCADE
);


/*SELECT COUNT(*) FROM LikeCommento WHERE IdCommento = ???;*/


/*   SEGNALAZIONE   */


CREATE TABLE Segnalazione(
  Id INT PRIMARY KEY AUTO_INCREMENT,
  Ragione Varchar(160) NOT NULL,
  DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 
  IdUtente INT NOT NULL,
  IdArticolo INT NOT NULL,
  IdCommento INT,


  FOREIGN KEY (IdUtente) REFERENCES Utente(Id) ON UPDATE CASCADE,
  FOREIGN KEY (IdArticolo) REFERENCES Articolo(Id) ON UPDATE CASCADE,
  FOREIGN KEY (IdCommento) REFERENCES Commento(Id) ON UPDATE CASCADE
);


INSERT INTO segnalazione (Ragione, IdUtente, IdArticolo) VALUES ("Odio i negri", 2 , 1);
INSERT INTO segnalazione (Ragione, IdUtente, IdArticolo , IdCommento) VALUES ("Odio i negri", 1 , 1 , 1);




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




/* ================= XP TRIGGERS ================= */


DELIMITER $$


-- +10 XP when someone likes an article
CREATE TRIGGER trg_like_article_add
AFTER INSERT ON LikeArticolo
FOR EACH ROW
BEGIN
  UPDATE Utente u
  JOIN Articolo a ON a.Id = NEW.IdArticolo
  SET u.XP = GREATEST(0, u.XP + 10)
  WHERE u.Id = a.IdUtente;
END$$


-- -10 XP when a like on an article is removed
CREATE TRIGGER trg_like_article_remove
AFTER DELETE ON LikeArticolo
FOR EACH ROW
BEGIN
  UPDATE Utente u
  JOIN Articolo a ON a.Id = OLD.IdArticolo
  SET u.XP = GREATEST(0, u.XP - 10)
  WHERE u.Id = a.IdUtente;
END$$


-- +5 XP when someone likes a comment
CREATE TRIGGER trg_like_comment_add
AFTER INSERT ON LikeCommento
FOR EACH ROW
BEGIN
  UPDATE Utente u
  JOIN Commento c ON c.Id = NEW.IdCommento
  SET u.XP = GREATEST(0, u.XP + 5)
  WHERE u.Id = c.IdUtente;
END$$


-- -5 XP when a like on a comment is removed
CREATE TRIGGER trg_like_comment_remove
AFTER DELETE ON LikeCommento
FOR EACH ROW
BEGIN
  UPDATE Utente u
  JOIN Commento c ON c.Id = OLD.IdCommento
  SET u.XP = GREATEST(0, u.XP - 5)
  WHERE u.Id = c.IdUtente;
END$$


-- +20 XP when someone follows this user
CREATE TRIGGER trg_follow_add
AFTER INSERT ON Follow
FOR EACH ROW
BEGIN
  UPDATE Utente SET XP = GREATEST(0, XP + 20)
  WHERE Id = NEW.IDUtenteFollow;
END$$


-- -20 XP when someone unfollows this user
CREATE TRIGGER trg_follow_remove
AFTER DELETE ON Follow
FOR EACH ROW
BEGIN
  UPDATE Utente SET XP = GREATEST(0, XP - 20)
  WHERE Id = OLD.IDUtenteFollow;
END$$


DELIMITER ;




/*  RICERCHE VELOCI DA VEDERE  */


/*CREATE INDEX idx_utenti_username ON Utente(Username);
CREATE INDEX idx_articoli_title ON Articolo(Title);*/


-- Add this to zzz_2.sql or run it separately
CREATE TABLE RememberTokens (
  Id          INT AUTO_INCREMENT PRIMARY KEY,
  IdUtente    INT NOT NULL,
  Token       VARCHAR(64) NOT NULL UNIQUE,
  DataScadenza DATETIME NOT NULL,
  DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


  FOREIGN KEY (IdUtente) REFERENCES Utente(Id) ON DELETE CASCADE
);




/*  NOTIFICHE  */


CREATE TABLE Notifica (
  Id INT AUTO_INCREMENT PRIMARY KEY,


  IdDestinatario INT NOT NULL,
  IdMittenteLog INT,


  Tipo ENUM(
    'follow',
    'post_eliminato',
    'commento_eliminato'
  ) NOT NULL,


  IdArticolo INT NULL,
  IdCommento INT NULL,


  Messaggio VARCHAR(160),
  Letta BOOLEAN DEFAULT FALSE,
  DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


  FOREIGN KEY (IdDestinatario) REFERENCES Utente(Id) ON DELETE CASCADE,
  FOREIGN KEY (IdMittenteLog) REFERENCES AdminLogs(Id) ON DELETE SET NULL,
  FOREIGN KEY (IdArticolo) REFERENCES Articolo(Id) ON DELETE SET NULL,
  FOREIGN KEY (IdCommento) REFERENCES Commento(Id) ON DELETE SET NULL
);


/*  SHOWS ALL UNREAD NOTIFICATIONS FOR THE SELECTED USER  */
/*  Try to see if it's worth using it  */


/*CREATE INDEX idx_notifiche_user_letta
ON Notifica(IdDestinatario, Letta);*/


CREATE TABLE Ban (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    UtenteId INT,
    Motivo VARCHAR(255),
    DataInizio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    DataFine TIMESTAMP NULL,
    FOREIGN KEY (UtenteId) REFERENCES Utente(Id)
);