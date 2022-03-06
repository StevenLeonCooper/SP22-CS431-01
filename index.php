<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CPSC431 Wep App - Phase 1(<?php echo ($page); ?>)</title>
		<style>
			<?php include("css/app.css"); ?>
		</style>
		<link class="page-css" id="css_<?php echo ($page); ?>" rel="stylesheet" href="css/<?php echo ($page); ?>.css" />
    </head>

	<body>
		<header>
			<nav>
				<!-- this header is on every page. -->
            </nav>
		</header>
		<main>
			<h1>Demo For Wednesday</h1>
			<p>
				<?php
                	echo "Today is " . date("Y/m/d") . "<br>";
				?>
			</p>
			<button id="view_home"+ class="say-hi" type="button" data-message="Hello world!!">No data received</button>
		</main>
		<footer>
			<!-- This is a footer. --> 
			<p>&copy; <?php echo date("Y"); ?> Banana Bunch, All Rights Reserved.</p>
        </footer>
		<script>
			// AJAX request object
			const request = new XMLHttpRequest();
			request.open("GET", "api/test", true);

			// Callback function that triggers after the request has finished
			request.onload = () => {
				if (request.status >= 400){return false;}
				console.log(request);
				let data = request.response;
				processResponse(data);
			};

			// Callback function that triggers if there's a network error
			request.onerror = (event) => {
				console.log("Error Occured");
				console.log(event);
			};

			// Processes the request
			function processResponse(data) {
				data = JSON.parse(data);
				console.log("value:" + data.value)
				let text = data.value ?? "NO VALUE!!";
				let output = `<h1>${text}</h1>`;
				document.getElementById("view_home").innerHTML = output;
			}

			request.send();
		</script>
    </body>
</html>
