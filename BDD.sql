SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `BaliseResultats`, `Resultats`, `BaliseCourses`, `Courses`, `Utilisateurs`;
SET FOREIGN_KEY_CHECKS=1;



#------------------------------------------------------------
# Table: Utilisateurs
#------------------------------------------------------------

CREATE TABLE Utilisateurs (
        id_user   	INTEGER AUTO_INCREMENT NOT NULL,
        login     	VARCHAR (255) NOT NULL,
        password  	VARCHAR (255) NOT NULL,
        nom       	VARCHAR (255) NOT NULL,
        prenom    	VARCHAR (255) NOT NULL,
        mail      	VARCHAR (255) NOT NULL,
        dateNaissance	DATE NOT NULL,
        sexe     	CHAR (1) NOT NULL,
        PRIMARY KEY (id_user)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;


#------------------------------------------------------------
# Table: Courses
#------------------------------------------------------------

CREATE TABLE Courses (
        id_course    INTEGER AUTO_INCREMENT NOT NULL,
        nom          VARCHAR (255) NOT NULL,
        description  TEXT,
        prive        BOOLEAN NOT NULL,
        type         VARCHAR (255) NOT NULL,
        debut        DATETIME NOT NULL,
        fin          DATETIME,
        tempsImparti TIME,
        penalite     TIME NOT NULL,
        fk_user      INT NOT NULL,
        PRIMARY KEY (id_course)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# Table: BaliseCourses
#------------------------------------------------------------

CREATE TABLE BaliseCourses (
        id_baliseCourse INTEGER AUTO_INCREMENT NOT NULL,
        nom       VARCHAR (255) NOT NULL,
        numero    INTEGER NOT NULL,
        valeur    INTEGER,
        longitude DECIMAL(9,6),
        latitude  DECIMAL(9,6),
        fk_course INT NOT NULL,
        PRIMARY KEY (id_baliseCourse)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;


#------------------------------------------------------------
# Table: Resultats
#------------------------------------------------------------

CREATE TABLE Resultats (
        id_resultat INTEGER AUTO_INCREMENT NOT NULL,
        debut       DATETIME NOT NULL,
        fin         DATETIME,
        score       INTEGER,
        fk_user     INT NOT NULL,
        fk_course   INT NOT NULL,
        PRIMARY KEY (id_resultat)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;


#------------------------------------------------------------
# Table: BaliseResultats
#------------------------------------------------------------

CREATE TABLE BaliseResultats (
        id_baliseResultat INTEGER AUTO_INCREMENT  NOT NULL,
        tempsInter         TIME NOT NULL,
        fk_resultat        INT NOT NULL,
        fk_baliseCourse   INT NOT NULL,
        PRIMARY KEY (id_baliseResultat)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;


#------------------------------------------------------------
#-- Contraintes
#------------------------------------------------------------
ALTER TABLE Courses
	ADD FOREIGN KEY (fk_user) REFERENCES Utilisateurs(id_user)
        ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE BaliseCourses
        ADD FOREIGN KEY (fk_course) REFERENCES Courses(id_course)
        ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE Resultats
        ADD FOREIGN KEY (fk_user) REFERENCES Utilisateurs(id_user)
        ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE Resultats
        ADD FOREIGN KEY (fk_course) REFERENCES Courses(id_course)
        ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE BaliseResultats
        ADD FOREIGN KEY (fk_resultat) REFERENCES Resultats(id_resultat)
        ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE BaliseResultats
        ADD FOREIGN KEY (fk_baliseCourse) REFERENCES BaliseCourses(id_baliseCourse)
        ON UPDATE CASCADE ON DELETE CASCADE;

#------------------------------------------------------------
#-- Données
#------------------------------------------------------------
INSERT INTO `Utilisateurs` (`id_user`, `login`, `password`, `nom`, `prenom`, `mail`, `dateNaissance`, `sexe`) VALUES (NULL, 'root', '$2y$10$h4vkidOHWnaMXNXfaDNUxOQv4xGDkhKa2eX/mBNgwQq0/hYFS7BAe', 'USER', 'user', 'user@mail.fr', '2000-04-05', 'M');

INSERT INTO `Courses` (`id_course`, `nom`, `description`, `prive`, `type`, `debut`, `fin`, `tempsImparti`, `penalite`, `fk_user`) VALUES (NULL, 'La ruthénoise', 'Rendez-vous à Layoule.', 0, 'S', '2018-04-01 00:00:00', NULL, NULL, '00:05:00', '1');

INSERT INTO `BaliseCourses` (`id_baliseCourse`, `nom`, `numero`, `valeur`, `longitude`, `latitude`, `fk_course`) VALUES (NULL, 'Départ', '0', NULL, NULL, NULL, '1'), (NULL, 'Balise n°1', '1', NULL, NULL, NULL, '1'), (NULL, 'Balise n°2', '2', NULL, NULL, NULL, '1'), (NULL, 'Fin', '3', NULL, NULL, NULL, '1');

INSERT INTO `Resultats` (`id_resultat`, `debut`, `fin`, `score`, `fk_user`, `fk_course`) VALUES (NULL, '2018-04-09 14:00:00', '2018-04-09 15:00:00', NULL, '1', '1');

INSERT INTO `BaliseResultats` (`id_baliseResultat`, `tempsInter`, `fk_resultat`, `fk_baliseCourse`) VALUES (NULL, '00:00:00', '1', '1'), (NULL, '00:20:00', '1', '2'), (NULL, '00:20:00', '1', '3'), (NULL, '00:20:00', '1', '4');
