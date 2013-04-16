<?php
/** @package    CARGO::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Package.php");

/**
 * PackageController is the controller class for the Package object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package CARGO::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class PackageController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 *
	 * @inheritdocs
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
	}

	/**
	 * Displays a list view of campaign objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for campaign records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new PackageCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,ShipDate,ShipTime,CustomerId,TrackingNumber,Description,Service,Destination'
				, '%'.$filter.'%')
			);

			// TODO: this is generic query filtering based only on criteria properties
			foreach (array_keys($_REQUEST) as $prop)
			{
				$prop_normal = ucfirst($prop);
				$prop_equals = $prop_normal.'_Equals';

				if (property_exists($criteria, $prop_normal))
				{
					$criteria->$prop_normal = RequestUtil::Get($prop);
				}
				elseif (property_exists($criteria, $prop_equals))
				{
					// this is a convenience so that the _Equals suffix is not needed
					$criteria->$prop_equals = RequestUtil::Get($prop);
				}
			}

			$output = new stdClass();

			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);

			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				$packages = $this->Phreezer->Query('Package',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $packages->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $packages->TotalResults;
				$output->totalPages = $packages->TotalPages;
				$output->pageSize = $packages->PageSize;
				$output->currentPage = $packages->CurrentPage;
			}
			else
			{
				// return all results
				$packages = $this->Phreezer->Query('Package',$criteria);
				$output->rows = $packages->ToObjectArray(true, $this->SimpleObjectParams());
				$output->totalResults = count($output->rows);
				$output->totalPages = 1;
				$output->pageSize = $output->totalResults;
				$output->currentPage = 1;
			}


			$this->RenderJSON($output, $this->JSONPCallback());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method retrieves a single campaign record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$package = $this->Phreezer->Get('Package',$pk);
			$this->RenderJSON($package, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new campaign record and render response as JSON
	 */
	public function Create()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$package = new Package($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $package->Id = $this->SafeGetVal($json, 'id');

			$package->ShipDate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'shipDate')));
			$package->ShipTime = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'shipTime')));
			$package->CustomerId = $this->SafeGetVal($json, 'customerId');
			$package->TrackingNumber = $this->SafeGetVal($json, 'trackingNumber');
			$package->Description = $this->SafeGetVal($json, 'description');
			$package->Service = $this->SafeGetVal($json, 'service');
			$package->Destination = $this->SafeGetVal($json, 'destination');

			$package->Validate();
			$errors = $package->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$package->Save();
				$this->RenderJSON($package, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing campaign record and render response as JSON
	 */
	public function Update()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('id');
			$package = $this->Phreezer->Get('Package',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $package->Id = $this->SafeGetVal($json, 'id', $package->Id);

			$package->ShipDate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'shipDate', $package->ShipDate)));
			$package->ShipTime = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'shipTime', $package->ShipTime)));
			$package->CustomerId = $this->SafeGetVal($json, 'customerId', $package->CustomerId);
			$package->TrackingNumber = $this->SafeGetVal($json, 'trackingNumber', $package->TrackingNumber);
			$package->Description = $this->SafeGetVal($json, 'description', $package->Description);
			$package->Service = $this->SafeGetVal($json, 'service', $package->Service);
			$package->Destination = $this->SafeGetVal($json, 'destination', $package->Destination);

			$package->Validate();
			$errors = $package->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$package->Save();
				$this->RenderJSON($package, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing campaign record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$package = $this->Phreezer->Get('Package',$pk);

			$package->Delete();

			$output = new stdClass();

			$this->RenderJSON($output, $this->JSONPCallback());

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
}

?>
