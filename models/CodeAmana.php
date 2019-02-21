<?php

class CodeAmana {
	public static function getAll(){
		$sql = "SELECT  * 
		FROM "._DB_PREFIX_."my_amana_cd_159357 ";
		return Db::getInstance()->ExecuteS($sql);
	}
	
	public static function addCode($libelle,$cd){

		Db::getInstance()->insert('my_amana_cd_159357', array(
			'libelle' => $libelle,
			'cd' => $cd,
		));
			
	}
	public static function removeCode($libelle){

		Db::getInstance()->delete('my_amana_cd_159357', 'libelle=\''.$libelle.'\'');
			
	}
	
}
?>