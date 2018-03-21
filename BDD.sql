SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `Balises`, `Realise`, `Courses`, `Lieux`, `Utilisateurs`;
SET FOREIGN_KEY_CHECKS=1;

#------------------------------------------------------------
# Table: Utilisateurs
#------------------------------------------------------------

CREATE TABLE Utilisateurs (
        id_user   INTEGER AUTO_INCREMENT NOT NULL,
        login     VARCHAR (255) NOT NULL,
        password  VARCHAR (255) NOT NULL,
        nom       VARCHAR (255) NOT NULL,
        prenom    VARCHAR (255) NOT NULL,
        mail      VARCHAR (255) NOT NULL,
        age       INTEGER NOT NULL,
        genre     CHAR (1) NOT NULL,
        PRIMARY KEY (id_user)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;


#------------------------------------------------------------
# Table: Courses
#------------------------------------------------------------

CREATE TABLE Courses (
        id_course    INTEGER AUTO_INCREMENT NOT NULL,
        nom          VARCHAR (255) NOT NULL,
        type         VARCHAR (255) NOT NULL,
        debut        DATETIME NOT NULL,
        fin          DATETIME NOT NULL,
        tempsImparti TIME NOT NULL,
        fk_lieu      INT NOT NULL,
        fk_user      INT NOT NULL,
        PRIMARY KEY (id_course)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# Table: Lieux
#------------------------------------------------------------

CREATE TABLE Lieux (
        id_lieu     INTEGER AUTO_INCREMENT NOT NULL,
        nom         VARCHAR (255) NOT NULL,
        description TEXT NOT NULL,
        longitude   DOUBLE NOT NULL,
        latitude    DOUBLE NOT NULL,
        adresse     TEXT NOT NULL,
        cp          VARCHAR (25) NOT NULL,
        ville       VARCHAR (255) NOT NULL,
        PRIMARY KEY (id_lieu)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;


#------------------------------------------------------------
# Table: Balises
#------------------------------------------------------------

CREATE TABLE Balises (
        id_balise INTEGER AUTO_INCREMENT NOT NULL,
        valeur    INTEGER NOT NULL,
        longitude DOUBLE NOT NULL,
        latitude  DOUBLE NOT NULL,
        fk_course INT NOT NULL,
        PRIMARY KEY (id_balise)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;


#------------------------------------------------------------
# Table: Realise
#------------------------------------------------------------

CREATE TABLE Realise (
        score     INTEGER,
        fk_user   INT NOT NULL,
        fk_course INT NOT NULL,
        PRIMARY KEY (fk_user, fk_course)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;


#------------------------------------------------------------
#-- Contraintes
#------------------------------------------------------------
ALTER TABLE Courses
	ADD FOREIGN KEY (fk_user) REFERENCES Utilisateurs(id_user)
        ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE Courses
        ADD FOREIGN KEY (fk_lieu) REFERENCES Lieux(id_lieu)
        ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE Balises
        ADD FOREIGN KEY (fk_course) REFERENCES Courses(id_course)
        ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE Realise
        ADD FOREIGN KEY (fk_user) REFERENCES Utilisateurs(id_user)
        ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE Realise
        ADD FOREIGN KEY (fk_course) REFERENCES Courses(id_course)
        ON UPDATE CASCADE ON DELETE CASCADE;

#------------------------------------------------------------
#-- Données
#------------------------------------------------------------
INSERT INTO `Lieux` (`id_lieu`, `nom`, `description`, `longitude`, `latitude`, `adresse`, `cp`, `ville`) VALUES
(1, 'Rodez', 'Parc de Vabres', 44.4026478, 2.5658006999999543, 'Vabre', '12850', 'Onet-le-Château');

INSERT INTO `Utilisateurs` (`id_user`, `login`, `password`, `nom`, `prenom`, `mail`, `age`, `genre`) VALUES (NULL, 'root', '$2y$10$h4vkidOHWnaMXNXfaDNUxOQv4xGDkhKa2eX/mBNgwQq0/hYFS7BAe', 'USER', 'user', 'user@mail.fr', '20', 'M');

INSERT INTO `Courses` (`id_course`, `nom`, `type`, `debut`, `fin`, `tempsImparti`, `fk_lieu`, `fk_user`) VALUES (NULL, 'La ruthénoise', 'Courses', '2018-04-01 00:00:00', '2018-04-30 00:00:00', '02:00:00', '1', '1');
