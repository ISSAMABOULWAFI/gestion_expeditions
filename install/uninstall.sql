DROP TABLE IF EXISTS `PREFIX_my_exp_cmd_159357`;
DROP TABLE IF EXISTS `PREFIX_my_expeditions_159357`;

alter table PREFIX_store  
drop column  is_default,
drop column is_actif;

DROP TABLE IF EXISTS `PREFIX_my_cdPost_159357`;
DROP TABLE IF EXISTS `PREFIX_my_amana_cd_159357`;
DROP TABLE IF EXISTS `PREFIX_my_amana_zones_159357`;
DROP TABLE IF EXISTS `PREFIX_my_amana_zones_between_159357`;
DROP TABLE IF EXISTS `PREFIX_my_amana_zones_in_159357`;

