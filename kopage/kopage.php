<?php

define('kopagePath',dirname(__FILE__).'/');

if(file_exists(kopagePath.'kopage.functions.php'))
include(kopagePath.'kopage.functions.php');

?>
<script>
if(!window.jQuery){
   document.writeln('<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></scr'+'ipt>');
}
if(!window.$)
var $=jQuery;
</script>
<?php include(kopagePath.'kopage.js.php'); ?>
<div class="kopageSitebuilder">

<div id="koSitebuilder" style="display:<?php if($predefinedUpgrade)echo 'block';else echo 'none'; ?>;position:fixed;z-index:100;top:0;left:0;right:0;bottom:0;background:radial-gradient(ellipse at center,rgba(0,0,0,0.3) 0,rgba(0,0,0,0.8) 100%);">

<form action="" method="POST" class="koSitebuilder" onsubmit="kopageInstallation();return false">
<a href="javascript:void(null)" class="kCancel" onclick="$('#koSitebuilder').fadeOut();return false">&times;</a>
  <h1><?php if($predefinedUpgrade)echo sitebuilderHeader_upgrade;else echo sitebuilderHeader?></h1>
  
  <?php

  if(defined('kopageTrialURL')){
	  
	  // it's a trial-website builder
	
  ?>
	<div id="koSitebuilderStep1" style="min-width:<?php echo (strlen(kopageTrialURL_Postfix)*10+400); ?>px">
    	<div style="position:relative;">
        	<label for="koDomain" style="position:absolute;z-index:2;color:#000;height: 36px;display:block;line-height: 36px;top:2px;padding: 0 3%;right:2px;background: #eee;border-radius:3px;font-size:24px; box-sizing:border-box;white-space:nowrap; " ><?php echo kopageTrialURL_Postfix; ?></label>
            <input class="input" type="text" name="koDomain" id="koDomain" placeholder="<?php echo sitebuilderTrialDomainPlaceholder?>" autofocus required <?php
		
		
		


	if(isset($_SESSION['kopage']['domain']) && !isset($_SESSION['kopage']['hostname']))
		echo ' value="'.$_SESSION['kopage']['domain'].'"';
	elseif(isset($_SESSION['kopage']['hostname']))
		echo ' value="'.$_SESSION['kopage']['hostname'].'"';
		
		?> />
		</div>
        <label class="inputTip" for="koDomain"><?php echo sitebuilderTrialDomainTip?> <strong><?php echo kopageTrialURL_SampleDomain.kopageTrialURL_Postfix; ?></strong></label>
        <input class="input" type="email" name="koEmail" id="koEmail" placeholder="<?php echo YourEmail?>" required />
        <label class="inputTip" for="koEmail"><?php echo sitebuilderTrialEmailTip?></label>
        <input class="input" type="hidden" name="koTemplate" id="koTemplate" required />
        <button type="submit"><?php echo _Continue?> &rarr;</button>

  </div>
  
  <?php
	  
  } elseif(!defined('kopageDemoOnly')) {
	  
	  	
	  
  ?>
		
  <div id="koSitebuilderStep1">

	<?php
	
	if(defined('kopageInstallationDetails')){
		
		?><style>#koDomain,#koUsername,#koPassword,#koSitebuilderStep1 .inputTip{display:none}</style>
		<script>
	
		function _kopageInstallationSelect(){
			
			var kopageOpt=$("#kopageInstallationSelect option:selected");
			$("#koDomain").val(kopageOpt.attr('data-domain'));
			$("#koUsername").val(kopageOpt.attr('data-username'));
			$("#koPassword").val(kopageOpt.attr('data-password'));
			
			var kopageHost=kopageOpt.attr('data-hostname');
			if(typeof kopageHost==='undefined')kopageHost='';
			$("#koPredefinedHost").val(kopageHost);
			
			var kopagePath=kopageOpt.attr('data-path');
			if(typeof kopagePath==='undefined')kopagePath='';
			$("#koPredefinedPath").val(kopagePath);
			
			var kopagePackage=kopageOpt.attr('data-package');
			if(typeof kopagePackage==='undefined')kopagePackage='';
			$("#koPackage").val(kopagePackage);
			
			var kopageEmail=kopageOpt.attr('data-email');
			if(typeof kopageEmail==='undefined')kopageEmail='';
			$("#koEmail").val(kopageEmail);
			
		}
			
		$(function(){
			
				
			
			
			
			$('form,input').attr('autocomplete', 'off');
			$('#koPassword').attr('readonly',true)
			
			$("#kopageInstallationSelect").change(function(){_kopageInstallationSelect()}).trigger('change');
			
		})
		</script><?php
		
		$kopageInstallationDetails=unserialize(kopageInstallationDetails);	
		if(is_array($kopageInstallationDetails)){
			
			$k_=0;
			$k_form='<select class="input" id="kopageInstallationSelect" onchange="_kopageInstallationSelect()">';
			foreach($kopageInstallationDetails as $k){
				
			
				$k_form.= '<option data-domain="'.$k[0].'" data-username="'.$k[1].'" data-password="'.$k[2].'"';
				
				// exact, predefined folder
				if(isset($k[3]) && $k[3]!='')
				$k_form.= ' data-path="'.$k[3].'"';
				
				// in case if subdomain isn't good host, predefined host hoes here
				if(isset($k[4]) && $k[4]!='') 
				$k_form.= ' data-hostname="'.$k[4].'"';
				
				// is there a package defined?
				if(isset($k[5]) && $k[5]!='') 
				$k_form.= ' data-package="'.$k[5].'"';
				
				// is there a package defined?
				if(isset($k[6]) && $k[6]!='') 
				$k_form.= ' data-email="'.$k[6].'"';
				
				$k_form.= '>'.$k[0].'</option>';
				
				
				
			}
			$k_form.= '</select>';
			
			$k_form.= '<input class="input" type="hidden" name="koPredefinedHost" id="koPredefinedHost">'
				.'<input class="input" type="hidden" name="koPredefinedPath" id="koPredefinedPath">'
				.'<input class="input" type="hidden" name="koPackage" id="koPackage">';
			
			echo $k_form;
			
		}
		
		
		
	}else{
		
		
	}
	
	?>
  <input class="input" type="text" name="koDomain" id="koDomain" placeholder="<?php echo sitebuilderDomainPlaceholder?>" autofocus required <?php
		
		

	function valueFromURL($value){
		
		$value=strip_tags($value);
		$value=stripslashes($value);
		$value=htmlentities($value);
		return $value;
		
	}


	if(isset($_REQUEST['1']))
		echo ' value="'.valueFromURL($_REQUEST['1']).'"';
	elseif(isset($_SESSION['kopage']['domain']) && !isset($_SESSION['kopage']['hostname']))
		echo ' value="'.$_SESSION['kopage']['domain'].'"';
	elseif(isset($_SESSION['kopage']['hostname']))
		echo ' value="'.$_SESSION['kopage']['hostname'].'"';
		
		?> />
  <label class="inputTip" for="koDomain"><?php echo sitebuilderDomainTip?> <strong><?php echo sitebuilderDomainTipSample?></strong></label>
  <input class="input" type="text" name="koUsername" id="koUsername" placeholder="<?php echo _ftpUsernameLabel?>" required <?php
		
		if(isset($_REQUEST['2']))echo ' value="'.valueFromURL($_REQUEST['2']).'"';
		elseif(isset($_SESSION['kopage']['username']))echo ' value="'.$_SESSION['kopage']['username'].'"';
		
		
		?> />
  <input class="input" type="password" name="koPassword" id="koPassword" placeholder="<?php echo _ftpPasswordLabel?>" required style="margin-top:5px;" <?php
		
		if(isset($_REQUEST['3']))echo ' value="'.valueFromURL($_REQUEST['3']).'"';
		elseif(isset($_SESSION['kopage']['password']))echo ' value="'.$_SESSION['kopage']['password'].'"';
		
		
		?>/>
  <label class="inputTip" for="name"><?php echo sitebuilderAccessTip?></label>
  <input class="input" type="hidden" name="koTemplate" id="koTemplate" required />
  <button type="submit"><?php echo _Continue?> &rarr;</button>

  </div>
  <div id="koSitebuilderStep2" style="display:none">
  
  <?php 
  
  if(!defined('sitebuilderNoEmailRequired')){ 
  
  ?>
      <input class="input" type="email" name="koEmail" id="koEmail" placeholder="<?php echo YourEmail?>" />
      <label class="inputTip" for="koEmail"><?php echo sitebuilderEmailTip?></label>
  <?php 
  
  }
  
  if(!defined('sitebuilderNoFolderAllowed')){ 
  
  ?>
  
  	<label style="display:block;text-align:left;margin:30px 0 10px">
    <input type="checkbox" value="1" name="koFolderTrigger" id="koFolderTrigger" <?php 
	
	if(isset($_SESSION['kopage']['folder']) && strlen(trim($_SESSION['kopage']['folder']))==0)
		unset($_SESSION['kopage']['folder']);

	if(
	
	!((isset($_GET['4']) && strlen(trim($_GET['4']))>0)||isset($_SESSION['kopage']['folder']))
	
	) echo 'checked'; ?> />
    <?php echo sitebuilderFolder?>
    </label>
    
    
<div style="<?php if(!(isset($_SESSION['kopage']['folder'])||(isset($_GET['4']) && strlen(trim($_GET['4']))>0))) echo 'display:none;'; ?>" id="installInFolder">

    <div class="controls">
    	<input type="text" class="input" name="koFolder" id="koFolder" placeholder="<?php echo Folder?>" <?php
		
		
		if(isset($_GET['4']))echo ' value="'.valueFromURL($_GET['4']).'"';
		elseif(isset($_SESSION['kopage']['folder']))echo ' value="'.$_SESSION['kopage']['folder'].'"';
		
		?>/>
    </div>
</div>


  	<label class="inputTip" for="koFolderTrigger"><?php echo sitebuilderFolderTip?></label>
    
    <?php
	
  	} else {
		
		echo '<input type="hidden" value="1" name="koFolderTrigger" id="koFolderTrigger">';
	}
	 
  	?>
  <button type="submit"><?php echo _Continue?> &rarr;</button>

  
  </div>
  <div id="koSitebuilderStep3" style="display:none;">
  
  <h2><?php echo sitebuilderWebsiteExists?></h2>
  <label class="inputTip"><?php echo sitebuilderWebsiteExistsTip?></label>
  <br />
  
  <a href="javascript:void(null)" style="padding:10px;margin-top:5px;display:block;text-decoration:none;border:1px solid #ddd;color:#000;border-radius:3px; background: linear-gradient(to bottom, #f5f5f5 0%, #eee 100%)" onclick="kopageInstallation('login')"><?php echo sitebuilderLogin?></a>
  <a href="javascript:void(null)" style="padding:10px;margin-top:5px;display:block;text-decoration:none;border:1px solid #ddd;color:#000;border-radius:3px; background: linear-gradient(to bottom, #f5f5f5 0%, #eee 100%)" onclick="kopageInstallation('upgrade')"><?php echo sitebuilderUpgrade?></a>

  
  
  </div>
  
<?php
  
	}
	
?>
</form>
</div>
<div id="kopageTemplates" class="t-row"></div>
</div>


<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<style>
.kopageSitebuilder{font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;}
.t-browser {
	
  padding-top:31px;
  box-shadow: 0 1px 16px 0 rgba(0, 0, 0, 0.4);
  position: relative;z-index:1;
  border-radius: 4px 4px 0 0;
  background:#ffcc;
  background: linear-gradient(to bottom, #D9D8D9 0%,#EBEBEB 4%);
  
  
	
}

.t-browser:before {
  display: block;
  position: absolute;z-index:3;
  content: '';
  top: 12px;
  left: 15px;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background-color: #bbb;
  box-shadow: 0 0 0 2px #bbb, 19px 0 0 2px #bbb, 38px 0 0 2px #bbb;
}

.t-browser div {
  cursor: default;
  z-index: 3;outline:0px solid red;
  font: bold 14px Helvetica, Arial, sans-serif;
  display: block;
  position: absolute;
  content: '';
  top: 1px;
  left: 78px;line-height:30px;
  min-width: 20%;padding:0 10px;
  height: 0;
  border-bottom: 30px solid white;
  border-left: 12px solid transparent;
  border-right: 12px solid transparent;
  color:#666;font-weight:normal;
}

.t-col-1:hover .t-browser.with-tab div{color:#000;}
.t-row {overflow:auto;max-width:70vw;margin:0 auto;padding:10px 5px 5px 10px;}
.t-col-1 {position:relative;width:48%;margin-right:2%;margin-bottom:2%;float:left;overflow:hidden;height:30vw;box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);border-radius: 4px;

/*animations by http://ademilter.com/lab/liffect/*/

  opacity: 0;
    position: relative;
    -webkit-animation: fadeIn 600ms ease both;
    -webkit-animation-play-state: paused;
    -moz-animation: fadeIn 600ms ease both;
    -moz-animation-play-state: paused;
    -o-animation: fadeIn 600ms ease both;
    -o-animation-play-state: paused;
    animation: fadeIn 600ms ease both;
    animation-play-state: paused;


}

.t-col-1.play {
    -webkit-animation-play-state: running;
    -moz-animation-play-state: running;
    -o-animation-play-state: running;
    animation-play-state: running;
}


@-webkit-keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@-moz-keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@-o-keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}
.t-next-action {position:absolute;bottom:0;right:0;;margin:10px;z-index:4}
.t-col-1 a.t-next-1 {background:rgba(0,0,0,0.7);position:absolute;top:32px;left:0;right:0;bottom:0;line-height:30vw;display:block;text-align:center;color:rgba(255,255,255,0.5);font-size:10vw;opacity:0;transition: all 300ms}
.t-col-1 a.t-next-1 i {line-height:29vw;}
.t-col-1 div.t-next {font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;position:absolute;bottom:0;right:0;left:0;padding:20px;background:rgba(255,255,255,1);box-shadow:inset 0 10px 10px -10px rgba(0,0,0,0.5)}
.t-col-1 a.t-next-2,.t-col-1 a.t-next-3 {font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;margin-left:5px;padding:10px;	background: #1865C5; border-radius:3px; background: linear-gradient(to bottom, #36A8F3 0%,#37ACF5 4%,#1865C5 100%);color:#fff;text-decoration:none; display:inline-block; box-shadow:0 0 10px rgba(0,0,0,0.4); transition:all 0.2s}
.t-col-1 a.t-next-2:hover,.t-col-1 a.t-next-3:hover {color:#fff}
.t-col-1 a.t-next-3{background: #555;background: linear-gradient(to bottom, #666 0%,#333 100%);}
.t-next-action a:hover{box-shadow:0 0 20px rgba(0,0,0,0.6),inset 0 0 0 100px rgba(0,0,0,0.2);}
.t-col-1 img {border-top:1px solid #fff;max-width:100%;height:auto;}
.t-col-1:hover a.t-next-1 {opacity:1;color:rgba(255,255,255,0.5)}

/* Installation Modal Box */

.koSitebuilder .kCancel{line-height:50px;height:50px;width:50px;display:block;text-align:center;font-size:20px;color:#fff;background:rgba(0,0,0,0.7);position:absolute;top:0;right:-50px;text-decoration:none}
.koSitebuilder .kCancel:hover{background:rgba(0,0,0,1);
  box-shadow:1px 1px 10px rgba(0,0,0,0.5)}
.koSitebuilder h1 {background: #555;background: linear-gradient(to bottom, #666 0%,#333 100%);margin:-20px -20px 30px;padding:0;line-height:50px;color:#fff;font-size: 27px;}
.koSitebuilder {
  text-align: center; position: absolute;background:#fff;border-radius:10px;padding:20px;z-index:9; min-width:400px;  box-shadow: 5px 5px 25px rgba(0,0,0,0.3);top: 50%;left: 50%;transform: translate(-50%, -50%);
}
.koSitebuilder .input {
  color: #444;
  font-size: 24px;
  outline: none;
  box-shadow: none;
  border-radius: 3px;
  border: 1px solid #bbb;
  background: transparent;
  display: block;
  height: 40px;
  line-height: 40px;
  width: 100%;
  margin-top: 5%;
  padding: 0 3%; 
  box-sizing:border-box;
  position: relative;
  z-index: 0;
  -webkit-transition: border .25s;
  -moz-transition: border .25s;
  -o-transition: border .25s;
  transition: border .25s;
}

.koSitebuilder .input:focus {
  color: #111;
  border-color: #444;
  box-shadow:1px 1px 10px rgba(0,0,0,0.3)
}

.koSitebuilder .inputTip {display:block;text-align:left;margin:5px 0 0;padding:0;color:rgba(0,0,0,0.5); font-size:80%; max-width:400px}

.koSitebuilder button {
	margin-top: 5%;
	outline: none;
	border: none;
	color: #fff;
	font-size: 24px;
	padding: 3% 7%;
	cursor: pointer;
	display:block; width:100%;
	bottom: 0%;
	transition: all .2s;
	background: #1865C5;
	background: linear-gradient(to bottom, #36A8F3 0%,#37ACF5 4%,#1865C5 100%);
}

.koSitebuilder button:hover, .koSitebuilder button:focus {
	box-shadow:1px 1px 10px rgba(0,0,0,0.3);
}
#kopageTemplates{position:relative}
#kopageTemplatesDidYouKnow{clear:both;float:none;width:90%;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;margin: 35px auto; box-shadow: 0 0 20px rgba(0,0,0,0.1);padding:25px;box-sizing:border-box; 
	border-radius:10px;
	background: #fbdd32;
	background: linear-gradient(to bottom, #fbdd32 0%,#ffeb34 4%,#fed430 50%,#ffcf31 100%);
	
	}
#kopageTemplatesDidYouKnow h3{padding:0;margin:0;font-weight:300;font-size:30px;color:#000;text-shadow:1px 1px 0 #fff}

#kopageTemplatesDidYouKnow h3 i {opacity:0.5}

#kopageTemplatesDidYouKnow h4{padding:0;margin:10px 0 0;font-weight:300;font-size:20px;color:#000;opacity:0.5;text-shadow:1px 1px 0 #fff}
#kopageMoreThemesLoader{ 
  height:200px;line-height:200px;box-sizing:border-box;padding-bottom:30px;text-align:center;z-index:99; background:linear-gradient(to bottom,rgba(<?php echo (defined('kopageMoreThemesRGB')) ? constant('kopageMoreThemesRGB'):'255,255,255'; ?>,0) 0%,rgba(<?php echo (defined('kopageMoreThemesRGB')) ? constant('kopageMoreThemesRGB'):'255,255,255'; ?>,1) 45%);position:absolute; display:block;bottom:0;left:0;right:0; backdrop-filter: blur(10px); vertical-align:middle;border:0;outline:0}
#kopageMoreThemesLoader span {display:inline-block;border:2px solid #2478B8;border-radius:3px;line-height:50px;padding:10px 50px;margin-bottom:35px;color:#2478B8;background:rgba(255,255,255,0.7); box-shadow:0 0 30px rgba(0,0,0,0.1);vertical-align:middle;transition: 0.2s all}
#kopageMoreThemesLoader:hover span {background:#fff;box-shadow:0 0 40px rgba(0,0,0,0.4)}
	
</style>