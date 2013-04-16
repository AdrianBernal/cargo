<?php
/** @package    Cargo::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * PurchaseMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the PurchaseDAO to the purchase datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package Cargo::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class PurchaseMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Purchase object
	 *
	 * @access public
	 * @return array of FieldMaps
	 */
	public static function GetFieldMaps()
	{
		static $fm = null;
		if ($fm == null)
		{
			$fm = Array();
			$fm["Id"] = new FieldMap("Id","purchase","p_id",true,FM_TYPE_INT,11,null,true);
			$fm["StatusCodeId"] = new FieldMap("StatusCodeId","purchase","p_status_code_id",false,FM_TYPE_VARCHAR,3,null,false);
			$fm["Quantity"] = new FieldMap("Quantity","purchase","p_quantity",false,FM_TYPE_INT,11,"1",false);
			$fm["Description"] = new FieldMap("Description","purchase","p_description",false,FM_TYPE_VARCHAR,45,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Purchase object
	 *
	 * @access public
	 * @return array of KeyMaps
	 */
	public static function GetKeyMaps()
	{
		static $km = null;
		if ($km == null)
		{
			$km = Array();
			$km["p_status_code"] = new KeyMap("p_status_code", "StatusCodeId", "StatusCode", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return $km;
	}

}

?>