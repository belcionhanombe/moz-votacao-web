
-- -----------------------------------------------------
-- Schema mozvotacao
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mozvotacao` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema new_schema1
-- -----------------------------------------------------
USE `mozvotacao`;
-- -----------------------------------------------------

-- Table `mozvotacao`.`Partidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Partidos` (
  `idPartidos` INT NOT NULL,
  `Nome` VARCHAR(60) UNIQUE NOT  NULL,
  PRIMARY KEY (`idPartidos`)
)
ENGINE = InnoDB;
-- --
---------------------------------------------------
-- Table `mozvotacao`.`Provincias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Provincias` (
  `idProvincias` INT NOT NULL,
  `Nome` VARCHAR(45) UNIQUE NOT NULL,
  PRIMARY KEY (`idProvincias`)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Distritos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Distritos` (
  `idDistritos` INT NOT NULL,
  `Nome` VARCHAR(45) UNIQUE NOT NULL,
  `Municipio` TINYINT NOT NULL,
  `Provincias_idProvincias` INT NOT NULL,
  PRIMARY KEY (`idDistritos`, `Provincias_idProvincias`),
    FOREIGN KEY (`Provincias_idProvincias`)
    REFERENCES `mozvotacao`.`Provincias` (`idProvincias`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mozvotacao`.`LocalDeVotacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`LocalDeVotacao` (
  `idLocalDeVotacao` INT NOT NULL,
  `Nome` VARCHAR(45) NULL,
  `Distritos_idDistritos` INT NOT NULL,
  PRIMARY KEY (`idLocalDeVotacao`),
    FOREIGN KEY (`Distritos_idDistritos`)
    REFERENCES `mozvotacao`.`Distritos` (`idDistritos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Mesas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Mesas` (
  `idMesas` VARCHAR(40) NOT NULL,
  `LocalDeVotacao_idLocalDeVotacao` INT NOT NULL,
  `contador` INT NULL,
  PRIMARY KEY (`idMesas`),
    FOREIGN KEY (`LocalDeVotacao_idLocalDeVotacao`)
    REFERENCES `mozvotacao`.`LocalDeVotacao` (`idLocalDeVotacao`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mozvotacao`.`Votos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Votos` (
  `Mesas_idMesas` VARCHAR(40) NOT NULL,
  `idVotos` INT NOT NULL,
  `Partidos_idPartidos` INT NOT NULL,
  `contador` INT NULL,
  PRIMARY KEY (`Mesas_idMesas`),
    FOREIGN KEY (`Partidos_idPartidos`)
    REFERENCES `mozvotacao`.`Partidos` (`idPartidos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Votos_Mesas1`
    FOREIGN KEY (`Mesas_idMesas`)
    REFERENCES `mozvotacao`.`Mesas` (`idMesas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Eleitores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Eleitores` (
  `BI` VARCHAR(14) NOT NULL,
  `PrimeiroNome` VARCHAR(45) NOT NULL,
  `SegundoNome` VARCHAR(45) NOT NULL,
  `DataNascimento` DATE NOT NULL,
  `Endereco` VARCHAR(45) NOT NULL,
  `Estado` INT(1) NOT NULL,
  `contacto` INT(9) NULL,
  `Mesas_idMesas` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`BI`, `Mesas_idMesas`),
    FOREIGN KEY (`Mesas_idMesas`)
    REFERENCES `mozvotacao`.`Mesas` (`idMesas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `mozvotacao`.`Administradores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Administradores` (
  `idAdministradores` INT NOT NULL,
  `Mesas_idMesas` VARCHAR(40) NULL,
  `primeiroNome` VARCHAR(45) NOT NULL,
  `SegundoNome` VARCHAR(45) NOT NULL,
  `BI` VARCHAR(45) NOT NULL,
  `Nivel` VARCHAR(45) NOT NULL,
  `Distritos_idDistritos` INT NULL,
  `senha` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idAdministradores`),
    FOREIGN KEY (`Mesas_idMesas`)
    REFERENCES `mozvotacao`.`Mesas` (`idMesas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Administradores_Distritos1`
    FOREIGN KEY (`Distritos_idDistritos`)
    REFERENCES `mozvotacao`.`Distritos` (`idDistritos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`PartidosDistritos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`PartidosDistritos` (
  `Partidos_idPartidos` INT NOT NULL,
  `Distritos_idDistritos` INT NOT NULL,
  `idPartidosDistritos` VARCHAR(45) NOT NULL,
 
  PRIMARY KEY (`idPartidosDistritos`),
    FOREIGN KEY (`Partidos_idPartidos`)
    REFERENCES `mozvotacao`.`Partidos` (`idPartidos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    FOREIGN KEY (`Distritos_idDistritos`)
    REFERENCES `mozvotacao`.`Distritos` (`idDistritos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`TipoEleicoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`TipoEleicoes` (
  `idTipoEleicoes` INT NOT NULL,
  `tipoEleicoes` TINYINT UNSIGNED NULL DEFAULT 0,
  `ano` VARCHAR(20) NOT NULL,
  `Distritos_idDistritos` INT NOT NULL,

  PRIMARY KEY (`idTipoEleicoes`),
    FOREIGN KEY (`Distritos_idDistritos`)
    REFERENCES `mozvotacao`.`Distritos` (`idDistritos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Pacotes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Pacotes` (
  `idPacotes` INT ZEROFILL NOT NULL,
  `Mesas_idMesas` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`idPacotes`),
    FOREIGN KEY (`Mesas_idMesas`)
    REFERENCES `mozvotacao`.`Mesas` (`idMesas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Boletins`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Boletins` (
  `idBoletins` INT NOT NULL,
  `Pacotes_idPacotes` INT ZEROFILL NOT NULL,
  PRIMARY KEY (`idBoletins`, `Pacotes_idPacotes`),
  INDEX `fk_Boletins_Pacotes1_idx` (`Pacotes_idPacotes` ASC) VISIBLE,
  CONSTRAINT `fk_Boletins_Pacotes1`
    FOREIGN KEY (`Pacotes_idPacotes`)
    REFERENCES `mozvotacao`.`Pacotes` (`idPacotes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`config` (
  `idconfig` INT NOT NULL,
  `limite` INT NOT NULL,
  `TipoEleicoes_idTipoEleicoes` INT NOT NULL,
  `Administradores_idAdministradores` INT NOT NULL,
  PRIMARY KEY (`idconfig`),
    FOREIGN KEY (`TipoEleicoes_idTipoEleicoes`)
    REFERENCES `mozvotacao`.`TipoEleicoes` (`idT+ipoEleicoes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    FOREIGN KEY (`Administradores_idAdministradores`)
    REFERENCES `mozvotacao`.`Administradores` (`idAdministradores`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)

    ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;





parte idDistritos


-- -----------------------------------------------------
-- Schema mozvotacao
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mozvotacao` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema new_schema1
-- -----------------------------------------------------
USE `mozvotacao`;
-- -----------------------------------------------------
-- Table `mozvotacao`.`Partidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Partidos` (
  `idPartidos` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(60) UNIQUE NOT  NULL,
  PRIMARY KEY (`idPartidos`)
)
ENGINE = InnoDB;
-- --
---------------------------------------------------
-- Table `mozvotacao`.`Provincias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Provincias` (
  `idProvincias` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) UNIQUE NOT NULL,
  PRIMARY KEY (`idProvincias`)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Distritos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Distritos` (
  `idDistritos` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) UNIQUE NOT NULL,
  `Municipio` TINYINT NOT NULL,
  `Provincias_idProvincias` INT NOT NULL,
  PRIMARY KEY (`idDistritos`),
    FOREIGN KEY (`Provincias_idProvincias`)
    REFERENCES `mozvotacao`.`Provincias` (`idProvincias`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mozvotacao`.`LocalDeVotacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`LocalDeVotacao` (
  `idLocalDeVotacao` INT NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NULL,
  `Distritos_idDistritos` INT NOT NULL,
  PRIMARY KEY (`idLocalDeVotacao`),
    FOREIGN KEY (`Distritos_idDistritos`)
    REFERENCES `mozvotacao`.`Distritos` (`idDistritos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Mesas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Mesas` (
  `idMesas` VARCHAR(40) NOT NULL AUTO_INCREMENT,
  `localDeVotacao_idLocalDeVotacao` INT NOT NULL,
  `contador` INT NULL,
  PRIMARY KEY (`idMesas`),
    FOREIGN KEY (`LocalDeVotacao_idLocalDeVotacao`)
    REFERENCES `mozvotacao`.`LocalDeVotacao` (`idLocalDeVotacao`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mozvotacao`.`Votos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Votos` (
  `Mesas_idMesas` VARCHAR(40) NOT NULL AUTO_INCREMENT,
  `idVotos` INT NOT NULL,
  `Partidos_idPartidos` INT NOT NULL,
  `contador` INT NULL,
  PRIMARY KEY (`Mesas_idMesas`),
    FOREIGN KEY (`Partidos_idPartidos`)
    REFERENCES `mozvotacao`.`Partidos` (`idPartidos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Votos_Mesas1`
    FOREIGN KEY (`Mesas_idMesas`)
    REFERENCES `mozvotacao`.`Mesas` (`idMesas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Eleitores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Eleitores` (
  `BI` VARCHAR(14) NOT NULL UNIQUE,
  `PrimeiroNome` VARCHAR(45) NOT NULL,
  `SegundoNome` VARCHAR(45) NOT NULL,
  `DataNascimento` DATE NOT NULL,
  `Endereco` VARCHAR(45) NOT NULL,
  `Estado` INT(1) NOT NULL,
  `contacto` INT(9) NULL,
  `Mesas_idMesas` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`BI`, `Mesas_idMesas`),
    FOREIGN KEY (`Mesas_idMesas`)
    REFERENCES `mozvotacao`.`Mesas` (`idMesas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `mozvotacao`.`Administradores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Administradores` (
  `idAdministradores` INT NOT NULL AUTO_INCREMENT,
  `Mesas_idMesas` VARCHAR(40) NULL,
  `primeiroNome` VARCHAR(45) NOT NULL,
  `SegundoNome` VARCHAR(45) NOT NULL,
  `BI` VARCHAR(45) NOT NULL,
  `Nivel` VARCHAR(45) NOT NULL,
  `Distritos_idDistritos` INT NULL,
  `senha` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idAdministradores`),
    FOREIGN KEY (`Mesas_idMesas`)
    REFERENCES `mozvotacao`.`Mesas` (`idMesas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Administradores_Distritos1`
    FOREIGN KEY (`Distritos_idDistritos`)
    REFERENCES `mozvotacao`.`Distritos` (`idDistritos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`PartidosDistritos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`PartidosDistritos` (
  `Partidos_idPartidos` INT NOT NULL ,
  `Distritos_idDistritos` INT NOT NULL,
  `idPartidosDistritos` VARCHAR(45) NOT NULL,
 
  PRIMARY KEY (`idPartidosDistritos`),
    FOREIGN KEY (`Partidos_idPartidos`)
    REFERENCES `mozvotacao`.`Partidos` (`idPartidos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    FOREIGN KEY (`Distritos_idDistritos`)
    REFERENCES `mozvotacao`.`Distritos` (`idDistritos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`TipoEleicoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`TipoEleicoes` (
  `idTipoEleicoes` INT NOT NULL,
  `tipoEleicoes` TINYINT UNSIGNED NULL DEFAULT 0,
  `ano` VARCHAR(20) NOT NULL,
  `Distritos_idDistritos` INT NOT NULL,

  PRIMARY KEY (`idTipoEleicoes`),
    FOREIGN KEY (`Distritos_idDistritos`)
    REFERENCES `mozvotacao`.`Distritos` (`idDistritos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Pacotes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Pacotes` (
  `idPacotes` INT NOT NULL AUTO_INCREMENT,
  `Mesas_idMesas` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`idPacotes`),
    FOREIGN KEY (`Mesas_idMesas`)
    REFERENCES `mozvotacao`.`Mesas` (`idMesas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`Boletins`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`Boletins` (
  `idBoletins` INT NOT NULL AUTO_INCREMENT,
  `Pacotes_idPacotes` INT NOT NULL,
  PRIMARY KEY (`idBoletins`, `Pacotes_idPacotes`),
  INDEX `fk_Boletins_Pacotes1_idx` (`Pacotes_idPacotes` ASC) VISIBLE,
  CONSTRAINT `fk_Boletins_Pacotes1`
    FOREIGN KEY (`Pacotes_idPacotes`)
    REFERENCES `mozvotacao`.`Pacotes` (`idPacotes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mozvotacao`.`config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mozvotacao`.`config` (
  `idconfig` INT NOT NULL AUTO_INCREMENT,
  `limite` INT NOT NULL,
  `TipoEleicoes_idTipoEleicoes` INT NOT NULL,
  `Administradores_idAdministradores` INT NOT NULL,
  PRIMARY KEY (`idconfig`),
    FOREIGN KEY (`TipoEleicoes_idTipoEleicoes`)
    REFERENCES `mozvotacao`.`TipoEleicoes` (`idT+ipoEleicoes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    FOREIGN KEY (`Administradores_idAdministradores`)
    REFERENCES `mozvotacao`.`Administradores` (`idAdministradores`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)

    ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;