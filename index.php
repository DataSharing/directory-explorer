<?php include 'app.explorer/fonctions.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Explorer v1.1</title>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="./app.explorer/fonts/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	
	<!-- Style perso -->
	<link rel="stylesheet" href="./app.explorer/css/app.css">
	<link rel="stylesheet" href="./app.explorer/css/checkbox/css/style.css">
	<script type="text/javascript" src="./app.explorer/js/app.js"></script>
</head>
<body>
	<?php 
	nouveauDossier();
	supprimer();
	uploadFile();
	?>
	<form action='' method="post" enctype="multipart/form-data" >
		<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
			<a class="navbar-brand" href="index.php"><i class="fa fa-wpexplorer" aria-hidden="true"></i> Explorer</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
				<i class="navbar-toggler-icon"></i>
			</button>
			<div class="navbar-collapse collapse" id="navbar">
				<div class="form-inline my-2 my-lg-0 navbar-nav mr-auto">
					<input type="text" class='form-control mr-sm-2' name="dossier" placeholder="nom du dossier" />
					<button type="submit" class="btn btn-warning" name='ajouterDossier'>
						<i class="fa fa-folder-open-o" aria-hidden="true"></i>
						Nouveau dossier
					</button>
				</div>
				<div class="nav navbar-nav navbar-right">
					<button type="submit" class="btn btn-danger mr-sm-2" name='supprimer' >
						<i class="fa fa-trash" aria-hidden="true"></i>
						Supprimer
					</button>
				</div>
			</div>
		</nav>
		<div class="row" style="margin: 3.5em auto;">
			<div class="col-12" style="background:#212529;padding:1em;text-align: center;color:#d1d1d1">
				<input type="file" name="donnees[]"  multiple/>
				<button type="submit" class="btn btn-dark" name='upload'>
					<i class="fa fa-upload" aria-hidden="true"></i>
					Charger
				</button>
			</div>
			<div class="col-12" style="padding: 0;">
				<?php
				tableauExplorer();
				?>
			</div>
		</div>
	</form>
</body>
</html> 