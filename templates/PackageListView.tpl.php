<?php
	$this->assign('title','CARGO | Packages');
	$this->assign('nav','packages');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("bootstrap/js/bootstrap-datepicker.js")
	.script("bootstrap/js/bootstrap-timepicker.js")
	.script("bootstrap/js/bootstrap-combobox.js")
	.script("scripts/libs/underscore-min.js").wait()
	.script("scripts/libs/underscore.date.min.js")
	.script("scripts/libs/backbone-min.js")
	.script("scripts/app.js")
	.script("scripts/model.js").wait()
	.script("scripts/view.js").wait()
	.script("scripts/app/packages.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">

<h1>
	<i class="icon-th-list"></i> Packages
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="packageCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_ShipDate">Ship Date<% if (page.orderBy == 'ShipDate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_ShipTime">Ship Time<% if (page.orderBy == 'ShipTime') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_CustomerId">Customer Id<% if (page.orderBy == 'CustomerId') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_TrackingNumber">Tracking Number<% if (page.orderBy == 'TrackingNumber') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_Description">Description<% if (page.orderBy == 'Description') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Service">Service<% if (page.orderBy == 'Service') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Destination">Destination<% if (page.orderBy == 'Destination') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				<td><%= _.escape(item.get('id') || '') %></td>
				<td><%if (item.get('shipDate')) { %><%= _date(app.parseDate(item.get('shipDate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
				<td><%if (item.get('shipTime')) { %><%= _date(app.parseDate(item.get('shipTime'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('customerId') || '') %></td>
				<td><%= _.escape(item.get('trackingNumber') || '') %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('description') || '') %></td>
				<td><%= _.escape(item.get('service') || '') %></td>
				<td><%= _.escape(item.get('destination') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="packageModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idInputContainer" class="control-group">
					<label class="control-label" for="id">Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="shipDateInputContainer" class="control-group">
					<label class="control-label" for="shipDate">Ship Date</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="shipDate" type="text" value="<%= _date(app.parseDate(item.get('shipDate'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="shipTimeInputContainer" class="control-group">
					<label class="control-label" for="shipTime">Ship Time</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="shipTime" type="text" value="<%= _date(app.parseDate(item.get('shipTime'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<div class="input-append bootstrap-timepicker-component">
							<input id="shipTime-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('shipTime'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="customerIdInputContainer" class="control-group">
					<label class="control-label" for="customerId">Customer Id</label>
					<div class="controls inline-inputs">
						<select id="customerId" name="customerId"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="trackingNumberInputContainer" class="control-group">
					<label class="control-label" for="trackingNumber">Tracking Number</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="trackingNumber" placeholder="Tracking Number" value="<%= _.escape(item.get('trackingNumber') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="descriptionInputContainer" class="control-group">
					<label class="control-label" for="description">Description</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="description" rows="3"><%= _.escape(item.get('description') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="serviceInputContainer" class="control-group">
					<label class="control-label" for="service">Service</label>
					<div class="controls inline-inputs">
						<select id="service" name="service"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="destinationInputContainer" class="control-group">
					<label class="control-label" for="destination">Destination</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="destination" placeholder="Destination" value="<%= _.escape(item.get('destination') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deletePackageButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deletePackageButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Package</button>
						<span id="confirmDeletePackageContainer" class="hide">
							<button id="cancelDeletePackageButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeletePackageButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="packageDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Package
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="packageModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="savePackageButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="packageCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newPackageButton" class="btn btn-primary">Add Package</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
