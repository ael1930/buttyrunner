<?php
require_once('../inc/config.php');
require_once('../inc/connectionOpen.php');

if(count( $_GET) > 0)
{
		$result = $mysqli->query('SELECT * FROM buttyrun WHERE id = "' . $_GET['b'] . '"');
		$row = $result->fetch_assoc();
		
		$vendor = $row["vendor"];
		$collect = strtotime($row["collect"]);
		$collect_date = date('d/m/Y', $collect);
		$collect_time = date('H:i', $collect);
		$deadline = strtotime($row["deadline"]);
		$deadline_date = date('d/m/Y', $deadline);
		$deadline_time = date('H:i', $deadline);
		
		$result->close();
		
		if(mktime() < $deadline){
			require_once("../inc/connectionClose.php");
	
			header('Location: ' . $baseUrl . 'order/too-late');
			exit();
		}
}

if(count( $_POST) > 0)
{
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
	
	// Send email to others
	$result = $mysqli->query('SELECT * FROM buttyrun WHERE id = "' . $_POST["buttyrun_id"] . '"');
	$row = $result->fetch_assoc();
	$vendor = $row["vendor"];
	$collect = strtotime($row["collect"]);
	$collect_date = date('d/m/Y', $collect);
	$collect_time = date('H:i', $collect);
	$deadline = strtotime($row["deadline"]);
	$deadline_date = date('d/m/Y', $deadline);
	$deadline_time = date('H:i', $deadline);
	$result->close();
	
	$subject = 'There\'s a ButtyRun to ' . $vendor . ' on ' . $collect_date . ' at ' . $collect_time;	
	$message = 'Hi,' . "\r\n\r\n" . 'There\'s a ButtyRun to ' . $vendor . ' on ' . $collect_date . ' at ' . $collect_time . ' that I thought you might be interested in.' . "\r\n\r\n" . 'You can place your order at the link below until ' . $deadline_time . '.' . "\r\n\r\n" . $baseUrl . 'order/?b=' . $_POST["buttyrun_id"] . "\r\n\r\n" . 'Has someone missed out? Simply forward this email to them and they can join in too.' . "\r\n\r\n" . trim($_POST['name']) . "\r\n\r\n" . '--' . "\r\n". 'www.ButtyRunner.com' . "\r\n". 'Follow us on Twitter at http://twitter.com/buttyrunner';	
	$headers = 'From: '. trim($_POST['email']) . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	$invitees = explode("\r\n", trim($_POST['shout']));
	
	foreach($invitees as $invite){
		if(!$devMode)
		{
			mail(trim($invite), $subject, $message, $headers);
		}
	}
	
	require_once("../inc/connectionClose.php");
	
	header('Location: ' . $baseUrl . 'order/thanks');
	exit();
}
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order | ButtyRunner (alpha)</title> <!-- Bootstrap -->
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
	  include_once("../inc/ga.php");
	  include_once("../inc/navigation.php");
	  ?>
		  
	  <div class="container">
	  <h1>Order</h1>
	  </div>
	  
	  <div class="container">
		
		  <div class="row">
       		  <div class="col-md-8">
				  
			  	  <p>There's a ButtyRun to <b><?=$vendor?></b> on <b><?=$collect_date?></b> at <b><?=$collect_time?></b>. You can place your order using the form below until <b><?=$deadline_date?></b> at <b><?=$deadline_time?></b>.</p>
				  	
					  
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
					                      <label for="shout" class="col-lg-2 control-label">Invite others</label>
					                      <div class="col-lg-10">
					                        <textarea class="form-control" rows="3" id="shout" name="shout"></textarea>
					                        <span class="help-block">Enter the email addresses of other people you're inviting, one per line.</span>
					                      </div>
					                    </div>
										
					                    <div class="form-group">
					                      <div class="col-lg-10 col-lg-offset-2">
					                        <button class="btn btn-default">Cancel</button>
					                        <button type="submit" class="btn btn-primary">Submit</button>
											<p style="font-size: 8px; margin-top: 10px;">* By clicking submit you agree to recieve emails from ButtyRunner for the purpose of running the system and a few other emails from us, certainly not from third parties though. You also take responsibilty for the emails generated to your invitees. We only store the email addresses of uses who've ordered via ButtyRun.</p>
					                      </div>
					                    </div>
										
									</fieldset>
								</form>
							</div
					  </div>

		
				  </div>
        		  <div class="col-md-4">Some ads will be here soon.</div>
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
<?php
require_once("../inc/connectionClose.php");	
?>