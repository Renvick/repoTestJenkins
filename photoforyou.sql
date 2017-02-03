-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 05 Juin 2014 à 11:06
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `photoforyou`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `majNbPhotos`()
BEGIN

declare id, compte, vide INT;

declare curseur cursor for
	SELECT idPhotographe FROM photographes;
declare continue handler for not found set vide = 1;

set vide=0;
set compte=0;

open curseur;
maboucle: loop
	fetch curseur into id;
	if vide=1 then
		leave maboucle;
	end if;
	SELECT COUNT(*) INTO compte FROM photos p, photographes ph
		WHERE p.idPhotographe=ph.idPhotographe
		AND ph.idPhotographe=id;
	UPDATE photographes SET nbPhotos=compte WHERE idPhotographe=id;
end loop;
close curseur;

END$$

--
-- Fonctions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `calculNbPhotoUser`(idUser INT, typeUser INT) RETURNS int(11)
    DETERMINISTIC
BEGIN

declare compte, vide INT;

set vide=0;
set compte=0;

CASE typeUser
	WHEN 1 THEN
		SELECT nbPhotosAchetees INTO compte FROM clients
		WHERE idClient=idUser;
	ELSE
		SELECT nbPhotos INTO compte FROM photographes
		WHERE idPhotographe=idUser;
END CASE;

RETURN compte;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `calculNote`(id INT) RETURNS int(11)
    DETERMINISTIC
BEGIN
	DECLARE note, nbVote, noteFinale INT;

	SELECT notePhoto INTO note FROM photos WHERE idPhoto=id;
	SELECT nbNotePhoto INTO nbVote FROM photos WHERE idPhoto=id;

	IF nbVote=0 THEN
		SET noteFinale=0;
	ELSE
		set noteFinale=note/nbVote;
	END IF;

RETURN noteFinale;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `client_sans_credit`() RETURNS int(11)
    DETERMINISTIC
BEGIN

declare zeroCredit INT;
SELECT COUNT(*) FROM clients WHERE nbCredit= 0 INTO zeroCredit ;

RETURN zeroCredit;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `InitCap`(chaine varchar(20)) RETURNS varchar(20) CHARSET utf8 COLLATE utf8_bin
    DETERMINISTIC
BEGIN

set chaine = CONCAT(UPPER(SUBSTR(chaine, 1, 1)),
					LOWER(SUBSTR(chaine, 2)));

RETURN chaine;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `total_poids`() RETURNS int(11)
    DETERMINISTIC
BEGIN

declare taille, total, vide int;
declare curseur cursor for
	SELECT taillePhoto FROM photos;
declare continue handler for not found set vide = 1;

set vide=0;
set total=0;

open curseur;
maboucle: loop
	fetch curseur into taille;
	if vide=1 then
		leave maboucle;
	end if;
	set total=total+taille;
end loop;
close curseur;

RETURN total;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `acheter`
--

CREATE TABLE IF NOT EXISTS `acheter` (
  `idPhoto` int(11) NOT NULL,
  `idClient` int(11) NOT NULL,
  PRIMARY KEY (`idPhoto`,`idClient`),
  KEY `fk_idclient_idx` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `acheter`
--

INSERT INTO `acheter` (`idPhoto`, `idClient`) VALUES
(1, 4),
(2, 4),
(3, 4);

--
-- Déclencheurs `acheter`
--
DROP TRIGGER IF EXISTS `acheter_AINS`;
DELIMITER //
CREATE TRIGGER `acheter_AINS` AFTER INSERT ON `acheter`
 FOR EACH ROW BEGIN
	UPDATE clients SET nbPhotosAchetees=nbPhotosAchetees+1 WHERE idClient=new.idClient;

	UPDATE photographes, photos SET nbPhotosVendues=nbPhotosVendues+1 
		WHERE new.idPhoto=photos.idPhoto
		AND photos.idPhotographe=photographes.idPhotographe;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `idClient` int(11) NOT NULL AUTO_INCREMENT,
  `nomClient` varchar(45) COLLATE utf8_bin NOT NULL,
  `prenomClient` varchar(45) COLLATE utf8_bin NOT NULL,
  `pseudoClient` varchar(45) COLLATE utf8_bin NOT NULL,
  `mailClient` varchar(45) COLLATE utf8_bin NOT NULL,
  `mdpClient` varchar(50) COLLATE utf8_bin NOT NULL,
  `nbCredit` smallint(6) NOT NULL DEFAULT '0',
  `nbPhotosAchetees` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idClient`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`idClient`, `nomClient`, `prenomClient`, `pseudoClient`, `mailClient`, `mdpClient`, `nbCredit`, `nbPhotosAchetees`) VALUES
(4, 'Expert', 'Adrien', 'AExpert', 'expert@adrien', '7e240de74fb1ed08fa08d38063f6a6a91462a815', 266, 3);

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `idMenu` int(1) NOT NULL AUTO_INCREMENT,
  `NomMenu` varchar(20) COLLATE utf8_bin NOT NULL,
  `Lien` varchar(20) COLLATE utf8_bin NOT NULL,
  `DispoMenu` tinyint(1) NOT NULL,
  PRIMARY KEY (`idMenu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

--
-- Contenu de la table `menu`
--

INSERT INTO `menu` (`idMenu`, `NomMenu`, `Lien`, `DispoMenu`) VALUES
(1, 'Acheter des credits', 'achatcredits.php', 1),
(2, 'Acheter des photos', 'achatphotos.php', 1),
(3, 'Nous contacter', 'contacts.php', 1),
(4, 'Connexion', 'connexion.php', 1),
(5, 'S''inscrire', 'inscription.php', 1),
(11, 'Deconnexion', 'deconnexion.php', 0);

-- --------------------------------------------------------

--
-- Structure de la table `photographes`
--

CREATE TABLE IF NOT EXISTS `photographes` (
  `idPhotographe` int(11) NOT NULL AUTO_INCREMENT,
  `nomPhotographe` varchar(45) COLLATE utf8_bin NOT NULL,
  `prenomPhotographe` varchar(45) COLLATE utf8_bin NOT NULL,
  `pseudoPhotographe` varchar(45) COLLATE utf8_bin NOT NULL,
  `mailPhotographe` varchar(45) COLLATE utf8_bin NOT NULL,
  `mdpPhotographe` varchar(45) COLLATE utf8_bin NOT NULL,
  `nbPhotos` smallint(6) NOT NULL DEFAULT '0',
  `nbPhotosVendues` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idPhotographe`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Contenu de la table `photographes`
--

INSERT INTO `photographes` (`idPhotographe`, `nomPhotographe`, `prenomPhotographe`, `pseudoPhotographe`, `mailPhotographe`, `mdpPhotographe`, `nbPhotos`, `nbPhotosVendues`) VALUES
(1, 'Dash', 'Rainbow', 'Speedy', 'rainbow@mlp', '7e240de74fb1ed08fa08d38063f6a6a91462a815', 3, 2),
(2, 'Gouchet', 'Pierre', 'Syn', 'pierre@gouchet', '7e240de74fb1ed08fa08d38063f6a6a91462a815', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `idPhoto` int(11) NOT NULL AUTO_INCREMENT,
  `nomPhoto` varchar(45) COLLATE utf8_bin NOT NULL,
  `taillePhoto` int(11) NOT NULL,
  `heightPhoto` smallint(6) NOT NULL,
  `widthPhoto` smallint(6) NOT NULL,
  `typePhoto` varchar(10) COLLATE utf8_bin NOT NULL,
  `idPhotographe` int(11) NOT NULL,
  `lienPhoto` varchar(200) COLLATE utf8_bin NOT NULL,
  `prixPhoto` smallint(6) NOT NULL DEFAULT '0',
  `notePhoto` int(11) DEFAULT '0',
  `nbNotePhoto` int(11) DEFAULT '0',
  PRIMARY KEY (`idPhoto`),
  KEY `fk_idPhotographe_idx` (`idPhotographe`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Contenu de la table `photos`
--

INSERT INTO `photos` (`idPhoto`, `nomPhoto`, `taillePhoto`, `heightPhoto`, `widthPhoto`, `typePhoto`, `idPhotographe`, `lienPhoto`, `prixPhoto`, `notePhoto`, `nbNotePhoto`) VALUES
(1, 'Totodile', 32767, 591, 591, 'jpg', 1, 'images/photos/Dash_Rainbow_Speedy/Totodile.jpg', 10, 400, 102),
(2, 'Louis', 32767, 248, 158, 'png', 1, 'images/photos/Dash_Rainbow_Speedy/Louis.png', 5, 90, 31),
(3, 'Meow', 32767, 397, 443, 'png', 2, 'images/photos/Gouchet_Pierre_Syn/Meow.png', 50, 4, 1),
(5, 'LS', 9263, 149, 160, 'png', 1, 'images/photos/Dash_Rainbow_Speedy/LS.png', 888, 4, 2);

--
-- Déclencheurs `photos`
--
DROP TRIGGER IF EXISTS `photos_AINS`;
DELIMITER //
CREATE TRIGGER `photos_AINS` AFTER INSERT ON `photos`
 FOR EACH ROW BEGIN
	UPDATE photographes SET nbPhotos=nbPhotos+1 WHERE idPhotographe=new.idPhotographe;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `photos_AUPD`;
DELIMITER //
CREATE TRIGGER `photos_AUPD` AFTER UPDATE ON `photos`
 FOR EACH ROW BEGIN
	IF new.idPhotographe!=old.idPhotographe THEN
		UPDATE photographes SET nbPhotos=nbPhotos+1 WHERE idPhotographe=new.idPhotographe;
		UPDATE photographes SET nbPhotos=nbPhotos-1 WHERE idPhotographe=old.idPhotographe;
	END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `photos_BDEL`;
DELIMITER //
CREATE TRIGGER `photos_BDEL` BEFORE DELETE ON `photos`
 FOR EACH ROW BEGIN
	UPDATE photographes SET nbPhotos=nbPhotos-1 WHERE idPhotographe=old.idPhotographe;
END
//
DELIMITER ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `acheter`
--
ALTER TABLE `acheter`
  ADD CONSTRAINT `fk_idclient` FOREIGN KEY (`idClient`) REFERENCES `clients` (`idClient`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_idphoto` FOREIGN KEY (`idPhoto`) REFERENCES `photos` (`idPhoto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `fk_idPhotographe` FOREIGN KEY (`idPhotographe`) REFERENCES `photographes` (`idPhotographe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
