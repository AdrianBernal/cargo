<?php
/** @package    CARGO::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Customer.php");

/**
 * CustomerController is the controller class for the Customer object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package CARGO::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class CustomerController extends AppBaseController
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
			$criteria = new CustomerCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,Name,LastLogin,Company,City,Level,Error'
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

				$customers = $this->Phreezer->Query('Customer',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $customers->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $customers->TotalResults;
				$output->totalPages = $customers->TotalPages;
				$output->pageSize = $customers->PageSize;
				$output->currentPage = $customers->CurrentPage;
			}
			else
			{
				// return all results
				$customers = $this->Phreezer->Query('Customer',$criteria);
				$output->rows = $customers->ToObjectArray(true, $this->SimpleObjectParams());
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
			$customer = $this->Phreezer->Get('Customer',$pk);
			$this->RenderJSON($customer, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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

			$customer = new Customer($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $customer->Id = $this->SafeGetVal($json, 'id');

			$customer->Name = $this->SafeGetVal($json, 'name');
			$customer->LastLogin = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'lastLogin')));
			$customer->Company = $this->SafeGetVal($json, 'company');
			$customer->City = $this->SafeGetVal($json, 'city');
			$customer->Level = $this->SafeGetVal($json, 'level');
			$customer->Error = $this->SafeGetVal($json, 'error');

			$customer->Validate();
			$errors = $customer->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$customer->Save();
				$this->RenderJSON($customer, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
			$customer = $this->Phreezer->Get('Customer',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $customer->Id = $this->SafeGetVal($json, 'id', $customer->Id);

			$customer->Name = $this->SafeGetVal($json, 'name', $customer->Name);
			$customer->LastLogin = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'lastLogin', $customer->LastLogin)));
			$customer->Company = $this->SafeGetVal($json, 'company', $customer->Company);
			$customer->City = $this->SafeGetVal($json, 'city', $customer->City);
			$customer->Level = $this->SafeGetVal($json, 'level', $customer->Level);
			$customer->Error = $this->SafeGetVal($json, 'error', $customer->Error);

			$customer->Validate();
			$errors = $customer->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$customer->Save();
				$this->RenderJSON($customer, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
			$customer = $this->Phreezer->Get('Customer',$pk);

			$customer->Delete();

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
