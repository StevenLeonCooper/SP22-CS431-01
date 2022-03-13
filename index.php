<?php
// Gets the requested page or a default value
// Prevents malicious scripting by rejecting malformed requests
$page = $_GET["page"] ?? "home"; // If no path was specified, go to home
if(!preg_match('/^[\w-]+$/', $page)){
    http_response_code(400);
    exit("ERROR: 400 - Bad Request");
} 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CPSC431 Wep App - Phase 2(<?php echo ($page); ?>)</title>
		<style>
			<?php include("css/app.css"); ?>
		</style>
		<link class="page-css" id="css_<?php echo ($page); ?>" rel="stylesheet" href="css/<?php echo ($page); ?>.css" />
    </head>

	<body>
		<header>
			<!-- This header appears on every page. -->
			<nav>
				<a href="home" title="Home Page">Home Page</a>
				<a href="products" title="Product Listings">Products</a>
            </nav>
		</header>
		<main>
			<div id="<?php echo ("view_$page") ?>">&nbsp;</div>
		</main>
		<footer>
			<!-- This footer appears on every page. --> 
			<p>&copy; <?php echo date("Y"); ?> Banana Bunch, All Rights Reserved.</p>
        </footer>
		<script type="module" src="_controller/c_<?php echo ("$page"); ?>.js"></script>
		<!--<script>
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
		</script>-->
    </body>
</html>
