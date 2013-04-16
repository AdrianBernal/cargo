<?php
/** @package    Cargo::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * PackageMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the PackageDAO to the package datastore.
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
class PackageMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Package object
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
			$fm["Id"] = new FieldMap("Id","package","p_id",true,FM_TYPE_INT,10,null,true);
			$fm["ShipDate"] = new FieldMap("ShipDate","package","p_ship_date",false,FM_TYPE_DATE,null,null,false);
			$fm["ShipTime"] = new FieldMap("ShipTime","package","p_ship_time",false,FM_TYPE_DATETIME,null,null,false);
			$fm["CustomerId"] = new FieldMap("CustomerId","package","p_customer_id",false,FM_TYPE_INT,10,null,false);
			$fm["TrackingNumber"] = new FieldMap("TrackingNumber","package","p_tracking_number",false,FM_TYPE_VARCHAR,45,null,false);
			$fm["Description"] = new FieldMap("Description","package","p_description",false,FM_TYPE_TEXT,null,null,false);
			$fm["Service"] = new FieldMap("Service","package","p_service",false,FM_TYPE_VARCHAR,10,null,false);
			$fm["Destination"] = new FieldMap("Destination","package","p_destination",false,FM_TYPE_VARCHAR,45,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Package object
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
			$km["p_customer"] = new KeyMap("p_customer", "CustomerId", "Customer", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
			$km["p_service_code"] = new KeyMap("p_service_code", "Service", "Service", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return $km;
	}

}

?>