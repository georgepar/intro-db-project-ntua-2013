var tabs = ['Hotel', 'Room', 'Employee', 'Customer', 'Reservation'];
var tabIndex = 0;
var menuID = 1;
var tabName = "Hotel";
var menuName = "Search";

var onSelect = function(event, ui) {
	$('.visible').hide();
	menuName = ui.item.attr("title");
	createForm();
	$('#' + menuName + 'Visible').show();
};

function createUI() {

	$(document).ready(function() {

		$('#tabs').tabs();

		$('#menu1').menu().css('width', 150);
		$('#menu1').menu({
			select : onSelect
		});

		$('#tabs').on('tabsactivate', function(event, ui) {
			var newIndex = ui.newTab.index();
			tabIndex = newIndex;
			menuID = tabIndex + 1;
			tabName = tabs[tabIndex];

			$('#menu' + menuID).menu().css('width', 150);
			$('#menu' + menuID).menu({
				select : onSelect
			});
		});
	});
}

var parseValues = function(type, values) {
	$.each(values, function(field, value) {
		var HTMLfield = 
		(type === 'varchar' || type === 'int') ? 
		'<br />' + '<label class="label">' + value + '</label>' + '<input type="text" name="' + field + '" /> <br/>' : 
		(type === 'date') ? 
		'<br />' + '<label class="label">' + value + '</label>' + '<input class="date" type="text" name="' + field + '" size="8" /> <br/>' : 
		(type === 'int5') ? 
		'<br />' + '<label class ="label">' + value + '</label>' + '<input class="spinner" type="text" name="' + field + '" size="5" /> <br/>' : '';
		$('#' + menuName + 'Visible>form').append(HTMLfield);
		$('.date').datepicker();
		$('.spinner').spinner({
			min : 1,
			max : 5
		});
	});

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
		success : function(response) {
			$(document).on('append', '.label', function(event) {
				$(this).css({
					'width' : '200px',
					'display' : 'inline-block'
				});
			});
			$('#' + menuName + 'Visible>form').html('');
			var string = tabName + ":" + menuName;
			$('#' + menuName + 'Visible>form').append('<input type="hidden" name="queryInfo"' + ' value="<?php echo ' + string + '  ?>" />');
			$.each(response, parseValues
				// function(type, values) {
				// if (type === 'varchar' || type === 'int') {
					// $.each(values, function(field, value) {
						// $('#' + menuName + 'Visible>form').append('<br />' + '<label class="label">' + value + '</label>' + '<input type="text" name="' + field + '" /> <br/>');
					// });
				// } else if (type === 'date') {
					// $.each(values, function(field, value) {
						// $('#' + menuName + 'Visible>form').append('<br />' + '<label class="label">' + value + '</label>' + '<input class="date" type="text" name="' + field + '" size="8" /> <br/>');
						// $('.date').datepicker();
					// });
				// } else if (type === 'int5') {
					// $.each(values, function(field, value) {
						// $('#' + menuName + 'Visible>form').append('<br />' + '<label class ="label">' + value + '</label>' + '<input class="spinner" type="text" name="' + field + '" size="5" /> <br/>');
						// $('.spinner').spinner({
							// min : 1,
							// max : 5
						// });
					// });
				// }
			// }
			);
			$('#' + menuName + 'Visible>form' + ' .label').each(function() {
				$(this).trigger('append');
			});
			$('#' + menuName + 'Visible>form').append('<br/>' + '<input type="submit" value="Υποβολή">');
		},
		error : function() {
			$('#' + menuName).append('<div class="ui-widget">' + '<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">' + '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + '<strong>Προσοχή:</strong> Παρουσιάστηκε σφάλμα στην επικοινωνία με τον εξυπηρετητή.' + '<br /> Η εφαρμογή είναι προσωρινά μη διαθέσιμη</p>' + '</div></div>');
		}
	});
}