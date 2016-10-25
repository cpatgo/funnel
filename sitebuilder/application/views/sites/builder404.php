<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>404 Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
    	  .center {text-align: center; margin-left: auto; margin-right: auto; margin-bottom: auto; margin-top: auto;}
    	  .container {margin-top:15%;font-size:18px;}
          .row_btn {margin-bottom: 50px}
    </style>
</head>
<body>
    
    <div id="page" class="page">
    
    	<div class="item content" id="content_section1">
    		
    		<div class="container">
                <div class="row row_btn">
                    <div class="span12">
                        <div class="hero-unit center">
                            <a href="<?php echo $return_link; ?>" class="btn btn-primary btn-lg">GO BACK</a>
                        </div>
                    </div>
                </div>
				<div class="row">
					<div class="span12">
						<div class="hero-unit center">
							<h1>Page Not Found <small><font face="Tahoma" color="red">Error 404</font></small></h1>
							<br />
							<p>The link you provided in your page is not working. Please go back and edit your link in the site builder. </p>
						</div>   
					</div>
				</div>
			</div>
    	
    	</div><!-- /.item -->
    
    </div><!-- /#page -->
</body>
</html>
