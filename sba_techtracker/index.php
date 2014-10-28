<?php

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>SBA Tech Tracker</title>
		<link rel="stylesheet" href="jQuery/jQuery_UI/jquery-ui.min.css">
		<link rel="stylesheet" href="CSS/sba-style.css">
		<script src="jQuery/jQuery_UI/external/jquery/jquery.js"></script>
		<script src="jQuery/jQuery_UI/jquery-ui.min.js"></script>
		<script src="JS/sceneStartup.js" type="text/javascript"></script>
	</head>
	
	<body>
		<div name="main">
			<header>
				<h1>SBA Tech Tracker</h1>
				<nav>
					<!-- NAV FRAME CONTENT -->
					<a href="/login/">Log In</a> |
					<a href="/register/">Register</a> |
				</nav>
			</header>

			<section name="primary">
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Dashboard</a></li>
						<li><a href="#tabs-2">Prechecks</a></li>
						<li><a href="#tabs-3">Tech Tracker</a></li>
						<li><a href="#tabs-4">Nightly Summary</a></li>
					</ul>
					<div id="tabs-1">
						<p>This tab will have general activation statistics as well as a "Shaun's Corner" style note of the day.</p>
						<p>If the user is not logged in, this will be the default tab.</p>
					</div>
					<div id="tabs-2">
						<p>This tab will display the precheck information for the requested store.  It will default to the users store on initial load.</p>
						<p>If the user is not logged in, this will ask the user to log in.</p>
					</div>
					<div id="tabs-3">
						<p>This tab will house the tech tracker utility.</p>
						<p>If the user is not logged in, this will ask the user to log in.</p>
					</div>
					<div id="tabs-4">
						<p>This tab will house the activation summary HUD.</p>
						<p>If the user is not logged in, this will ask the user to log in.</p>
					</div>
				</div>
			</section>

			<footer>
				<!-- FOOTER CONTENT -->
			</footer>
		</div>
		<script>
			$("#tabs").tabs();
			$("a").button();
		</script>
	</body>
	
</html>
