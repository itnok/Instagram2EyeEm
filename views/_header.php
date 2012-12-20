<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Instagram 2 EyeEm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Migrate all your Instagram images to EyeEm">
    <meta name="author" content="Simone Conti - http://dev.itnok.com"><!-- Le styles -->
    <link href="lib/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <style type="text/css">
	    body {
		    padding-top: 20px;
		    padding-bottom: 40px;
	    }
	    
	    /* Custom container */
	    .container-narrow {
		    margin: 0 auto;
		    max-width: 700px;
	    }
	    .container-narrow > hr {
	    	margin: 30px 0;
	    }
	    
	    /* Main marketing message and sign up button */
	    .jumbotron {
		    margin: 60px 0;
		    text-align: center;
	    }
	    .jumbotron h1 {
		    font-size  : 5.0em;
		    line-height: 1.0em;
	    }
	    .jumbotron .headtitle h2 {
		    font-size  : 4.0em;
		    line-height: 1.0em;
	    }
	    .jumbotron .btn {
		    font-size: 21px;
		    padding: 14px 24px;
	    }
	    
	    /* Supporting marketing content */
	    .marketing {
		    margin: 60px 0;
	    }
	    .marketing p + h4 {
		    margin-top: 28px;
	    }
	    #progress .spin {
		    height: 100px;
	    }
	    #progress .text {
		    padding-top: 50px;
	    }
    </style>
    <link href="lib/bootstrap/docs/assets/css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="lib/bootstrap/docs/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="lib/bootstrap/docs/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" 	 href="lib/bootstrap/docs/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" 				 href="lib/bootstrap/docs/assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" 								 href="lib/bootstrap/docs/assets/ico/favicon.png">
</head>

<body>
    <div class="container-narrow">
	    <div class="masthead">
        <ul class="nav nav-pills pull-right">
        	<li <?php echo (   empty( $page ) || $page == 'home'    ? 'class="active"' : '' ) ?>><a href="<?php echo WEB_DIR ?>">Home</a></li>
        	<li <?php echo ( ! empty( $page ) && $page == 'about'   ? 'class="active"' : '' ) ?>><a href="<?php echo WEB_DIR ?>/about">About</a></li>
        	<li <?php echo ( ! empty( $page ) && $page == 'contact' ? 'class="active"' : '' ) ?>><a href="<?php echo WEB_DIR ?>/contact">Contact</a></li>
        </ul>
        <h3 class="muted">Instagram 2 EyeEm</h3>
        </div>

        <hr>

        <div class="jumbotron">
	    <?php if( isset( $error ) ): ?>
	        <p id="error"><?php echo htmlspecialchars( $error ) ?></p>
	    <?php endif; ?>
