
CREATE TABLE IF NOT EXISTS  `PREFIX_my_expeditions_159357` (
	`id_exp` INT(11) NOT NULL AUTO_INCREMENT,
	`date_exp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`date_mail_amana` DATETIME NULL DEFAULT NULL,
	`date_mail_client` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id_exp`)
);

CREATE TABLE IF NOT EXISTS  `PREFIX_my_exp_cmd_159357` (
	`id_order` INT(10) NOT NULL,
	`id_exp` INT(11) NOT NULL,
	`nbr_colis` INT(11) NOT NULL DEFAULT '1',
	`is_valid` TINYINT(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`id_order`, `id_exp`)
);

alter table PREFIX_store  
add column  is_default Bool NOT NULL DEFAULT FALSE,
add column is_actif Bool NOT NULL DEFAULT FALSE;

ALTER TABLE PREFIX_order_carrier MODIFY tracking_number TEXT;


CREATE TABLE `PREFIX_my_cdPost_159357` (
	`id_cd` INT(11) NOT NULL AUTO_INCREMENT,
	`from_` TEXT NOT NULL,
	`to_` TEXT,
	`operation_` varchar(3) NOT NULL ,
	`id_store` INT NOT NULL,
	PRIMARY KEY (`id_cd`)
);

create table  IF NOT EXISTS PREFIX_my_amana_cd_159357 (
libelle varchar(255) NOT NULL,
cd varchar(255) NOT NULL,
PRIMARY KEY (libelle)
);

INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AGADIR-IDA OU TANANE','145 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AIN JOHRA','15422 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AIN SBIT','15152 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AIT BAHA','27 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AIT BELKACEM','15425 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AITI CHOU','15124 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AIT IKKOU','15052 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AIT MIMOUNE','15023 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AIT OURIBEL','15024 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AL HAOUZ','137 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AL HOCEIMA','138 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AOUSSERD','74002 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('ASSA-ZAG','154 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('AZILAL','139 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BENI MELLAL','123 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BENSLIMANE','117 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BERKANE','146 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BNI YAKHLEF','95 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BOUJDOUR','155 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BOULEMANE','124 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BOUQACHMIR','15122 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BOUSKOURA','27182 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BOUZNIKA','16 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('BRACHOUA','15153 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('CASABLANCA','118 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('CHEFCHAOUEN','125 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('CHICHAOUA','140 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('DAR BOUAZZA','50 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('EL HAJEB','126 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('EL KELÂA DES SRAGHNA','141 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('EL-JADIDA','127 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('ERRACHIDIA','142 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('ESSAOUIRA','157 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('ES-SEMARA','72000 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('EZZHILIGA','15154 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('FES','114 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('FIGUIG','158 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('GUELMIM','159 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('HOUDERRANE','15073 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('IFRANE','128 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('INEZGANE-AIT MELLOUL','32 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('JEMAAT MOUL BLAD','15173 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('JERADA','148 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('KENITRA','119 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('KHEMISSET','120 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('KHENIFRA','129 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('KHOURIBGA','130 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('LAAYOUNE','160 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('LAGHOUALEM','15172 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('LARACHE','131 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('M QAM TOLBA','15424 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('MAAZIZ','15053 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('MAJMAA TOLBA','15032 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('MARCHOUCH','15174 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('MARRAKECH','115 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('MEDIOUNA','52 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('MEKNES','116 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('MOHAMMEDIA','121 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('MOULAY DRISS AGHBAL','15072 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('MOULAY YACOUB','63 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('NADOR','149 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('NOUACEUR','27000 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('OUAJDA-ANGAD','151 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('OUARZAZATE','150 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('OUED ED-DAHAB','73000 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('OULMES','15102 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('Rabat','113 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SAFI','143 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SALE','10 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SEFROU','132 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SETTAT','133 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SFASSIF','15004 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SIDI ABDERRAZAK','15202 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SIDI ALLAL EL BAHRAOUI','15252 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SIDI ALLAL LAMSADDER','15005 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SIDI BOUKHALKHAL','15273 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SIDI EL GHANDOUR','15033 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SIDI KACEM','122 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SIDI MOUSSA BEN ALI','28814 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('SKHIRATE-TEMARA','11 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TANGER','134 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TAN-TAN','2 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TAOUNATE','135 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TAOURIRT','44 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TAROUDANNT','152 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TATA','3 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TAZA','144 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TETOUAN','136 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TIDDAS','15352 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('TIZNIT','153 ');
INSERT INTO PREFIX_my_amana_cd_159357 VALUES ('ZAGORA','47900 ');



CREATE TABLE `PREFIX_my_amana_zones_159357` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`zone_name` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `PREFIX_my_amana_zones_between_159357` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_zone` INT(11) NOT NULL,
	`from_` INT(11) NOT NULL,
	`to_` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `PREFIX_my_amana_zones_in_159357` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`zip_code` INT(11) NOT NULL DEFAULT '0',
	`id_zone` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
);


