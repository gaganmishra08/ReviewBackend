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


<?php
if (isset($_POST["submit1"])) {
    // Checking For Blank Fields..
    if ($_POST["email"]=="") {
        //echo "Fill All Fields..";
      ?>
      <script>alert('Enter your mail address')</script>
      <?php
    } else {
        // Check if the "Sender's Email" input field is filled out
        $email=$_POST['email'];
        // Sanitize E-mail Address
        $email =filter_var($email, FILTER_SANITIZE_EMAIL);
        // Validate E-mail Address
        $email= filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            //echo "Invalid Sender's Email";
    ?>
    <script>alert('Enter your mail address')</script>
    <?php
        } else {
            $email = $_POST['email'];

            //$headers = 'From:'. $email2 . "\r\n"; // Sender's Email
            //$headers .= 'Cc:'. $email2 . "\r\n"; // Carbon copy to Sender
            $Subject= $email ;
            //$headers .= 'From: <no-reply@abc.com>' . "\r\n";
            $headers .= 'Reply-To:'.$email."\r\n";


            // Message lines should not exceed 70 characters (PHP rule), so wrap it
            $message = wordwrap($Message, 70);


            // Send Mail By PHP Mail Function
    mail("subscribe@reviewit.site", $Subject, $message, $headers)

    //echo "Your mail has been sent successfuly ! Thank you for your feedback";
     ?>
      <script>alert('Thanks for Subscribing with Us');
      //window.location = 'contact-form.html';
	  </script>
     <?php
        }
    }
}
?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="Description" CONTENT="Job Opportunities for Students">
<title>Review It</title>
<meta name="robots" content="index,all" />
<!--default style sheet link portion start here-->
<link href="<?php echo base_url().'assets/site/';?>css/default_css/main_style.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url().'assets/site/';?>css/default_css/responsive.css" rel="stylesheet" type="text/css">
<!--default style sheet link portion end here-->

<!--page smooth scroll animation js link start here-->
<script src="<?php echo base_url().'assets/site/';?>js/page_smooth_scroll_animation_js/SmoothScroll.js"></script>
<!--page smooth scroll animation js link end here-->

<!--banner css & js link start here-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.js"></script>

<script type="text/javascript" src="<?php echo base_url().'assets/site/';?>js/banner_js/jquery.themepunch.plugins.min.js"></script>

<script type="text/javascript" src="<?php echo base_url().'assets/site/';?>js/banner_js/jquery.themepunch.revolution.min.js">
</script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/site/';?>css/banner_css/settings.css" media="screen" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/site/';?>css/banner_css/style.css" media="screen" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/site/';?>css/banner_css/extralayers.css" media="screen" />
<!--banner css & js link end here-->

<!--bootstrap link start here-->
<link rel="stylesheet" href="<?php echo base_url().'assets/site/';?>css/bootstrap_css/bootstrap.min.css" type="text/css">
<script type="text/javascript" src="<?php echo base_url().'assets/site/';?>js/bootstrap_js/bootstrap.min.js"></script>
<!--bootstrap link end here-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-73295445-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body>
<!--banner portion start here-->
<div class="banner_bg">
<div class="main_con">

<!--header portion start here-->
<div class="header_left_section">
<img src="<?php echo base_url().'assets/site/';?>images/review_it_logo.png" alt="Logo" />
</div>

<div class="header_right_section">
<p class="header_mail">
<a href="mailto:contact@reviewit.site" target="_top">contact@reviewit.site</a></p>

<div class="cleared reset-box"></div>
</div>
<!--header portion end here-->
<div class="cleared reset-box"></div>


<!--slider portion start here-->
<div class="tp-banner-container">
		<div class="tp-banner" >


            <ul>
<!-- SLIDE  -->
<li data-transition="fade" data-slotamount="7" data-masterspeed="500" data-thumb="homeslider_thumb1.jpg"  data-saveperformance="on"  data-title="Intro Slide">
		<!-- MAIN IMAGE -->
		<img src="<?php echo base_url().'assets/site/';?>images/banner_images/dummy.png"  alt="slidebg1" data-lazyload="<?php echo base_url().'assets/site/';?>images/banner_images/slidebg1.png" data-bgposition="center top" data-bgfit="contain" data-bgrepeat="no-repeat">
		<!-- LAYERS -->

		<!-- LAYER NR. 4 -->
		<div class="tp-caption skewfromrightshort slider_text_1"
			data-x="40"
			data-y="100"
			data-speed="500"
			data-start="2250"
			data-easing="Power3.easeInOut"
			data-splitin="chars"
			data-splitout="none"
			data-elementdelay="0.1"
			data-endelementdelay="0.1"
			style="z-index: 5;
            max-width: auto;
            max-height: auto;
            white-space: nowrap;">
            It's Time to Make Right Choice
		</div>

		<!-- LAYER NR. 5 -->
		<div class="tp-caption customin rs-parallaxlevel-0"
			data-x="50"
			data-y="230"
			data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
			data-speed="500"
			data-start="2000"
			data-easing="Power3.easeInOut"
			data-elementdelay="0.1"
			data-endelementdelay="0.1"
			style="z-index: 6;"><a href="#"><img src="<?php echo base_url().'assets/site/';?>images/banner_images/dummy.png" alt="" data-lazyload="<?php echo base_url().'assets/site/';?>images/banner_images/redbg_.png"></a>
		</div>
        <!-- LAYER NR. 9 -->

        <div class="tp-caption customin rs-parallaxlevel-0"
			data-x="320"
			data-y="230"
			data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
			data-speed="500"
			data-start="2000"
			data-easing="Power3.easeInOut"
			data-elementdelay="0.1"
			data-endelementdelay="0.1"
			style="z-index: 6;"><a href="https://play.google.com/store/apps/details?id=com.reviewit.info.android"><img src="<?php echo base_url().'assets/site/';?>images/banner_images/dummy.png" alt="" data-lazyload="<?php echo base_url().'assets/site/';?>images/banner_images/redbg2.svg"></a>
		</div>
        <!-- LAYER NR. 9 -->


		<!-- LAYER NR. 9 -->
		<!--<div class="tp-caption slider_text_3"
			data-x="45"
			data-y="180"
			data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;"
			data-speed="500"
			data-start="2350"
			data-easing="Back.easeOut"
			data-splitin="none"
			data-splitout="none"
			data-elementdelay="0.1"
			data-endelementdelay="0.1"
			style="z-index: 10; max-width: auto; max-height: auto; white-space: nowrap;">
Review It” is a company discovery platform for International Students and Graduates for<br />
finding the right company. The collaborative platform allows users to provide Company<br />
reviews, share Job experience, communicate with each other and share Job seekers,<br />
Opportunities. For recruiters or alumni, it is right place to find the talent and for Job<br />
it is right place to find opportunities.
		</div>-->

		<!-- LAYER NR. 10 -->

	</li>

<!-- SLIDE  -->
<li data-transition="fade" data-slotamount="7" data-masterspeed="500" data-thumb="homeslider_thumb1.jpg"  data-saveperformance="on"  data-title="Intro Slide">
		<!-- MAIN IMAGE -->
		<img src="<?php echo base_url().'assets/site/';?>images/banner_images/dummy.png"  alt="3dbg" data-lazyload="<?php echo base_url().'assets/site/';?>images/banner_images/3dbg.png" data-bgposition="center top" data-bgfit="contain" data-bgrepeat="no-repeat">
		<!-- LAYERS -->

		<!-- LAYER NR. 4 -->
		<div class="tp-caption skewfromrightshort slider_text_1"
			data-x="600"
			data-y="100"
			data-speed="500"
			data-start="2250"
			data-easing="Power3.easeInOut"
			data-splitin="chars"
			data-splitout="none"
			data-elementdelay="0.1"
			data-endelementdelay="0.1"
			style="z-index: 5;
            max-width: auto;
            max-height: auto;
            white-space: nowrap;">
             It's Time to Make Right Choice <br/>

		</div>

		<!-- LAYER NR. 5 -->
		<div class="tp-caption customin rs-parallaxlevel-0"
			data-x="590"
			data-y="230"
			data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
			data-speed="500"
			data-start="2000"
			data-easing="Power3.easeInOut"
			data-elementdelay="0.1"
			data-endelementdelay="0.1"
			style="z-index: 6;"><a href="#"><img src="<?php echo base_url().'assets/site/';?>images/banner_images/dummy.png" alt="" data-lazyload="<?php echo base_url().'assets/site/';?>images/banner_images/redbg_.png"></a>
		</div>
        <!-- LAYER NR. 9 -->

        <div class="tp-caption customin rs-parallaxlevel-0"
			data-x="860"
			data-y="230"
			data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
			data-speed="500"
			data-start="2000"
			data-easing="Power3.easeInOut"
			data-elementdelay="0.1"
			data-endelementdelay="0.1"
			style="z-index: 6;"><a href="https://play.google.com/store/apps/details?id=com.reviewit.info.android"><img src="<?php echo base_url().'assets/site/';?>images/banner_images/dummy.png" alt="" data-lazyload="<?php echo base_url().'assets/site/';?>images/banner_images/redbg2.svg"></a>
		</div>
        <!-- LAYER NR. 9 -->

		<!-- LAYER NR. 9 -->
		<!--<div class="tp-caption slider_text_3"
			data-x="600"
			data-y="210"
			data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;"
			data-speed="500"
			data-start="2350"
			data-easing="Back.easeOut"
			data-splitin="none"
			data-splitout="none"
			data-elementdelay="0.1"
			data-endelementdelay="0.1"
			style="z-index: 10; max-width: auto; max-height: auto; white-space: nowrap;">
“Review It” is a company discovery platform for International Students and Graduates for<br/>finding the right company. The collaborative platform allows users to provide Company<br/>reviews, share Job experience, communicate with each other and share Job<br/>Opportunities. For recruiters or alumni, it is right place to find the talent and for Job<br/>seekers, it is right place to find opportunities.
		</div>-->

		<!-- LAYER NR. 10 -->

	</li>


</ul>


		</div>
	</div>

    <script type="text/javascript">

				var revapi;

				jQuery(document).ready(function() {

					   revapi = jQuery('.tp-banner').revolution(
						{
							delay:9000,
							startwidth:1170,
							startheight:430,
							hideThumbs:10,
							fullWidth:"on",
							forceFullWidth:"on"

						});

				});	//ready

			</script>
<!--slider portion end here-->

</div>
</div>
<!--banner portion end here-->





<!--welcome con portion start here-->
<div class="main_con">
<div class="welcome_con">

<!--<div class="welcome_con_heading">
<h1>Welcome</h1>
</div>-->
<p>
"Review It" is a company discovery platform for International Students and Graduates for finding the right company. The collaborative platform allows users to provide Company reviews, share Job experience, communicate with each other and share Job Opportunities. For recruiters, it is right place to find the talent and for Job seekers, it is right place to find opportunities.
</p>

</div>
</div>
<!--welcome con portion end here-->





<!--products features portion start here-->
<div class="product_features_bg">
<div class="main_con">

<div class="product_features_heading">
<h1>APP FEATURES</h1>
</div>


<div class="row">
<div class="col-lg-4 col-md-4 col-xs-12">
<div class="product_feature_item">
<ul>
<li class="product_feature_icon_1">
<h1 class="text_align_r">RIGHT CHOICE</h1>
<p class="text_align_r">Our algorithm provide the right options so that students make wise decisions</p>
</li>

<li class="product_feature_icon_2">
<h1 class="text_align_r">LOCATION BASED SEARCH</h1>
<p class="text_align_r">Find the Right company based on your current location and your criteria</p>
</li>

<li class="product_feature_icon_3">
<h1 class="text_align_r">STAY CONNECTED</h1>
<p class="text_align_r">Graduates and Recruiters stay connected through our messaging system</p>
</li>
</ul>
</div>
</div>

<div class="col-lg-4 col-md-4 col-xs-12">
<img src="<?php echo base_url().'assets/site/';?>images/product_feature_mobile_pic.png" alt="" />
</div>

<div class="col-lg-4 col-md-4 col-xs-12">
<div class="product_feature_item">
<ul>
<li class="product_feature_icon_4">
<h1 class="text_align_l">EXPLORE TALENT</h1>
<p class="text_align_l">Great place for Recruiters and Alumni to find right talent</p>
</li>

<li class="product_feature_icon_5">
<h1 class="text_align_l">SHARE</h1>
<p class="text_align_l">Share reviews and Company profile with Friends, Family and Colleagues.</p>
</li>

<li class="product_feature_icon_6">
<h1 class="text_align_l">INSIGHT</h1>
<p class="text_align_l">More Insights on Individual functionalities of each Employer apart from Overall rating.</p>
</li>
</ul>
</div>
</div>

<div class="cleared reset-box"></div>
</div>


</div>
</div>
<!--products features portion end here-->





<!--great features portion start here-->
<!--<div class="great_features_bg">
<div class="main_con">

<div class="great_features_main_heading">
<h1>REVIEW IT <span>FEATURES</span></h1>

<div class="great_features_main_heading_border"></div>
</div>

<div class="great_features_heading_text">
<p>Intrinsicly integrate standards compliant ideas with cross-unit models. Dramatically enable alternative users rather than orthogonal content. Proactively integrate front-end sources vis-a-vis client-based e-tailers</p>
</div>-->



<!--<div class="row">
<div class="great_feature_item">
<ul>
<li class="col-lg-4 col-md-4 col-xs-12">
<div class="great_feature_item_pic">
<img src="images/great_features_item_1.png" alt="" />
</div>
<h1>Premium Quality</h1>
<p>Efficiently coordinate global infrastructures through seamless deliverables. Authoritatively disintermediate high standards in viral technologies.</p>
</li>


<li class="col-lg-4 col-md-4 col-xs-12">
<div class="great_feature_item_pic">
<img src="images/great_features_item_2.png" alt="" />
</div>
<h1>Premium Quality</h1>
<p>Efficiently coordinate global infrastructures through seamless deliverables. Authoritatively disintermediate high standards in viral technologies.</p>
</li>

<li class="col-lg-4 col-md-4 col-xs-12">
<div class="great_feature_item_pic">
<img src="images/great_features_item_3.png" alt="" />
</div>
<h1>Premium Quality</h1>
<p>Efficiently coordinate global infrastructures through seamless deliverables. Authoritatively disintermediate high standards in viral technologies.</p>
</li>

</ul>
</div>
</div>-->

</div>
</div>
<!--great features portion end here-->





<!--download app portion start here-->
<!--<div class="parallax">
<div class="main_con">

<div class="download_app_main_heading">
<h1>Download Our App</h1>

<div class="download_app_main_heading_border"></div>
</div>

<div class="download_app_main_heading_text">
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque convallis est ut erat rutrum, ut ullamcorper orci vulputate. Mauris eleifend urna eget suscipit tincidunt. Fusce maximus, sapien vel suscipit auctor, ante metus tempor leo, id facilisis odio ex et est. Nam eu condimentum urna, sed porta tortor.</p>
</div>

<div class="download_app_btn">
<ul>
<li><a href="#">
<img src="images/banner_images/redbg_.png" alt="" /></a></li>

<li><a href="https://play.google.com/store/apps/details?id=com.reviewit.info.android">
<img src="images/banner_images/redbg2.svg" alt="" /></a></li>

<div class="cleared reset-box"></div>
</ul>
</div>

</div>
</div>-->
<!--download app portion end here-->





<!--get in touch portion start here-->
<div class="get_in_touch_bg">
<div class="main_con">

<div class="get_in_touch_main_heading">
<h1>Get In Touch</h1>
<p>Leave us a note and we will reply to you at the earliest</p>

<div class="get_in_touch_main_heading_pic"></div>
</div>

<div class="get_in_touch_form">



<div class="row">
<form action="" name="Form1" method="post">
<div class="col-lg-6 col-md-6 col-xs-12">
<input type="text" class="get_in_touch_form_box user_icon" placeholder="Name" name="Name" id="name">
</div>

<div class="col-lg-6 col-md-6 col-xs-12">
<input type="email" class="get_in_touch_form_box mail_icon" placeholder="Email" name="EMail" id="email">
</div>
<div class="cleared reset-box"></div>

<div class="col-lg-12">
<input type="text" class="get_in_touch_form_box subject_icon" placeholder="Subject" name="Subject" id="subject">
</div>

<div class="col-lg-12">
<textarea rows="3" class="get_in_touch_form_box message_icon" placeholder="Message" name="Message" id="message"></textarea>
</div>

<div class="get_in_touch_form_btn_con">
<input type="submit" id="submit" name="submit" class="get_in_touch_form_btn" value="SEND  MESSAGE">
</div>

</form>
</div>

</div>

</div>
</div>
<!--get in touch portion end here-->





<!--news letter portion start here-->
<div class="news_letter_bg">
<div class="main_con">

<div class="news_letter_heading">
<h1>Subscribe to our newsletter now!</h1>
</div>

<form action="" name="Form1" method="post">
<div class="row">
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
<input type="email" name="email" id="email" class="study_update_mail_box" placeholder="Enter your email address" >
</div>

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<input type="submit" id="submit1" name="submit1" value="Subscribe" class="study_update_subscribe_btn" />

</div>

<div class="cleared reset-box"></div>
</div>
</form>

</div>
</div>
<!--news letter portion end here-->





<!--footer portion start here-->
<footer>
<div class="main_con">

<div class="footer_logo">
<img src="<?php echo base_url().'assets/site/';?>images/review_it_logo.png" alt="">
</div>

<div class="ftr_social_media_icon">
<ul>
<li><a href="https://www.facebook.com/LikeReviewIt" target="_blank"><img src="<?php echo base_url().'assets/site/';?>images/social_icon_1.png" alt="">
</a></li>

<li><a href="https://twitter.com/FollowReviewIt" target="_blank"><img src="<?php echo base_url().'assets/site/';?>images/social_icon_2.png" alt="">
</a></li>
<li><a href="https://www.linkedin.com/company-beta/7947982/" target="_blank"><img src="<?php echo base_url().'assets/site/';?>images/social_icon_4.png" alt="">
</a></li>
<!--<li><a href="#"><img src="images/social_icon_4.png" alt="">
</a></li>

<li><a href="#"><img src="images/social_icon_5.png" alt="">
</a></li>-->

<div class="cleared reset-box"></div>
</ul>
</div>

<div class="copyright_section">
<p>© 2017 Review It • All Rights Reserved</p>
<p><a href="termsconditions">Terms & Conditions</a></p>
<p><a href="privacy">Privacy Policy</a></p>
</div>

</div>
</footer>
<!--footer portion end here-->




<!--paralax animation link start here-->
<!--<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>-->
<style>
.parallax {
    padding:60px 0 60px 0;
    text-align: center;
    color:#fff;
}
</style>

<script src="<?php echo base_url().'assets/site/';?>js/paralax_js/laxicon.js"></script>

<script>
$('.parallax').laxicon();
</script>
<!--paralax animation link end here-->
</body>
</html>
