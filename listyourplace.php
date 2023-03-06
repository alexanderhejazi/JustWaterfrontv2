<?php 

$captchaSiteKey = '6LdNijMdAAAAANctKNaUespZ-uPdjxFAVXUr_VYo';
$captchaSecretKey = '6LdNijMdAAAAAJ-h6RJEJSK3UGcQNzF9gzxMpepC';

function curlRequest($url)
{
    $ch = curl_init();
    $getUrl = $url;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $getUrl);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);
    
    $response = curl_exec($ch);
    return $response;
    
    curl_close($ch);
    
}

require 'core/boot.php';

if (isset($_POST['firstName'])) {

	// Check captcha

	$createGoogleUrl = 'https://www.google.com/recaptcha/api/siteverify?secret='.$captchaSecretKey.'&response='.$_POST['g-recaptcha-response'];
	$verifyRecaptcha = curlRequest($createGoogleUrl);
	$decodeGoogleResponse = json_decode($verifyRecaptcha,true);

	if($decodeGoogleResponse['success'] == 0)
	{
		echo "Captcha incorrect";
		die();
	}



	// Report filled in, make checks and add to database

	// If not logged in, create user


	

	$newUser = 0;

	if (!loggedIn()) {

		// If email already registered
		$check = $sql->sql()->prepare("SELECT * FROM users WHERE email = ?");
		$check->execute([$_POST['user-email']]);
		if ($check->rowCount()) {
			// Already registered user, send only report activation letter
			echo "Error - user already exists. Login before making a new report.";
			die();
		}

		$generatedPassword = generateStrongPassword();

		$user = $sql->sql()->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)");
		$user->execute([
		':firstName' => $_POST['user-firstname'],
		':lastName' => $_POST['user-lastname'],
		':email' => $_POST['user-email'],
		':password' => password_hash($generatedPassword, PASSWORD_DEFAULT)
		]);
		
		$_SESSION['user'] = $sql->lastId();
		$newUser = 1;
	}

		$emailCode = md5($_POST['email']+time());

		if(!isset($_POST['year'])){
			$_POST['year'] = date("Y");
		};

		if(!isset($_POST['month'])){
			$_POST['month'] = date("m");
		};


		$report = $sql->sql()->prepare("INSERT INTO reports (reporterID, firstName, lastName, email, store, phone, username, details, year, month, emailCode, address, second_address, city, state, zip, country) VALUES (:reporterID, :firstName, :lastName, :email, :store, :phone, :username, :details, :year, :month, :emailCode, :address, :second_address, :city, :state, :zip, :country)");
		$report->execute([
			'reporterID' => $_SESSION['user'],
			'firstName' => $_POST['firstName'],
			'lastName' => $_POST['lastName'],
			'email' => $_POST['email'],
			'store' => $_POST['store'],
			'phone' => $_POST['phone'],
			'username' => $_POST['username'],
			'details' => $_POST['details'],
			'year' => $_POST['year'],
			'month' => $_POST['month'],
			'emailCode' => $emailCode,
			'address' => $_POST['address'],
			'second_address' => $_POST['second_address'],
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'zip' => $_POST['zip'],
			'country' => $_POST['country']
		]);

		if ($newUser) {
			$message =
			"Dear {$_POST['user-firstname']} {$_POST['user-lastname']},<br>
			<br>
			Welcome to BadBuyer.com! Here's your account information:<br>
			<br>
			<b>E-mail: <i><a rel='nofollow' style='text-decoration:none; color:#333'>{$_POST['user-email']}</a></i></b><br>
			<b>Password: <i>{$generatedPassword}</i></b><br>
			<br>
			To activate your report on {$_POST['firstName']} {$_POST['lastName']} you need to click the following link:<br><br>
			<a href='http://badbuyer.com/activate.php?email={$_POST['user-email']}&code={$emailCode}'>badbuyer.com/activate.php?email={$_POST['user-email']}&code={$emailCode}</a><br>
			<br>
			If you desire we can send out a notification to your bad buyer for you, letting them know that they have been listed on our site and the only way possible for them to ever be removed from BadBuyer.com is if you decide as the listing party to remove them.
			It's simple! Just login via the link below, with your new account, and click the mail icon next to your reported buyer.<br>
			<br>
			<a href='http://badbuyer.com/account.php'>badbuyer.com/account.php</a><br>
			<br>
			Once again - welcome!<br>
			<br>
			- The staff at BadBuyer.com
			";

			sendmail($_POST['user-firstname'] . ' ' . $_POST['user-lastname'], $_POST['user-email'], "Welcome to JustWaterfront.com", $message);
		} else {
			$message =
			"Dear {$_POST['user-firstname']} {$_POST['user-lastname']},<br>
			<br>
			Thank you for submitting a listing to JustWaterfront.com!<br>
			<br>
			To activate your report, you need to click the following link:<br>
			<a href='badbuyer.com/activate.php?email={$_POST['user-email']}&code={$emailCode}'>badbuyer.com/activate.php?email={$_POST['user-email']}&code={$emailCode}</a><br>
			<br>
			If you desire we can send out a notification to your bad buyer for you, letting them know that they have been listed on our site and the only way possible for them to ever be removed from BadBuyer.com is if you decide as the listing party to remove them.
			It's simple! Just login via the link below, with your new account, and click the mail icon next to your reported buyer.<br>
			<br>
			<a href='http://badbuyer.com/account.php'>badbuyer.com/account.php</a><br>
			<br>
			- The staff at BadBuyer.com
			";

			sendmail($_POST['user-firstname'] . ' ' . $_POST['user-lastname'], $_POST['user-email'], "Your report is almost done!", $message);
		}

		header('Location: account.php?firstTime');
		die();

}

?>
    
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="assets/img/favicon.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>JustWaterfront.com Listing System</title>

        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />

        <!--     Fonts and icons     -->
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">

        <!-- CSS Files -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="assets/css/gsdk-bootstrap-wizard.css" rel="stylesheet" />

        <!-- CSS Just for demo purpose, don't include it in your project -->
        <link href="assets/css/demo.css" rel="stylesheet" />
        <style>/*basic reset*/
            * {
                margin: 0;
                padding: 0;
            }
            
            html {
                height: 100%;
                background: #6441A5; /* fallback for old browsers */
                background: -webkit-linear-gradient(to left, #6441A5, #2a0845); /* Chrome 10-25, Safari 5.1-6 */
            }
            
            body {
                font-family: montserrat, arial, verdana;
                background: transparent;
            }
            
            /*form styles*/
            #msform {
                text-align: center;
                position: relative;
                margin-top: 30px;
            }
            
            #msform fieldset {
                background: white;
                border: 0 none;
                border-radius: 0px;
                box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
                padding: 20px 30px;
                box-sizing: border-box;
                width: 80%;
                margin: 0 10%;
            
                /*stacking fieldsets above each other*/
                position: relative;
            }
            
            /*Hide all except first fieldset*/
            #msform fieldset:not(:first-of-type) {
                display: none;
            }
            
            /*inputs*/
            #msform input, #msform textarea {
                padding: 15px;
                border: 1px solid #ccc;
                border-radius: 0px;
                margin-bottom: 10px;
                width: 100%;
                box-sizing: border-box;
                font-family: montserrat;
                color: #2C3E50;
                font-size: 13px;
            }
            
            #msform input:focus, #msform textarea:focus {
                -moz-box-shadow: none !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
                border: 1px solid #ee0979;
                outline-width: 0;
                transition: All 0.5s ease-in;
                -webkit-transition: All 0.5s ease-in;
                -moz-transition: All 0.5s ease-in;
                -o-transition: All 0.5s ease-in;
            }
            
            /*buttons*/
            #msform .action-button {
                width: 100px;
                background: #ee0979;
                font-weight: bold;
                color: white;
                border: 0 none;
                border-radius: 25px;
                cursor: pointer;
                padding: 10px 5px;
                margin: 10px 5px;
            }
            
            #msform .action-button:hover, #msform .action-button:focus {
                box-shadow: 0 0 0 2px white, 0 0 0 3px #ee0979;
            }
            
            #msform .action-button-previous {
                width: 100px;
                background: #C5C5F1;
                font-weight: bold;
                color: white;
                border: 0 none;
                border-radius: 25px;
                cursor: pointer;
                padding: 10px 5px;
                margin: 10px 5px;
            }
            
            #msform .action-button-previous:hover, #msform .action-button-previous:focus {
                box-shadow: 0 0 0 2px white, 0 0 0 3px #C5C5F1;
            }
            
            /*headings*/
            .fs-title {
                font-size: 18px;
                text-transform: uppercase;
                color: #2C3E50;
                margin-bottom: 10px;
                letter-spacing: 2px;
                font-weight: bold;
            }
            
            .fs-subtitle {
                font-weight: normal;
                font-size: 13px;
                color: #666;
                margin-bottom: 20px;
            }
            
            /*progressbar*/
            #progressbar {
                margin-bottom: 30px;
                overflow: hidden;
                /*CSS counters to number the steps*/
                counter-reset: step;
            }
            
            #progressbar li {
                list-style-type: none;
                color: white;
                text-transform: uppercase;
                font-size: 9px;
                width: 33.33%;
                float: left;
                position: relative;
                letter-spacing: 1px;
            }
            
            #progressbar li:before {
                content: counter(step);
                counter-increment: step;
                width: 24px;
                height: 24px;
                line-height: 26px;
                display: block;
                font-size: 12px;
                color: #333;
                background: white;
                border-radius: 25px;
                margin: 0 auto 10px auto;
            }
            
            /*progressbar connectors*/
            #progressbar li:after {
                content: '';
                width: 100%;
                height: 2px;
                background: white;
                position: absolute;
                left: -50%;
                top: 9px;
                z-index: -1; /*put it behind the numbers*/
            }
            
            #progressbar li:first-child:after {
                /*connector not needed before the first step*/
                content: none;
            }
            
            /*marking active/completed steps green*/
            /*The number of the step and the connector before it = green*/
            #progressbar li.active:before, #progressbar li.active:after {
                background: #ee0979;
                color: white;
            }
            
            
            /* Not relevant to this form */
            .dme_link {
                margin-top: 30px;
                text-align: center;
            }
            .dme_link a {
                background: #FFF;
                font-weight: bold;
                color: #ee0979;
                border: 0 none;
                border-radius: 25px;
                cursor: pointer;
                padding: 5px 25px;
                font-size: 12px;
            }
            
            .dme_link a:hover, .dme_link a:focus {
                background: #C5C5F1;
                text-decoration: none;
            }</style>

    </head>

    <body>
    <div class="image-container set-full-height" style="background-image: url('assets/img/wizard-boat.jpg')">
        <!--   Creative Tim Branding   -->
        <a href="http://creative-tim.com">
            <div class="logo-container">
                <div class="logo">
                    <img src="assets/img/new_logo.png">
                </div>
                <div class="brand">
                    JustWaterfront Listing System
                </div>
            </div>
        </a>

        <!--   Big container   -->
        <div class="container">
            <div class="row">
            <div class="col-sm-8 col-sm-offset-2">

                <!--      Wizard container        -->
                <div class="wizard-container">
                    <div class="card wizard-card" data-color="azzure" id="wizard">
                        <form action="process-form.php" method="post">
                    <!--        You can switch ' data-color="azzure" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->

                            <div class="wizard-header">
                                <h3>
                                <b>LIST</b> YOUR PLACE <br>
                                <small>Fill out this form to get your listing published on our site!</small>
                                </h3>
                              
                            </div>
                            <div class="wizard-navigation">
                                <ul>
                                    <li><a href="#captain" data-toggle="tab">Listing Type</a></li>
                                    <li><a href="#details" data-toggle="tab">Details</a></li>
                                    <li><a href="#description" data-toggle="tab">Description</a></li>
                                    <li><a href="#photos" data-toggle="tab">Photos</a></li>
                                    <li><a href="#userinfo" data-toggle="tab">User Info</a></li>
                                    <li><a href="#paymentportal" data-toggle="tab">Checkout</a></li>
                                    <div class="wizard-buttons">
        <button type="button" class="btn btn-previous">Previous</button>
        <button type="button" class="btn btn-next">Next</button>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane" id="details">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="info-text"> Let's start with the basic details</h4>
                                    </div>
                                    <div class="col-sm-5 col-sm-offset-1">
                                        <div class="form-group">
                                            <label>How Many Sq. Ft. ?</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Ex. 4500?">
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>What state is your listing in?</label>
                                            <select class="form-control">
                                                <option disabled="" selected="">- State -</option>
                                                <option>Alabama</option>
    <option>Alaska</option>
    <option>Arizona</option>
    <option>Arkansas</option>
    <option>California</option>
    <option>Colorado</option>
    <option>Connecticut</option>
    <option>Delaware</option>
    <option>Florida</option>
    <option>Georgia</option>
    <option>Hawaii</option>
    <option>Idaho</option>
    <option>Illinois</option>
    <option>Indiana</option>
    <option>Iowa</option>
    <option>Kansas</option>
    <option>Kentucky</option>
    <option>Louisiana</option>
    <option>Maine</option>
    <option>Maryland</option>
    <option>Massachusetts</option>
    <option>Michigan</option>
    <option>Minnesota</option>
    <option>Mississippi</option>
    <option>Missouri</option>
    <option>Montana</option>
    <option>Nebraska</option>
    <option>Nevada</option>
    <option>New Hampshire</option>
    <option>New Jersey</option>
    <option>New Mexico</option>
    <option>New York</option>
    <option>North Carolina</option>
    <option>North Dakota</option>
    <option>Ohio</option>
    <option>Oklahoma</option>
    <option>Oregon</option>
    <option>Pennsylvania</option>
    <option>Rhode Island</option>
    <option>South Carolina</option>
    <option>South Dakota</option>
    <option>Tennessee</option>
    <option>Texas</option>
    <option>Utah</option>
    <option>Vermont</option>
    <option>Virginia</option>
    <option>Washington</option>
    <option>West Virginia</option>
    <option>Wisconsin</option>
    <option>Wyoming</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 col-sm-offset-1">
                                        <div class="form-group">
                                            <label>What town is your listing in?</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control">
                                                <span class="input-group-addon"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Listing Price</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control">
                                                <span class="input-group-addon">$</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="tab-pane" id="paymentportal">
                                    <h4 class="info-text"> Payment Portal </h4>
                                    <div class="row">
                                      <div class="col-sm-10 col-sm-offset-1">
                                        <!-- Replace "test" with your own sandbox Business account app client ID -->
    <script src="https://www.paypal.com/sdk/js?client-id=AVjxIXPhpRb9KVFi9Fp8Sw2g1an_rBjtbMTn0J4bRHd2L1KtBgRs_2OVoChPy37AgXzSkEisEjXqmi_x&currency=USD"></script>
    <!-- Set up a container element for the button -->
    <div id="paypal-button-container"></div>
    <script>
      paypal.Buttons({
        // Order is created on the server and the order id is returned
        createOrder() {
          return fetch("/my-server/create-paypal-order", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            // use the "body" param to optionally pass additional order information
            // like product skus and quantities
            body: JSON.stringify({
              cart: [
                {
                  sku: "YOUR_PRODUCT_STOCK_KEEPING_UNIT",
                  quantity: "YOUR_PRODUCT_QUANTITY",
                },
              ],
            }),
          })
          .then((response) => response.json())
          .then((order) => order.id);
        },
        // Finalize the transaction on the server after payer approval
        onApprove(data) {
          return fetch("/my-server/capture-paypal-order", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              orderID: data.orderID
            })
          })
          .then((response) => response.json())
          .then((orderData) => {
            // Successful capture! For dev/demo purposes:
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            const transaction = orderData.purchase_units[0].payments.captures[0];
            alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
            // When ready to go live, remove the alert and show a success message within this page. For example:
            // const element = document.getElementById('paypal-button-container');
            // element.innerHTML = '<h3>Thank you for your payment!</h3>';
            // Or go to another URL:  window.location.href = 'thank_you.html';
          });
        }
      }).render('#paypal-button-container');
    </script>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="tab-pane" id="userinfo">
  <h4 class="info-text">User Info </h4>
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1">
      <div class="col-sm-4">
        <div class="form-group">
          <label for="rental">First Name</label>
          <input type="text" class="form-control" id="firstName" name="rental">
        </div>
      </div>
      <div class="col-sm-4">
        <div class="form-group">
          <label for="sale">Last Name</label>
          <input type="text" class="form-control" id="lastName" name="sale">
        </div>
      </div>
      <div class="col-sm-4">
        <div class="form-group">
          <label for="other">Email Address</label>
          <input type="text" class="form-control" id="email" name="other">
        </div>
      </div>
    </div>
  </div>
</div>

                                  <div class="tab-pane" id="photos">
                                    <h4 class="info-text"> Upload Up to 25 photos! </h4>
                                    <div class="row">
                                      <div class="col-sm-10 col-sm-offset-1">
                                        <form action="" method="post" enctype="multipart/form-data">
                                          <input type="file" name="photo[]" multiple>
                                          <br><br>
                                          <button class="btn btn-success btn-fill" type="submit" name="submit">Upload</button>
                                        </form>
                                      </div>
                                    </div>
                                  </div>                                  
                                <div class="tab-pane" id="captain">
                                    <h4 class="info-text">What type of listing is this? </h4>
                                    
                                    <div class="row">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <div class="col-sm-4 col-sm-offset-2">
                                                <div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Select this option if this listing is a rental.">
                                                    <input type="radio" name="job" value="1">
                                                    <div class="icon">
                                                        <i class="fa fa-life-ring"></i>
                                                    </div>
                                                    <h6>Rental</h6>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Select this option if this listing is a sale.">
                                                    <input type="radio" name="job" value="2">
                                                    <div class="icon">
                                                        <i class="fa fa-male"></i>
                                                        
                                                    </div>
                                                    <h6>Sale</h6>
                                                    
                                                </div>
                                            </div>
            
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="description">
                                    <div class="row">
                                        <h4 class="info-text"> Craft a short description </h4>
                                        <div class="col-sm-6 col-sm-offset-1">
                                            <div class="form-group">
                                                <label>Listing Description</label>
                                                <textarea class="form-control" placeholder="" rows="9">
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Example</label>
                                                <p class="description">"This is a stunning 3 bedroom unit, with amazing views of the waterfront!"</p>

                                                <div class="wizard-buttons">
        <button type="button" class="btn btn-previous">Previous</button>
        <button type="button" class="btn btn-next">Next</button>
    </div>
</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div> <!-- wizard container -->
            </div>
            </div> <!-- row -->
        </div> <!--  big container -->
       

        <div class="footer">
            <div class="container">
                Made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim</a>. Free download <a href="http://www.creative-tim.com/product/bootstrap-wizard">here.</a>
            </div>
        </div>


    </div>

    </body>

        <!--   Core JS Files   -->
        <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

        <script> $(document).ready(function(){
    // Initialize wizard
    $('#wizard').bootstrapWizard();
    
    // Handle previous button click
    $('.btn-previous').click(function() {
        $('#wizard').bootstrapWizard('previous');
    });
    
    // Handle next button click
    $('.btn-next').click(function() {
        $('#wizard').bootstrapWizard('next');
    });
});
</script>

        <!--  Plugin for the Wizard -->
        <script src="assets/js/gsdk-bootstrap-wizard.js"></script>

        <!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
        <script src="assets/js/jquery.validate.min.js"></script>

    </html>
