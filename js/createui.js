var tabs = ['Hotel', 'Room', 'Employee', 'Customer', 'Reservation'];
var tabIndex = 0;
var menuID = 1;
var tabName = "Hotel";
var menuName = "Search";
var operation = "Search";
var titles = [];
var titleNames = [];
formCreationInfo = [];
var table;
var rowInfo;

function createUI() {

	$(document).ready(function() {

		$('#tabs').tabs();

		$('#menu1').menu().css('width', 150);
		$('#menu1').menu({
			select : myOnSelect
		});
		
		$('#menu11').menu().css('width', 150);
		$('#menu11').menu({
			select : onSelectQuery
		});

		$('#tabs').on('tabsactivate', function(event, ui) {
			var newIndex = ui.newTab.index();
			tabIndex = newIndex;
			menuID = tabIndex + 1;
			tabName = tabs[tabIndex];
			$('#' + menuName + 'Visible>form').html('');

			$('#menu' + menuID).menu().css('width', 150);
			$('#menu' + menuID).menu({
				select : myOnSelect
			});
			
			$('#menu' + menuID + '' + menuID).menu().css('width', 150);
			$('#menu' + menuID + '' + menuID).menu({
				select : function(event, ui) {}
			});
		});

		$(document).on('click', 'input:submit', myOnSubmit);
	});
}

var onSelectQuery = function(event, ui) {
	$.ajax({
		url : 'php/extraQueries',
		type : 'GET',
		data : {
			table : tabName,
			queryInfo : ui.item.attr('title')
		},
		contentType : 'application/json',
		success : onExtraQuerySuccess,
		error : onCommunicationError
	});
};

var onExtraQuerySuccess = function(response) {
	console.log(response);
};

var myOnSelect = function(event, ui) {
	$('#' + menuName + 'Visible>form').html('');
	$('.visible').hide();
	menuName = ui.item.attr("title");
	operation = menuName;
	$('#Results').html('');
	createForm();
	$('#' + menuName + 'Visible').show();
};

function createForm() {
	$.ajax({
		url : 'php/getFormFields.php',
		type : 'GET',
		data : {
			table : tabName
		},
		dataType : 'json',
		contentType : 'application/json',
		success : onCreateFormSuccess,
		error : onCommunicationError
	});
}

var onCreateFormSuccess = function(response) {
	$(document).on('append', '.label', function(event) {
		$(this).css({
			'width' : '200px',
			'display' : 'inline-block'
		});
	});

	titles = [];
	titleNames = [];
	formCreationInfo = response;
	$.each(response, parseValues);

	$('#' + menuName + 'Visible>form').append('<br/>' + '<input type="submit" id="submit" value="Υποβολή">');

	$('#' + menuName + 'Visible>form' + ' .label').each(function() {
		$(this).trigger('append');
	});
};

var parseValues = function(type, values) {
	$.each(values, function(field, value) {
		var string = tabName + ":" + menuName;
		$('#' + menuName + 'Visible>form').append('<input type="hidden" name="queryInfo"' + ' value="' + string + '" />');
		var HTMLField = '<br />' + '<label class="label">' + value + '</label>';
		HTMLField += (type === 'varchar' || type === 'int') ? '<input type="text" name="' + field + '" /> <br/>' : (type === 'date') ? '<input class="date" type="text" name="' + field + '" size="8" /> <br/>' : (type === 'int5') ? '<input class="spinner" type="text" name="' + field + '" size="5" /> <br/>' : (type === 'bool') ? 'Ναι <input type="radio" name="' + field + '" value="yes"> &nbsp &nbsp' + ' Όχι <input type="radio" name="' + field + '" value="no"> <br/>' : '';

		$('#' + menuName + 'Visible>form').append(HTMLField);
		titles[titles.length] = value;
		titleNames[titleNames.length] = field;
		$('.date').datepicker({
			dateFormat : 'yy-mm-dd'
		});
		$('.spinner').spinner({
			min : 1,
			max : 5
		});
	});

};

var onCommunicationError = function(xhr, status, error) {
	var err = xhr.eval;
	$('#' + menuName + 'Visible').append('<div class="ui-widget">' + '<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">' + '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + '<strong>Προσοχή:</strong> Παρουσιάστηκε σφάλμα στην επικοινωνία με τον εξυπηρετητή.' + '<br /> Η εφαρμογή είναι προσωρινά μη διαθέσιμη</p>' + '<p>Περιγραφή προβλήματος: ' + xhr.responseText + '</p></div></div>');
};

var myOnSubmit = function(event) {
	event.preventDefault();
	var $form = $(this).parent();
	console.log($form.parent().length);
	if(operation == "Update") {
		$("#InsertForm").dialog('close');	
	}
	var serialized = $form.serialize();
	//$.map( $('input', $form), function(a) { return  {'name': $(a).attr("name"), 'value': $(a).val()}; } );
	$.ajax({
		url : 'php/handleForm.php',
		type : 'GET',
		data : serialized,
		dataType : 'json',
		contentType : 'application/json',
		success : onSubmitFormSuccess,
		error : onCommunicationError
	});
	$form[0].reset();
};

var onSubmitFormSuccess = function(response) {
	$('#Results').html('');
	if (operation === "Search") {
		showResults(response);
	} else {
		$('#Results').html('<p>Το αίτημα σας καταχωρήθηκε με επιτυχία</p>');
		$('#Results').dialog();
		$("#Results").dialog("option", "width", 500);
		$("#Results").dialog("option", "height", 100);
	}
};

function showResults(response) {
	$('#Results').html('<table class="display" style="width:10%; border:0; font-size:1em;"" id="myDataTable" cellspacing="0" width="100%">' + '<thead><tr></tr></thead>' + '<tfoot><tr></tr></tfoot>' + '<tbody></tbody>' + '</table>');

	for (var i = 0; i < titles.length; i++) {
		$('#Results > table > thead > tr').append('<th>' + titles[i] + '</th>');
		$('#Results > table > tfoot > tr').append('<th>' + titles[i] + '</th>');
	}
	//	$('#Results > table > thead > tr').append('<th>' + "Ενέργεια" + '</th>');
	//	$('#Results > table > tfoot > tr').append('<th>' + "Ενέργεια" + '</th>');
	responseData = [];
	for (var i = 0; i < response.length; i++) {
		rowData = [];
		for (var j = 0; j < titleNames.length; j++) {
			rowData[rowData.length] = response[i][titleNames[j]];
		}
		rowId = '"row-' + i + '-' + titleNames[j] + '"';
		//	rowData[rowData.length] = '<input type="button" id="' + tabName + ':' + 'Delete" value="Διαγραφή"> <br/>' + '<input type="button" id="' + rowId + ':' + tabName + ':' + 'Update" value="Ενημέρωση">';

		responseData[responseData.length] = rowData;
	}

	//	$('#Results').append('<input type="button" id="' + tabName + ':' + 'Delete" value="Διαγραφή">');
	//	$('#Results').append('<input type="button" id="' + tabName + ':' + 'Update" value="Ενημέρωση">');

	$('#Results').dialog();

	table = $('#Results > table').DataTable({
		bJQueryUI : true,
		data : responseData
	});

	$("#Results").dialog("option", "width", $(window).width());
	$("#Results").dialog("option", "height", $(window).height());

	var clicks = 0, timer = null;

	$('#Results table tbody').on('click', 'tr', function(event) {
		rowInfo = table.row(this).data();
		++clicks;
		if (clicks === 1) {
			timer = setTimeout(function() {

				$('#InsertForm > form').html('');

				$.each(formCreationInfo, popUpForm);
				$('#InsertForm>form').append('<br/>' + '<input type="submit" id="submit" value="Υποβολή">');
				clicks = 0;
			}, 200);
		} else {
			clearTimeout(timer);
			clicks = 0;
		}
	}).on('dblclick', 'tr', function(event) {
		var deleteData = {
			queryInfo : tabName + ':' + 'Delete'
		};
		for (var i = 0; i < titleNames.length; i++) {
			deleteData[titleNames[i]] = rowInfo[i];
		}
		console.log(deleteData);
		$.ajax({
			url : 'php/handleForm.php',
			type : 'GET',
			data : deleteData,
			dataType : 'json',
			contentType : 'application/json',
			success : function(response) {
				$('#Results').html('<p>Το αίτημα σας καταχωρήθηκε με επιτυχία</p>');
				$('#Results').dialog();
				$("#Results").dialog("option", "width", 500);
				$("#Results").dialog("option", "height", 100);
			},
			error : onCommunicationError
		});
	}); 

}

var popUpForm = function(type, values) {
	operation = "Update";
	$.each(values, function(field, value) {
		var idx = jQuery.inArray(field, titleNames);

		var HTMLField = '<br />' + '<label class="label">' + value + '</label>';
		HTMLField += (type === 'varchar' || type === 'int') ? '<input type="text" name="' + field + '" value="def:' + rowInfo[idx] + '" /> <br/>' : (type === 'date') ? '<input class="date" type="text" name="' + field + '" value="def:' + rowInfo[idx] + '" size="8" /> <br/>' : (type === 'int5') ? '<input class="spinner" type="text" name="' + field + '" value="def:' + rowInfo[idx] + '" size="5" /> <br/>' : (type === 'bool') ? 'Ναι <input type="radio" name="' + field + '" value="yes"> &nbsp &nbsp' + ' Όχι <input type="radio" name="' + field + '" value="no"> <br/>' : '';

		$('#InsertForm').dialog();
		var string = tabName + ":" + operation;
		$('#InsertForm>form').append('<input type="hidden" name="queryInfo"' + ' value="' + string + '" />');
		$('#InsertForm>form').append(HTMLField);

		$('.date').datepicker({
			dateFormat : 'yy-mm-dd'
		});
		$('.spinner').spinner({
			min : 1,
			max : 5
		});
	});

};