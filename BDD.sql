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
        penalite     TIME,
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
        qrcode    TEXT NOT NULL,
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
        temps             TIME NOT NULL,
        longitude         DECIMAL(9,6),
        latitude          DECIMAL(9,6),
        fk_resultat       INT NOT NULL,
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
INSERT INTO `Utilisateurs` (`id_user`, `login`, `password`, `nom`, `prenom`, `mail`, `dateNaissance`, `sexe`)
VALUES (NULL, 'joevin', '$2y$10$UTIiVNyvWQU7hMnaLFATtuGKJUpYEqmacXoSYkO2OlmTztTBq6IH.', 'SOULENQ', 'Joévin', 'joevin.soulenq@gmail.com', '1996-01-23', 'M'),
       (NULL, 'a', '$2y$10$AeaLszxcEuZphixThYFjVOIOuLlczbQkyPfLgk.BDnUzxd3IEOUpm', 'DELMONTEIL', 'Lucas', 'lucas.delmonteil@outlook.com', '1997-05-19', 'M');

# Score
INSERT INTO `Courses` (`id_course`, `nom`, `description`, `prive`, `type`, `debut`, `fin`, `tempsImparti`, `fk_user`)
VALUES (NULL, 'La ruthénoise', 'Rendez-vous à Layoule.', 0, 'S', '2018-05-05 08:00:00', '2018-08-05 18:00:00', '02:00:00', '1');

INSERT INTO `BaliseCourses` (`id_baliseCourse`, `nom`, `numero`, `valeur`, `longitude`, `latitude`, `qrcode`, `fk_course`)
VALUES (NULL, 'Configuration', '0', NULL, NULL, NULL,
	'{"nom":"La ruthénoise","id":1,"type":"S","deb":"2018-05-05 08:00:00","fin":"2018-08-05 18:00:00","timp":"02:00","bals":[{"num":2,"nom":"CP1","val":100},{"num":3,"nom":"CP2","val":50}]}', '1'),
	(NULL, 'Départ', '1', NULL, 2.580907, 44.353503, '{"id_course":1,"num":1,"nom":"Start"}', '1'),
	(NULL, 'CP1', '2', '100', 2.582133, 44.350259, '{"id_course":1,"num":2,"nom":"CP1","val":100}', '1'),
	(NULL, 'CP2', '3', '50', 2.583035, 44.348046, '{"id_course":1,"num":3,"nom":"CP2","val":50}', '1');

INSERT INTO `Resultats` (`id_resultat`, `debut`, `fin`, `score`, `fk_user`, `fk_course`)
VALUES (NULL, '2018-04-09 14:31:23', '2018-04-09 14:39:00', 150, '1', '1');

INSERT INTO `BaliseResultats` (`id_baliseResultat`, `temps`, `longitude`, `latitude`, `fk_resultat`, `fk_baliseCourse`)
VALUES
(NULL, '00:00:00', NULL, NULL, 1, 1),
(NULL, '00:00:00', 2.580959, 44.353525, 1, 2),
(NULL, '00:03:12', 2.582101, 44.350346, 1, 3),
(NULL, '00:07:47', 2.582998, 44.347994, 1, 4);

# Parcours
INSERT INTO `Courses` (`id_course`, `nom`, `description`, `prive`, `type`, `debut`, `fin`, `penalite`, `fk_user`) VALUES
(NULL, 'L\'aurillacoise', 'Rendez-vous au gymnase de Peyrolles.', 0, 'P', '2018-05-16 10:00:00', '2018-08-15 18:00:00', '00:15:00', 1);

INSERT INTO `BaliseCourses` (`id_baliseCourse`, `nom`, `numero`, `valeur`, `longitude`, `latitude`, `qrcode`, `fk_course`)
VALUES (NULL, 'Configuration', 0, NULL, NULL, NULL,
	'{"nom":"L\'aurillacoise","id":2,"type":"P","deb":"2018-05-16  10:00:00","fin":"2018-08-15  18:00:00","pnlt":"0:15:00","bals":[{"num":2,"nom":"CP1"},{"num":3,"nom":"CP2"}]}', 2),
	(NULL, 'Départ', 1, NULL, 2.457914, 44.938921, '{"id_course":2,"num":1,"nom":"Start"}', 2),
	(NULL, 'CP1', 2, NULL, 2.459169, 44.939606, '{"id_course":2,"num":2,"nom":"CP1"}', 2),
	(NULL, 'CP2', 3, NULL, 2.460001, 44.940778, '{"id_course":2,"num":3,"nom":"CP2"}', 2),
	(NULL, 'Arrivée', 4, NULL, 2.460734, 44.941695, '{"id_course":2,"num":4,"nom":"Stop"}', 2);

INSERT INTO `Resultats` (`id_resultat`, `debut`, `fin`, `fk_user`, `fk_course`)
VALUES (NULL, '2018-05-20 15:10:11', '2018-05-20 15:18:37', '2', '2');

INSERT INTO `BaliseResultats` (`id_baliseResultat`, `temps`, `longitude`, `latitude`, `fk_resultat`, `fk_baliseCourse`)
VALUES
(NULL, '00:00:00', NULL, NULL, 1, 5),
(NULL, '00:00:00', 2.457453, 44.939136, 2, 6),
(NULL, '00:03:27', 2.459304, 44.939772, 2, 7),
(NULL, '00:05:44', 2.459969, 44.94072, 2, 8),
(NULL, '00:08:26', 2.460305, 44.9424, 2, 9);
