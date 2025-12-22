<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>404 - Page Not Found</title>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
	<style>
		body {
			font-family: 'Poppins', sans-serif;
			margin: 0;
			padding: 0;
			background-color: #f4f4f9;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
			color: #333;
			text-align: center;
		}

		.container {
			max-width: 600px;
			padding: 30px;
			background-color: #fff;
			border-radius: 10px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
		}

		h1 {
			font-size: 100px;
			color: #ff6347;
			margin: 0;
		}

		h2 {
			font-size: 24px;
			color: #555;
			margin-bottom: 20px;
		}

		p {
			font-size: 18px;
			color: #777;
			margin-bottom: 40px;
		}

		.btn {
			background-color: #ff6347;
			color: white;
			padding: 15px 30px;
			font-size: 16px;
			text-decoration: none;
			border-radius: 5px;
			transition: background-color 0.3s ease;
		}

		.btn:hover {
			background-color: #ff4500;
		}

		.support {
			margin-top: 20px;
			font-size: 14px;
		}

		.support a {
			color: #ff6347;
			text-decoration: none;
		}

		.support a:hover {
			text-decoration: underline;
		}
	</style>
</head>

<body>
	<div class="container">
		<h1>404</h1>
		<h2>Oops! Page Not Found</h2>
		<p>Sorry, the page you're looking for doesn't exist or has been moved.</p>
		<a href="<?= base_url();?>" class="btn">Back to Home</a>

		<div class="support">
			<p>If you need assistance, feel free to <a href="<?= base_url();?>">contact support</a>.</p>
		</div>
	</div>
</body>

</html>
