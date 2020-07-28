<?php
   if (isset($_POST["submit"])) {
       // Checking For Blank Fields..
       if ($_POST["Name"]==""||$_POST["EMail"]==""||$_POST["Subject"]==""||
       $_POST["Message"]=="") {
           //echo "Fill All Fields..";
         ?>
<script>alert('Fill All Fields..!')</script>
<?php
       } else {
           // Check if the "Sender's Email" input field is filled out
           $EMail=$_POST['EMail'];
           // Sanitize E-mail Address
           $EMail =filter_var($EMail, FILTER_SANITIZE_EMAIL);
           // Validate E-mail Address
           $EMail= filter_var($EMail, FILTER_VALIDATE_EMAIL);
           if (!$EMail) {
               //echo "Invalid Sender's Email";
   ?>
<script>alert('Invalid Email')</script>
<?php
           } else {
               $Name = $_POST['Name'];
               $EMail = $_POST['EMail'];
               $Subject = $_POST['Subject'];
               $Message = $_POST['Message'];


               //$headers = 'From:'. $email2 . "\r\n"; // Sender's Email
               //$headers .= 'Cc:'. $email2 . "\r\n"; // Carbon copy to Sender

               // $headers .= 'From:'.$Email. "\r\n";
               $headers = 'Reply-To:'.$EMail."\r\n";


               // Message lines should not exceed 70 characters (PHP rule), so wrap it
               $message = wordwrap($Message, 70);


               // Send Mail By PHP Mail Function
               mail("CONTACT@REVIEWIT.SITE", $Subject, $message, $headers);
               //echo "Your mail has been sent successfuly ! Thank you for your feedback";
   ?>
<script>alert('Thanks for contacting us. We will get back to you at the earliest')</script>
<?php
           }
       }
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="ReviewIt -  It's Time to Make Right Choice">
      <meta name="author" content="">
      <title>ReviewIt</title>
      <!-- Bootstrap core CSS -->
      <link href="<?php echo base_url().'assets/site/';?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <!-- Custom fonts for this template -->
      <link rel="stylesheet" href="<?php echo base_url().'assets/site/';?>vendor/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" href="<?php echo base_url().'assets/site/';?>vendor/simple-line-icons/css/simple-line-icons.css">
      <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
      <!-- Plugin CSS -->
      <link rel="stylesheet" href="<?php echo base_url().'assets/site/';?>device-mockups/device-mockups.min.css">
      <!-- Custom styles for this template -->
      <link href="<?php echo base_url().'assets/site/';?>css/new-age.min.css" rel="stylesheet">
      <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url().'assets/site/favicon/';?>/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url().'assets/site/favicon/';?>/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url().'assets/site/favicon/';?>/favicon-16x16.png">
      <link rel="manifest" href="<?php echo base_url().'assets/site/favicon/';?>/manifest.json">
      <link rel="mask-icon" href="<?php echo base_url().'assets/site/favicon/';?>/safari-pinned-tab.svg" color="#5bbad5">
      <meta name="theme-color" content="#ffffff">
   </head>
   <body id="page-top">
      <!-- Navigation -->
      <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
         <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="#page-top"><b>ReviewIt</b></a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
               <ul class="navbar-nav ml-auto">
                  <li class="nav-item">
                     <a class="nav-link js-scroll-trigger" href="#download">Download</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link js-scroll-trigger" href="#features">Features</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link js-scroll-trigger" href="#contact">Contact</a>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
      <header class="masthead">
         <div class="container h-100">
            <div class="row h-100">
               <div class="col-lg-7 my-auto">
                  <div class="header-content mx-auto">
                     <h1 class="mb-5">Right place to look for Companies, Jobs & Talent.</h1>
                     <a href="#download" class="btn btn-outline btn-xl js-scroll-trigger">Download Now!</a>
                  </div>
               </div>
               <div class="col-lg-5 my-auto">
                  <div class="device-container">
                     <div class="device-mockup iphone6_plus portrait white">
                        <div class="device">
                           <div class="screen">
                              <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
                              <img src="<?php echo base_url().'assets/site/';?>img/demo-screen-0.png" class="img-fluid" alt="">
                           </div>
                           <div class="button">
                              <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </header>
      <section class="download bg-primary text-center" id="download">
         <div class="container">
            <div class="row">
               <div class="col-md-10 mx-auto">
                  <h2 class="section-heading">It's Time to Make Right Choice</h2>
                  </br>
                  <p class="text">"Review It" is a company discovery platform for International Students and Graduates for finding the right company. The collaborative platform allows users to provide Company reviews, share Job experience, communicate with each other and share Job Opportunities. For recruiters, it is right place to find the talent and for Job seekers, it is right place to find opportunities.</p>
                  </br></br>
                  <p>Our app is available on any mobile device! Download now to get started!</p>
                  <div class="badges">
                     <a class="badge-link" href="https://play.google.com/store/apps/details?id=com.reviewit.info.android"><img src="<?php echo base_url().'assets/site/';?>img/google-play-badge.svg" alt=""></a>
                     <a class="badge-link" href="#"><img src="<?php echo base_url().'assets/site/';?>img/ios_coming.png" alt=""></a>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <section class="features" id="features">
         <div class="container">
         <div class="section-heading text-center">
            <h2>APP FEATURES</h2>
            <p class="text-muted">Check out what you can do with this app!</p>
            <hr>
         </div>
         <div class="row">
            <div class="col-lg-3 my-auto">
               <div class="device-container">
                  <div class="device-mockup iphone6_plus portrait white">
                     <div class="device">
                        <div class="screen">
                           <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
                           <img src="<?php echo base_url().'assets/site/';?>img/demo-screen-4.png" class="img-fluid" alt="">="">
                        </div>
                        <div class="button">
                           <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-9 my-auto">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-lg-4">
                        <div class="feature-item">
                           <i class="fa fa-hand-o-right text-primary"></i>
                           <h4>RIGHT CHOICE</h4>
                           <p class="text-muted">Our algorithm provide the right options so that students make wise decisions!</p>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="feature-item">
                           <i class="fa fa-compass text-primary"></i>
                           <h4>LOCATION SEARCH</h4>
                           <p class="text-muted">Find the Right company based on your current location and your criteria.</p>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="feature-item">
                           <i class="fa fa-comments text-primary"></i>
                           <h4>STAY CONNECTED</h4>
                           <p class="text-muted">Graduates and Recruiters stay connected through our messaging system</p>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-4">
                        <div class="feature-item">
                           <i class="fa fa-address-card text-primary"></i>
                           <h4>EXPLORE TALENT</h4>
                           <p class="text-muted">Great place for Recruiters and Alumni to find right talent</p>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="feature-item">
                           <i class="fa fa-comments text-primary"></i>
                           <h4>SHARE</h4>
                           <p class="text-muted">Share reviews and Company profile with Friends, Family and Colleagues.</p>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="feature-item">
                           <i class="fa fa-address-card text-primary"></i>
                           <h4>INSIGHT</h4>
                           <p class="text-muted">More Insights on Individual functionalities of each Employer apart from Overall rating.</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <section class="cta">
         <div class="cta-content">
            <div class="container">
               <h2>Explore, Communicate and Collaborate.</h2>
               <a href="#contact" class="btn btn-outline btn-xl js-scroll-trigger">Let's Get Started!</a>
            </div>
         </div>
         <div class="overlay"></div>
      </section>
      <section id="contact">
         <div class="container">
            <div class="row">
               <div class="col-lg-12 text-center">
                  <h2 class="section-heading text-uppercase">Contact Us</h2>
                  </br>
                  <!-- <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3> -->
               </div>
            </div>
            <div class="row">
               <div class="col-lg-12">
                  <form action="" name="Form1" method="post">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <input class="form-control" id="name" type="text" name="Name" placeholder="Your Name *" required data-validation-required-message="Please enter your name.">
                              <p class="help-block text-danger"></p>
                           </div>
                           <div class="form-group">
                              <input class="form-control" name="EMail" id="email" type="email" placeholder="Your Email *" required data-validation-required-message="Please enter your email address.">
                              <p class="help-block text-danger"></p>
                           </div>
                           <div class="form-group">
                              <input class="form-control" id="phone" name="Subject" id="subject" type="text" placeholder="Your Subject *" required data-validation-required-message="Please enter your subject.">
                              <p class="help-block text-danger"></p>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <textarea class="form-control" name="Message" id="message" rows="6" placeholder="Your Message *" required data-validation-required-message="Please enter a message."></textarea>
                              <p class="help-block text-danger"></p>
                           </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12 text-center">
                           <div id="success"></div>
                           <input type="submit" id="submit" name="submit" value="Send Message" class="btn btn-primary btn-xl text-uppercase" />
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="row">
            </div>
         </div>
      </section>
      <section class="contact bg-primary" id="contact_">
         <div class="container">
            <h2>Follow us now!</h2>
            <ul class="list-inline list-social">
               <li class="list-inline-item social-twitter">
                  <a href="https://twitter.com/FollowReviewIt">
                  <i class="fa fa-twitter"></i>
                  </a>
               </li>
               <li class="list-inline-item social-facebook">
                  <a href="https://www.facebook.com/LikeReviewIt/">
                  <i class="fa fa-facebook"></i>
                  </a>
               </li>
               <li class="list-inline-item social-linkedin">
                  <a href="https://www.linkedin.com/company/7947982/">
                  <i class="fa fa-linkedin"></i>
                  </a>
               </li>
            </ul>
         </div>
      </section>
      <footer>
         <div class="container">
            <p>&copy; 2018 Review It • All Rights Reserved.</p>
            <ul class="list-inline">
               <li class="list-inline-item">
                  <a href="privacy">Privacy</a>
               </li>
               <li class="list-inline-item">
                  <a href="termsconditions">Terms</a>
               </li>
               <!-- <li class="list-inline-item">
                  <a href="#">FAQ</a>
                  </li> -->
            </ul>
         </div>
      </footer>
      <!-- Bootstrap core JavaScript -->
      <script src="<?php echo base_url().'assets/site/';?>vendor/jquery/jquery.min.js"></script>
      <script src="<?php echo base_url().'assets/site/';?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- Plugin JavaScript -->
      <script src="<?php echo base_url().'assets/site/';?>vendor/jquery-easing/jquery.easing.min.js"></script>
      <!-- Custom scripts for this template -->
      <script src="<?php echo base_url().'assets/site/';?>js/new-age.min.js"></script>
   </body>
</html>
