<?php
/** @package Cargo::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/Phreezable.php");
require_once("ServiceMap.php");

/**
 * ServiceDAO provides object-oriented access to the service table.  This
 * class is automatically generated by ClassBuilder.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * Add any custom business logic to the Model class which is extended from this DAO class.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package Cargo::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ServiceDAO extends Phreezable
{
	/** @var string */
	public $Id;

	/** @var string */
	public $Name;


	/**
	 * Returns a dataset of Package objects with matching Service
	 * @param Criteria
	 * @return DataSet
	 */
	public function GetPackages($criteria = null)
	{
		return $this->_phreezer->GetOneToMany($this, "p_service_code", $criteria);
	}


}
?>