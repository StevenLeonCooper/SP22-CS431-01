<?php
// Gets the requested page or a default value
// Prevents malicious scripting by rejecting malformed requests
$page = $_GET["page"] ?? "home"; // If no path was specified, go to home
if(!preg_match('/^[\w-]+$/', $page)){
    http_response_code(400);
    exit("ERROR: 400 - Bad Request");
}

session_start();
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
				<a href="product" title="Product Listings">Products</a>
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
    </body>
</html>
