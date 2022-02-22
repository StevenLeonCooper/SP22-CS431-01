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
			<div id="<?php echo ("view_$page") ?>">&nbsp;</div>
        </main>
		<footer>
			<!-- This is a footer. --> 
			<p>&copy; <?php echo date("Y"); ?> Banana Bunch, All Rights Reserved.</p>
        </footer>
		<script>
			const request = new XMLHttrequest();
			request.open("GET", "api/api-test.php", true);
			request.onload = () => {
				if (request.status >= 400){return false;}
				console.log(request);
				let data = request.response;
				processResponse(data);
			};

			request.onerror = (event) => {
				console.log("Error Occured");
				console.log(event);
			};

			function processResponse(data) {
				data = JSON.parse(data);
				let text = data.value ?? "NO VALUE!";
				let output = '<h1>${}</h1>';
				document.getElementById("view_home").innerHTML = output;
			}

			request.send();
		</script>
    </body>
</html>
