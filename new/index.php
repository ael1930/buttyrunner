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
		
	// Create the new order	
	$buttyrun_id = uniqid("2");
	$collect = DateTime::createFromFormat('d/m/Y H:i', $_POST["collect_date"] . ' ' . $_POST["collect_time"]);
	$deadline = DateTime::createFromFormat('d/m/Y H:i', $_POST["collect_date"] . ' ' . $_POST["collect_time"]);
	$deadlineInterval = new DateInterval('PT' . $_POST["deadline"] . 'M');
	$deadline->sub($deadlineInterval);
	$mysqli->query('INSERT INTO buttyrun (id, user_id, vendor, collect, deadline) VALUES ("' . $buttyrun_id . '", "' . $user_id . '", "' . $_POST['vendor'] . '", "' . $collect->format('Y-m-d H:i:s') . '", "' . $deadline->format('Y-m-d H:i:s') . '")');
	
	// Add My Item
	$butty_id = uniqid("3");
	$mysqli->query('INSERT INTO butty (id, user_id, buttyrun_id, product) VALUES ("' . $butty_id . '", "' . $user_id . '", "' . $buttyrun_id . '", "' . trim($_POST["product"]) . '")');
	
	$mysqli->close();
	
	// Send email to organiser
	$subject = 'Your ButtyRun to ' . trim($_POST['vendor']) . ' on ' . trim($_POST['collect_date']) . ' at ' . trim($_POST['collect_time']); 
	$headers = 'From: noreply@buttyrunner.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	$message = 'Hi ' . trim($_POST['name']) . ','. "\r\n\r\n" . 'You\'re going to ' . trim($_POST['vendor']) .' on ' . trim($_POST['collect_date']) . ' at ' . trim($_POST['collect_time']) . '.' . "\r\n\r\n" . 'The ButtyList is available at the link below.'. "\r\n\r\n" . $baseUrl . 'list/?b=' . $buttyrun_id . "\r\n\r\n" . 'Enjor your food!' . "\r\n\r\n" . '--' . "\r\n". 'ButtyRunner.com' . "\r\n". 'Follow us on Twitter at http://twitter.com/buttyrunner'; 	
	mail(trim($_POST['email']), $subject, $message, $headers);
	
	// Send email to invetees	
	$subject = 'There\'s a ButtyRun to ' . trim($_POST['vendor']) . ' on ' . trim($_POST['collect_date']) . ' at ' . trim($_POST['collect_time']);	
	$message = 'Hi,'. "\r\n\r\n" . 'I\'m going to ' . trim($_POST['vendor']) . ' on ' . trim($_POST['collect_date']) . ' at ' . trim($_POST['collect_time']) . '.' . "\r\n\r\n" . 'You can place your order at the link below until ' . $deadline->format('H:i') . '.'. "\r\n\r\n" . $baseUrl . 'order/?b=' . $buttyrun_id . "\r\n\r\n" . trim($_POST['name']) . "\r\n\r\n" . '--' . "\r\n". 'ButtyRunner.com' . "\r\n". 'Follow us on Twitter at http://twitter.com/buttyrunner';	
	$headers = 'From: '. trim($_POST['email']) . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	$invitees = explode("\r\n", trim($_POST['shout']));
	foreach($invitees as $invite){
		mail(trim($invite), $subject, $message, $headers);
	}	
	header('Location: ' . $baseUrl . 'list/?b=' . $buttyrun_id);
	exit();
}
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New | ButtyRunner (v0.0.1 not even a beta yet)</title>

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
		              <div class="well">
					                <form class="bs-example form-horizontal" method="post" action="./">
					                  <fieldset>
					                    <legend>New Butty Run</legend>
										
					                    <div class="form-group">
					                      <label for="name" class="col-lg-2 control-label">Name</label>
					                      <div class="col-lg-10">
					                        <input type="text" class="form-control" id="name" placeholder="Name" name="name">
					                      </div>
					                    </div>
										
					                    <div class="form-group">
					                      <label for="inputEmail" class="col-lg-2 control-label">Email</label>
					                      <div class="col-lg-10">
					                        <input type="text" class="form-control" id="inputEmail" placeholder="Email" name="email">
					                      </div>
					                    </div>
					                    
					                    <div class="form-group">
					                      <label for="shout" class="col-lg-2 control-label">Shout</label>
					                      <div class="col-lg-10">
					                        <textarea class="form-control" rows="3" id="shout" name="shout"></textarea>
					                        <span class="help-block">Enter the email addresses of the people you're shouting, one per line.</span>
					                      </div>
					                    </div>
					                    
					                    <div class="form-group">
					                      <label for="vendor" class="col-lg-2 control-label">Where</label>
					                      <div class="col-lg-10">
					                        <select class="form-control" id="vendor" name="vendor">
												<option value="-1">Please select</option>
											  <option>The Pantry</option>
					                          <option>Costa</option>
					                          <option>Subway</option>
					                        </select>					                        
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
											<label for="collect_date" class="col-lg-2 control-label">Date</label>
  					                          <div class="col-lg-10">
						                        <input type="text" class="form-control" id="collect_date" placeholder="dd/mm/yyyy" name="collect_date">
	  											</div>
											</div>
											<div class="form-group">
												<label for="collect_time" class="col-lg-2 control-label">Time</label>
											<div class="col-lg-10">
  						                        <input type="text" class="form-control" id="collect_time" placeholder="hh:mm" name="collect_time">
	  											</div>
										</div>
										
					                    <div class="form-group">
					                      <label for="deadline" class="col-lg-2 control-label">Deadline</label>
					                      <div class="col-lg-10">
					                        <select class="form-control" id="deadline" name="deadline">
											<option value="-1">Please select</option>
											  <option value="5">5 minutes before</option>
					                          <option value="15">15 minutes before</option>
					                          <option value="30">30 minutes before</option>
					                          <option value="60">1 hour before</option>
					                          <option value="180">3 hours before</option>
					                        </select>					                        
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
					              </div>
		            </div>
		          </div>
		        </div>
  	  	  	  <?php
  	  	  	  include '../inc/footer.php';
  	  	  	  ?>
		  
  	  		  </div>  
			
		
	 
   
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </body>
</html>

<html>