<?php
/** @package    Cargo::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * ServiceMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ServiceDAO to the service datastore.
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
class ServiceMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Service object
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
			$fm["Id"] = new FieldMap("Id","service","s_id",true,FM_TYPE_VARCHAR,10,null,false);
			$fm["Name"] = new FieldMap("Name","service","s_name",false,FM_TYPE_VARCHAR,45,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Service object
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
			$km["p_service_code"] = new KeyMap("p_service_code", "Id", "Package", "Service", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
		}
		return $km;
	}

}

?>