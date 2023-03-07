<?php 

require_once('./core/boot.php');
require_once('./core/functions.php');

if (isset($_POST['sq_ft']) && $_POST['sq_ft'] !== '') {

    // TODO: Submit to database
    $insertStatement = $link->prepare("INSERT INTO listings (title, description, price, rental_or_sale, active, sq_ft, listing_state, town, listing_description, photos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $rental_or_sale = isset($_POST['rental_or_sale']) ? $_POST['rental_or_sale'] : '';
    $active = isset($_POST['active']) ? $_POST['active'] : '';
    $sq_ft = isset($_POST['sq_ft']) ? $_POST['sq_ft'] : '';
    $listing_state = isset($_POST['listing_state']) ? $_POST['listing_state'] : '';
    $town = isset($_POST['town']) ? $_POST['town'] : '';
    $listing_description = isset($_POST['listing_description']) ? $_POST['listing_description'] : '';
    $photos = isset($_POST['photos']) ? $_POST['photos'] : '';

    $insertStatement->bind_param('ssdiisssss', $title, $description, $price, $rental_or_sale, $active, $sq_ft, $listing_state, $town, $listing_description, $photos);
    $insertStatement->execute();

    // TODO: Redirect user somewhere or show a message

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
          <link rel="stylesheet" href="assets/listyourplace.css">
        </head>
        <body>
          <div class="image-container set-full-height" style="background-image: url('assets/img/wizard-boat.jpg')">
            <!--   Creative Tim Branding   -->
            <a href="http://creative-tim.com">
              <div class="logo-container">
                <div class="logo">
                  <img src="assets/img/new_logo.png">
                </div>
                <div class="brand"> JustWaterfront Listing System </div>
              </div>
            </a>
            <!--   Big container   -->
            <div class="container">
              <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                  <!--      Wizard container        -->
                  <div class="wizard-container">
                    <div class="card wizard-card" data-color="azzure" id="wizard">
                      <form action="" method="post">
                        <!--        You can switch ' data-color="azzure" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->
                        <div class="wizard-header">
                          <h3>
                            <b>LIST</b> YOUR PLACE <br>
                            <small>Fill out this form to get your listing published on our site!</small>
                          </h3>
                        </div>
                        <div class="wizard-navigation">
                          <ul>
                            <li>
                              <a href="#captain" data-toggle="tab">Listing Type</a>
                            </li>
                            <li>
                              <a href="#details" data-toggle="tab">Details</a>
                            </li>
                            <li>
                              <a href="#description" data-toggle="tab">Description</a>
                            </li>
                            <li>
                              <a href="#photos" data-toggle="tab">Photos</a>
                            </li>
                            <li>
                              <a href="#userinfo" data-toggle="tab">User Info</a>
                            </li>
                            <li>
                              <a href="#paymentportal" data-toggle="tab">Checkout</a>
                            </li>
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
                                  <input type="text" class="form-control" name="sq_ft" id="exampleInputEmail1" placeholder="Ex. 4500?">
                                </div>
                              </div>
                              <div class="col-sm-5">
                                <div class="form-group">
                                  <label>What state is your listing in?</label>
                                  <select class="form-control" name="listing_state">
    <option disabled selected>- State -</option>
    <?php
        $listing_states = array(
            'Alabama',
            'Alaska',
            'Arizona',
            'Arkansas',
            'California',
            'Colorado',
            'Connecticut',
            'Delaware',
            'Florida',
            'Georgia',
            'Hawaii',
            'Idaho',
            'Illinois',
            'Indiana',
            'Iowa',
            'Kansas',
            'Kentucky',
            'Louisiana',
            'Maine',
            'Maryland',
            'Massachusetts',
            'Michigan',
            'Minnesota',
            'Mississippi',
            'Missouri',
            'Montana',
            'Nebraska',
            'Nevada',
            'New Hampshire',
            'New Jersey',
            'New Mexico',
            'New York',
            'North Carolina',
            'North Dakota',
            'Ohio',
            'Oklahoma',
            'Oregon',
            'Pennsylvania',
            'Rhode Island',
            'South Carolina',
            'South Dakota',
            'Tennessee',
            'Texas',
            'Utah',
            'Vermont',
            'Virginia',
            'Washington',
            'West Virginia',
            'Wisconsin',
            'Wyoming'
        );
        foreach ($listing_states as $listing_state) {
            echo '<option>' . $listing_state . '</option>';
        }
    ?>
</select>

                                </div>
                              </div>
                              <div class="col-sm-5 col-sm-offset-1">
                                <div class="form-group">
                                  <label>What town is your listing in?</label>
                                  <div class="input-group">
                                    <input type="text" name="town" class="form-control">
                                    <span class="input-group-addon"></span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-5">
                                <div class="form-group">
                                  <label>Listing Price</label>
                                  <div class="input-group">
                                    <input type="text" name="price" class="form-control">
                                    <span class="input-group-addon">$</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Checkout tab -->
                          <div class="tab-pane" id="userinfo">
                            <h4 class="info-text">User Info </h4>
                            <div class="row">
                              <div class="col-sm-10 col-sm-offset-1">
                                <div class="col-sm-4">
                                  <div class="form-group">
                                    <label for="rental">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName">
                                  </div>
                                </div>
                                <div class="col-sm-4">
                                  <div class="form-group">
                                    <label for="sale">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName">
                                  </div>
                                </div>
                                <div class="col-sm-4">
                                  <div class="form-group">
                                    <label for="other">Email Address</label>
                                    <input type="text" class="form-control" id="email" name="email">
                                  </div>
                                </div>
                                <!-- Temporary submit, will be replaced with checkout later -->
                                <div class="col-md-12">
                                  <div class="form-group"><input type="submit" value="Submit form"></div>
                                </div>
                                <!-- end of temp submit -->
                              </div>
                            </div>
                          </div>
                          <div class="tab-pane" id="photos">
                            <h4 class="info-text"> Upload Up to 25 photos! </h4>
                            <div class="row">
                              <div class="col-sm-10 col-sm-offset-1">
                                <form action="" method="POST" enctype="multipart/form-data">
                                  <input type="file" name="photos" multiple>
                                  <br>
                                  <br>
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
                                    <input type="radio" name="rental_or_sale" value="1">
                                    <div class="icon">
                                      <i class="fa fa-life-ring"></i>
                                    </div>
                                    <h6>Rental</h6>
                                  </div>
                                </div>
                                <div class="col-sm-4">
                                  <div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Select this option if this listing is a sale.">
                                    <input type="radio" name="rental_or_sale" value="2">
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
                                  <textarea class="form-control" name="listing_description" placeholder="Descripton..." rows="9"></textarea>
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
                </div>
                <!-- wizard container -->
              </div>
            </div>
            <!-- row -->
          </div>
          <!--  big container -->
          <div class="footer">
            <div class="container"> Made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim</a>. Free download <a href="http://www.creative-tim.com/product/bootstrap-wizard">here.</a>
            </div>
          </div>
          </div>
        </body>
        <!--   Core JS Files   -->
        <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/js/jquery.bootstrap.wizard.js" type="text/javascript"></script>
        <script>
          $(document).ready(function() {
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