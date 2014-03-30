<?php
require_once('../../inc/config.php');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Too Late! | ButtyRunner (alpha)</title> <!-- Bootstrap -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/css/bootstrap-theme.min.css"/>
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	  <?php
	  include_once("../../inc/ga.php");
	  include_once("../../inc/navigation.php");
	  ?>
	  
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h1>Too Late!</h1>
              <p class="lead">Unfortunately you've missed the deadline for this ButtyRun.</p>
			  <p>You can always create a <a href="/new">new ButtyRun</a> and invite others that might have missed out.</p>
			</div>
		</div>
	</div>

  	  	  	  
	<?php
	include_once("../../inc/footer.php");
	?>
   
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </body>
</html>

<html>