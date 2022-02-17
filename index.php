<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Demo Page</title>
		<link rel="stylesheet" href="css/home.css" type="text/css"/>
    </head>

	<body>
		<header>

		</header>
		<main>
			<h1>Demo For Wednesday</h1>
			<p>
				A paragraph of text.
				<?php
                	echo("<!-- This is PHP! -->");
				?>
			</p>
			<button class="say-hi" type="button" data-message="Hello world!!">Hello world</button>
		</main>
		<footer>

		</footer>

		<script>
			// document.querySelectorAll(".say-hi").forEach(button => {
			// 	button.addEventListener("click", function() {
			// 		alert("Hello world!");
			// 	});
			// });

			document.addEventListener("click", event => {
				let eventSource = event.target;

				if (!eventSource.classList.contains("say-hi")) return;

				let displayText = eventSource.dataset.message ?? "No message found." //Separate content like strings out of logic - maybe a dictionary for no message found message.
				alert(displayText);
			});
		</script>
	</body>
</html>
