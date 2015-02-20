<?php
require_once 'php/init.php';
?>
<!DOCTYPE html>

<html>
	<head>
		<script
		src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>

		<!-- DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">

 
		<!-- DataTables -->
		<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>

		<script type="text/javascript" language="javascript" src="http://www.appelsiini.net/download/jquery.jeditable.mini.js"></script>
	</head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<body>
		<h1>Αστέρας Βουλιαγμένης!</h1>

		<div id="wrapper">
			<script type="text/javascript" src="js/createui.js"></script>

			<script>
				createUI()
			</script>
			<div id="tabs">
				<ul>
					<li title="Hotel">
						<a href="#Hotel">Ξενοδοχεία</a>
					</li>
					<li title="Room">
						<a href="#Room">Δωμάτια</a>
					</li>
					<li title="Employee">
						<a href="#Employee">Υπάλληλοι</a>
					</li>
					<li title="Customer">
						<a href="#Customer">Πελάτες</a>
					</li>
					<li title="Reservation">
						<a href="#Reservation">Κρατήσεις</a>
					</li>
				</ul>

				<div id="Hotel">
					<h2>Επιλέξτε μια λειτουργία</h2>

					<ul id="menu1">
						<li title="Search">
							<a href="#">Αναζήτηση</a>

						</li>

						<li title="Insert">
							<a href="#">Εισαγωγή</a>

						</li>
					</ul>
					<ul id="menu11">
						<li title="Telephone">
							<a href="#">Στοιχεία Επικοινωνίας</a>

						</li>
					</ul>
				</div>
				<div id="Room">
					<h2>Επιλέξτε μια λειτουργία</h2>

					<ul id="menu2">
						<li title="Search">
							<a href="#">Αναζήτηση</a>

						</li>

						<li title="Insert">
							<a href="#">Εισαγωγή</a>

						</li>
					</ul>
					<ul id="menu22">
						<li title="CheapRooms">
							<a href="#">Φτηνά Δωμάτια</a>

						</li>
					</ul>
				</div>
				<div id="Employee">
					<h2>Επιλέξτε μια λειτουργία</h2>

					<ul id="menu3">
						<li title="Search">
							<a href="#">Αναζήτηση</a>

						</li>

						<li title="Insert">
							<a href="#">Εισαγωγή</a>

						</li>
					</ul>
					<ul id="menu33">
						<li title="Telephone">
							<a href="#">Στοιχεία Επικοινωνίας</a>

						</li>

						<li title="EmployeesPerHotel">
							<a href="#">Λίστα Εργαζομένων ανά ξενοδοχείο</a>

						</li>
					</ul>					
				</div>
				<div id="Customer">
					<h2>Επιλέξτε μια λειτουργία</h2>

					<ul id="menu4">
						<li title="Search">
							<a href="#">Αναζήτηση</a>

						</li>

						<li title="Insert">
							<a href="#">Εισαγωγή</a>

						</li>
					</ul>
					<ul id="menu44">
						<li title="Telephone">
							<a href="#">Στοιχεία Επικοινωνίας</a>
						</li>
						<li title="CountVip">
							<a href="#">Πλήθος VIP πελατών</a>
						</li>
					</ul>
				</div>
				<div id="Reservation">
					<h2>Επιλέξτε μια λειτουργία</h2>

					<ul id="menu5">
						<li title="Search">
							<a href="#">Αναζήτηση</a>

						</li>

						<li title="Insert">
							<a href="#">Εισαγωγή</a>

						</li>
					</ul>
				</div>

				<div style="display: none;" class="visible" id="SearchVisible">
					<!-- Search -->
					<form></form>
				</div>
				<div style="display: none;" class="visible" id="InsertVisible">
					<!-- Insert -->
					<form></form>
				</div>
				<div id="Results">
					<table class="display" style="width:10%; border:0; font-size:1em;"" id="myDataTable" cellspacing="0" width="100%">
					<thead>
						<tr></tr>
					</thead>
					<tfoot>
						<tr></tr>
					</tfoot><tbody></tbody>
					</table>
				</div>
				
				<div id="InsertForm">
					<form>
						
					</form>
				</div>
				

			</div>
		</div>

	</body>
</html>
