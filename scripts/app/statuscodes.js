/**
 * View logic for StatusCodes
 */

/**
 * application logic specific to the StatusCode listing page
 */
var page = {

	statusCodes: new model.StatusCodeCollection(),
	collectionView: null,
	statusCode: null,
	modelView: null,
	isInitialized: false,

	fetchParams: { filter: '', orderBy: '', orderDesc: '', page: 1 },
	fetchInProgress: false,
	dialogIsOpen: false,

	/**
	 *
	 */
	init: function()
	{
	
		if (!$.isReady && console) console.warn('page was initialized before dom is ready.  views may not render properly.');

		// make the new button clickable
		$("#newStatusCodeButton").click(function(e) {
			e.preventDefault();
			page.showDetailDialog();
		});

		// let the page know when the dialog is open
		$('#statusCodeDetailDialog').on('show',function(){
			page.dialogIsOpen = true;
		});

		// when the model dialog is closed, let page know and reset the model view
		$('#statusCodeDetailDialog').on('hidden',function(){
			$('#modelAlert').html('');
			page.dialogIsOpen = false;
		});

		// save the model when the save button is clicked
		$("#saveStatusCodeButton").click(function(e) {
			e.preventDefault();
			page.updateModel();
		});

		// initialize the collection view
		this.collectionView = new view.CollectionView({
			el: $("#statusCodeCollectionContainer"),
			templateEl: $("#statusCodeCollectionTemplate"),
			collection: page.statusCodes
		});

		// initialize the search filter
		$('#filter').change(function(obj){
			page.fetchParams.filter = $('#filter').val();
			page.fetchParams.page = 1;
			page.fetchStatusCodes(page.fetchParams);
		});
		
		// make the rows clickable ('rendered' is a custom event, not a standard backbone event)
		this.collectionView.on('rendered',function(){

			// attach click handler to the table rows for editing
			$('table.collection tbody tr').click(function(e) {
				e.preventDefault();
				var m = page.statusCodes.get(this.id);
				page.showDetailDialog(m);
			});

			// make the headers clickable for sorting
 			$('table.collection thead tr th').click(function(e) {
 				e.preventDefault();
				var prop = this.id.replace('header_','');

				// toggle the ascending/descending before we change the sort prop
				page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
				page.fetchParams.orderBy = prop;
				page.fetchParams.page = 1;
 				page.fetchStatusCodes(page.fetchParams);
 			});

			// attach click handlers to the pagination controls
			$('.pageButton').click(function(e) {
				e.preventDefault();
				page.fetchParams.page = this.id.substr(5);
				page.fetchStatusCodes(page.fetchParams);
			});
			
			page.isInitialized = true;
		});

		// backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
		this.fetchStatusCodes({ page: 1 });

		// initialize the model view
		this.modelView = new view.ModelView({
			el: $("#statusCodeModelContainer")
		});

		// tell the model view where it's template is located
		this.modelView.templateEl = $("#statusCodeModelTemplate");

		if (model.longPollDuration > 0)
		{
			setInterval(function () {

				if (!page.dialogIsOpen)
				{
					page.fetchStatusCodes(page.fetchParams,true);
				}

			}, model.longPollDuration);
		}
	},

	/**
	 * Fetch the collection data from the server
	 * @param object params passed through to collection.fetch
	 * @param bool true to hide the loading animation
	 */
	fetchStatusCodes: function(params, hideLoader)
	{
		// persist the params so that paging/sorting/filtering will play together nicely
		page.fetchParams = params;

		if (page.fetchInProgress)
		{
			if (console) console.log('supressing fetch because it is already in progress');
		}

		page.fetchInProgress = true;

		if (!hideLoader) app.showProgress('loader');;

		page.statusCodes.fetch({

			data: params,

			success: function() {

				if (page.statusCodes.collectionHasChanged)
				{
					// data returned from the server.  render the collection view
					page.collectionView.render();
				}

				app.hideProgress('loader');
				page.fetchInProgress = false;
			},

			error: function(m, r) {
				app.appendAlert(app.getErrorMessage(r), 'alert-error',0,'collectionAlert');
				app.hideProgress('loader');
				page.fetchInProgress = false;
			}

		});
	},

	/**
	 * show the dialog for editing a model
	 * @param model
	 */
	showDetailDialog: function(m) {

		// show the modal dialog
		$('#statusCodeDetailDialog').modal({ show: true });

		// if a model was specified then that means a user is editing an existing record
		// if not, then the user is creating a new record
		page.statusCode = m ? m : new model.StatusCodeModel();

		page.modelView.model = page.statusCode;

		if (page.statusCode.id == null || page.statusCode.id == '')
		{
			// this is a new record, there is no need to contact the server
			page.renderModelView(false);
		}
		else
		{
			app.showProgress('modelLoader');

			// fetch the model from the server so we are not updating stale data
			page.statusCode.fetch({

				success: function() {
					// data returned from the server.  render the model view
					page.renderModelView(true);
				},

				error: function(m, r) {
					app.appendAlert(app.getErrorMessage(r), 'alert-error',0,'modelAlert');
					app.hideProgress('modelLoader');
				}

			});
		}

	},

	/**
	 * Render the model template in the popup
	 * @param bool show the delete button
	 */
	renderModelView: function(showDeleteButton)
	{
		page.modelView.render();

		app.hideProgress('modelLoader');

		// initialize any special controls
		try {
			$('.date-picker')
				.datepicker()
				.on('changeDate', function(ev){
					$('.date-picker').datepicker('hide');
				});
		} catch (error) {
			// this happens if the datepicker input.value isn't a valid date
			if (console) console.log('datepicker error: '+error.message);
		}
		
		$('.timepicker-default').timepicker({ defaultTime: 'value' });


		if (showDeleteButton)
		{
			// attach click handlers to the delete buttons

			$('#deleteStatusCodeButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeleteStatusCodeContainer').show('fast');
			});

			$('#cancelDeleteStatusCodeButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeleteStatusCodeContainer').hide('fast');
			});

			$('#confirmDeleteStatusCodeButton').click(function(e) {
				e.preventDefault();
				page.deleteModel();
			});

		}
		else
		{
			// no point in initializing the click handlers if we don't show the button
			$('#deleteStatusCodeButtonContainer').hide();
		}
	},

	/**
	 * update the model that is currently displayed in the dialog
	 */
	updateModel: function()
	{
		// reset any previous errors
		$('#modelAlert').html('');
		$('.control-group').removeClass('error');
		$('.help-inline').html('');

		// if this is new then on success we need to add it to the collection
		var isNew = page.statusCode.isNew();

		app.showProgress('modelLoader');

		page.statusCode.save({
			'id': $('input#id').val(),
			'name': $('input#name').val()
		}, {
			wait: true,
			success: function(){
				$('#statusCodeDetailDialog').modal('hide');
				setTimeout("app.appendAlert('StatusCode was sucessfully " + (isNew ? "inserted" : "updated") + "','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				// if the collection was initally new then we need to add it to the collection now
				if (isNew) { page.statusCodes.add(page.statusCode) }

				if (model.reloadCollectionOnModelUpdate)
				{
					// re-fetch and render the collection after the model has been updated
					page.fetchStatusCodes(page.fetchParams,true);
				}
		},
			error: function(model,response,scope){

				app.hideProgress('modelLoader');

				app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');

				try {
					var json = $.parseJSON(response.responseText);

					if (json.errors)
					{
						$.each(json.errors, function(key, value) {
							$('#'+key+'InputContainer').addClass('error');
							$('#'+key+'InputContainer span.help-inline').html(value);
							$('#'+key+'InputContainer span.help-inline').show();
						});
					}
				} catch (e2) {
					if (console) console.log('error parsing server response: '+e2.message);
				}
			}
		});
	},

	/**
	 * delete the model that is currently displayed in the dialog
	 */
	deleteModel: function()
	{
		// reset any previous errors
		$('#modelAlert').html('');

		app.showProgress('modelLoader');

		page.statusCode.destroy({
			wait: true,
			success: function(){
				$('#statusCodeDetailDialog').modal('hide');
				setTimeout("app.appendAlert('The StatusCode record was deleted','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				if (model.reloadCollectionOnModelUpdate)
				{
					// re-fetch and render the collection after the model has been updated
					page.fetchStatusCodes(page.fetchParams,true);
				}
			},
			error: function(model,response,scope){
				app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');
				app.hideProgress('modelLoader');
			}
		});
	}
};

