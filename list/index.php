<?php
require_once('../inc/config.php');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List | ButtyRunner (v0.0.1 not even a beta yet)</title>

    <!-- Bootstrap -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="bootstrap-theme.min.css"/>
	
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	  <?php
	  include_once("../inc/ga.php");
	  include_once("../inc/navigation.php");
	  ?>
		  
		  <div class="container">

		        <div class="page-header" id="banner">
		          <div class="row">
		            <div class="col-lg-6">
		              <h1>List</h1>
		              <p class="lead">Here's the Order</p>
					  <ul>
						  <?php
						  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
						  $result = $mysqli->query('SELECT a.id, `name`, `product` FROM `buttyrun` AS a  INNER JOIN `butty` as b ON a.id = b.buttyrun_id  INNER JOIN `user` as c ON b.user_id = c.id WHERE a.id = "' . $_GET["b"] . '" ORDER BY b.updated ASC');
						  
						  if(!$result)
						  {
						  	printf("Error: %s\n", $mysqli->error);
						  }
						  
						  while ($row = $result->fetch_assoc())
						  {
							  printf ("<li>%s - %s</li>\n", $row["name"], $row["product"]);
						  }
						  $result->free();
						  $mysqli->close();
						  ?>
					  </ul>
		            </div>
		          </div>
		        </div>
			</div>
	<?php
	include_once("../inc/footer.php");
	?>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </body>
</html>

<html>