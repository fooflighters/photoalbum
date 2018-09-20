<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Photo uploader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>

	<?php include('menu.php'); ?>
	<main>
	<div class="full-height">
		<div class="container">
			<br><br>
			<div class="center">
				<p>The photo was successfully uploaded!</p>
				<a href="upload.php" class="waves-effect waves-light green darken-4 btn">Upload another photo</a>
			</div>
		</div>
	</div>
	</main>



	<?php include('footer.php'); ?>


    <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>
</body>
</html>

