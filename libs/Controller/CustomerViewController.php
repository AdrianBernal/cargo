<?php
/** @package    CARGO::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/CustomerView.php");

/**
 * CustomerViewController is the controller class for the CustomerView object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package CARGO::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class CustomerViewController extends AppBaseController
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
			$criteria = new CustomerViewCriteria();
			
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

				$customerviews = $this->Phreezer->Query('CustomerView',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $customerviews->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $customerviews->TotalResults;
				$output->totalPages = $customerviews->TotalPages;
				$output->pageSize = $customerviews->PageSize;
				$output->currentPage = $customerviews->CurrentPage;
			}
			else
			{
				// return all results
				$customerviews = $this->Phreezer->Query('CustomerView',$criteria);
				$output->rows = $customerviews->ToObjectArray(true, $this->SimpleObjectParams());
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
			$customerview = $this->Phreezer->Get('CustomerView',$pk);
			$this->RenderJSON($customerview, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
			// TODO: views are read-only by default.  uncomment at your own discretion
			throw new Exception('Database views are read-only and cannot be updated');
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$customerview = new CustomerView($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$customerview->Id = $this->SafeGetVal($json, 'id');
			$customerview->Name = $this->SafeGetVal($json, 'name');
			$customerview->LastLogin = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'lastLogin')));
			$customerview->Company = $this->SafeGetVal($json, 'company');
			$customerview->City = $this->SafeGetVal($json, 'city');
			$customerview->Level = $this->SafeGetVal($json, 'level');
			$customerview->Error = $this->SafeGetVal($json, 'error');

			$customerview->Validate();
			$errors = $customerview->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				// since the primary key is not auto-increment we must force the insert here
				$customerview->Save(true);
				$this->RenderJSON($customerview, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
			// TODO: views are read-only by default.  uncomment at your own discretion
			throw new Exception('Database views are read-only and cannot be updated');
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('id');
			$customerview = $this->Phreezer->Get('CustomerView',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $customerview->Id = $this->SafeGetVal($json, 'id', $customerview->Id);

			$customerview->Name = $this->SafeGetVal($json, 'name', $customerview->Name);
			$customerview->LastLogin = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'lastLogin', $customerview->LastLogin)));
			$customerview->Company = $this->SafeGetVal($json, 'company', $customerview->Company);
			$customerview->City = $this->SafeGetVal($json, 'city', $customerview->City);
			$customerview->Level = $this->SafeGetVal($json, 'level', $customerview->Level);
			$customerview->Error = $this->SafeGetVal($json, 'error', $customerview->Error);

			$customerview->Validate();
			$errors = $customerview->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$customerview->Save();
				$this->RenderJSON($customerview, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{

			// this table does not have an auto-increment primary key, so it is semantically correct to
			// issue a REST PUT request, however we have no way to know whether to insert or update.
			// if the record is not found, this exception will indicate that this is an insert request
			if (is_a($ex,'NotFoundException'))
			{
				return $this->Create();
			}

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
			// TODO: views are read-only by default.  uncomment at your own discretion
			throw new Exception('Database views are read-only and cannot be updated');
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$customerview = $this->Phreezer->Get('CustomerView',$pk);

			$customerview->Delete();

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
