<?php
/** @package    Cargo::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * CustomerMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the CustomerDAO to the customer datastore.
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
class CustomerMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Customer object
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
			$fm["Id"] = new FieldMap("Id","customer","c_id",true,FM_TYPE_INT,10,null,true);
			$fm["Name"] = new FieldMap("Name","customer","c_name",false,FM_TYPE_VARCHAR,45,null,false);
			$fm["LastLogin"] = new FieldMap("LastLogin","customer","c_last_login",false,FM_TYPE_TIMESTAMP,null,null,false);
			$fm["Company"] = new FieldMap("Company","customer","c_company",false,FM_TYPE_VARCHAR,45,null,false);
			$fm["City"] = new FieldMap("City","customer","c_city",false,FM_TYPE_VARCHAR,45,null,false);
			$fm["Level"] = new FieldMap("Level","customer","c_level",false,FM_TYPE_ENUM,array("Standard","Premium"),"Standard",false);
			$fm["Error"] = new FieldMap("Error","customer","c_error",false,FM_TYPE_UNKNOWN,5,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Customer object
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
			$km["p_customer"] = new KeyMap("p_customer", "Id", "Package", "CustomerId", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
		}
		return $km;
	}

}

?>