<?php
require_once('../inc/config.php');

if(count( $_POST) > 0)
{
	// Store order in database
	$mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
	
	// If email address doesn't already exist, create the user
	$result = $mysqli->query('SELECT id, name FROM user WHERE email = "' . trim($_POST['email']) . '" LIMIT 1');
	if($result->num_rows > 0)
	{
		$row = $result->fetch_row();
		$user_id = $row['0'];
		
		// If the user's name has changed (why?) keep the data current
		if($row['1'] != trim($_POST['name']))
		{
			$mysqli->query('UPDATE user SET name = "' . $_POST['name'] . '" WHERE id = "' . $row[0] . '"');
		}
	}
	else
	{
		$user_id = uniqid("1");
		$mysqli->query('INSERT INTO user (id, name, email) VALUES ("' . $user_id . '", "' . trim($_POST['name']) . '", "' . trim($_POST['email']) . '")');		
	}
	$result->close();
	
	// Add My Item
	$butty_id = uniqid("3");
	$mysqli->query('INSERT INTO butty (id, user_id, buttyrun_id, product) VALUES ("' . $butty_id . '", "' . $user_id . '", "' . $_POST["buttyrun_id"] . '", "' . trim($_POST["product"]) . '")');
	
	$mysqli->close();
	
	header('Location: ' . $baseUrl . 'order/thanks');
	exit();
}
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order | ButtyRunner (v0.0.1 not even a beta yet)</title>

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
	  <h1>Your Order</h1>
	  <p>sadasd</p>
	  </div>
	  
	  <div class="container">
		
		  <div class="row">
       		  <div class="col-md-8">
					  
					  <div class="well">
					                <form class="bs-example form-horizontal" method="post" action="./">
										<input type="hidden" name="buttyrun_id" value="<?=$_GET["b"]?>"/>
					                  <fieldset>
					                    
					                    <div class="form-group">
					                      <label for="name" class="col-lg-2 control-label">Name</label>
					                      <div class="col-lg-10">
					                        <input type="text" class="form-control" id="name" placeholder="Your name" name="name">
					                      </div>
					                    </div>
										
					                    <div class="form-group">
					                      <label for="inputEmail" class="col-lg-2 control-label">Email</label>
					                      <div class="col-lg-10">
					                        <input type="text" class="form-control" id="inputEmail" placeholder="Your email address" name="email">
					                      </div>
					                    </div>
					                    
					                
  					                    <div class="form-group">
  					                      <label for="product" class="col-lg-2 control-label">For me</label>
  					                      <div class="col-lg-10">
  					                        <textarea class="form-control" rows="3" id="product" name="product"></textarea>
  					                        <span class="help-block">What are you having?</span>
  					                      </div>
  					                    </div>
										
					                    <div class="form-group">
					                      <div class="col-lg-10 col-lg-offset-2">
					                        <button class="btn btn-default">Cancel</button>
					                        <button type="submit" class="btn btn-primary">Submit</button>
					                      </div>
					                    </div>
										
									</fieldset>
								</form>
							</div
					  </div>

		
				  </div>
        		  <div class="col-md-4">.col-md-4</div>
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