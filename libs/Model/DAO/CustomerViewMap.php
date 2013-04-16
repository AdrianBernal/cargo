<?php
/** @package    Cargo::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * CustomerViewMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the CustomerViewDAO to the customer_view datastore.
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
class CustomerViewMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the CustomerView object
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
			$fm["Id"] = new FieldMap("Id","customer_view","c_id",true,FM_TYPE_INT,10,null,false);
			$fm["Name"] = new FieldMap("Name","customer_view","c_name",false,FM_TYPE_VARCHAR,45,null,false);
			$fm["LastLogin"] = new FieldMap("LastLogin","customer_view","c_last_login",false,FM_TYPE_TIMESTAMP,null,null,false);
			$fm["Company"] = new FieldMap("Company","customer_view","c_company",false,FM_TYPE_VARCHAR,45,null,false);
			$fm["City"] = new FieldMap("City","customer_view","c_city",false,FM_TYPE_VARCHAR,45,null,false);
			$fm["Level"] = new FieldMap("Level","customer_view","c_level",false,FM_TYPE_ENUM,array("Standard","Premium"),"Standard",false);
			$fm["Error"] = new FieldMap("Error","customer_view","c_error",false,FM_TYPE_UNKNOWN,5,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the CustomerView object
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
		}
		return $km;
	}

}

?>