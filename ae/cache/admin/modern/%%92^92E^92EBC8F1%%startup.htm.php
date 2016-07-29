<?php /* Smarty version 2.6.12, created on 2016-07-08 14:09:37
         compiled from startup.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'startup.htm', 5, false),array('function', 'math', 'startup.htm', 268, false),array('function', 'adesk_amchart', 'startup.htm', 317, false),)), $this); ?>
<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'startup.js', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<h3 class="m-b"><?php echo ((is_array($_tmp='Welcome')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
, <?php echo $this->_tpl_vars['admin']['first_name']; ?>
!</h3>
 
<?php if (isset ( $this->_tpl_vars['newversionavailable'] ) && $this->_tpl_vars['admin']['id'] == 1): ?>
<div class="resultMessage" style="border-color:#109309;">
  <strong><?php echo ((is_array($_tmp='New version available')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 - v <?php echo $this->_tpl_vars['latestversion']; ?>
. <?php echo $this->_tpl_vars['moreinfo']; ?>
</strong>
 
  <br />
  <div style="color:#999999;"> <?php echo ((is_array($_tmp="This notice will only be visible to <b>admin</b>. To hide new version alert change value of NEW_VERSION_ALERT in /cache/serialkey.php to 0")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
</div>
<?php endif; ?>



<?php if (isset ( $this->_tpl_vars['httpauth_warning'] )): ?>
<div class="resultMessage" style="color:#990000; border:1px solid #FF0000; background:#F9E4E3; font-size:12px;">
  <strong><?php echo ((is_array($_tmp="Your software directory currently has password and/or authentication requirements that cause software conflicts")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />

  <?php echo ((is_array($_tmp="Many parts of the software will NOT function properly if you have authentication required (such as htaccess auth)  Please remove all password/authentication requirements from the software folder and refresh this page.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

</div>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['suphp_warning'] )): ?>
<div class="resultMessage" style="color:#990000; border:1px solid #FF0000; background:#F9E4E3; font-size:12px;">
  <strong><?php echo ((is_array($_tmp='Your server seems to have PHPsuExec or suPHP installed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />

  <?php echo ((is_array($_tmp="Having PHPsuExec or suPHP enabled could cause unexpected performance and/or stability issues. Some parts of the software might NOT function properly if you have these security extensions loaded on your server.  Please remove the extension to hide this message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

</div>
<?php endif; ?>



<?php if ($this->_tpl_vars['site']['mailer_log_file'] && $this->_tpl_vars['admin']['id'] == 1): ?>
<div id="campaign_sendlog_warn" class="resultMessage">
  <strong>
    <?php echo ((is_array($_tmp="Campaign sending logs are currently enabled. This means every campaign sent is being logged. This is useful for debugging potential sending issues but will cause your sending to be slower than usual. If you are not debugging your email sending click %shere%s to disable logging.")) ? $this->_run_mod_handler('alang', true, $_tmp, '<a href="#" onclick="return campaign_sendlog_switch();">', '</a>') : smarty_modifier_alang($_tmp, '<a href="#" onclick="return campaign_sendlog_switch();">', '</a>')); ?>

  </strong>
</div>
<?php endif; ?>
	

	<?php if ($this->_tpl_vars['creditbased'] && $this->_tpl_vars['maillimitleft_raw'] < 50): ?>
	<div class="resultMessage" style="border:1px solid #FCD68D; font-size:14px; margin-bottom:10px;">
	  <?php echo ((is_array($_tmp="Your account is almost out of email credits; soon you won't be able to send any more mailings to your subscribers.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <?php if (! adesk_site_hosted_rsid ( )): ?>
	  <a href="/manage/manage/index.php"><?php echo ((is_array($_tmp="Purchase more credits now!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	  <?php endif; ?>
	</div>
	<?php endif; ?>

<?php if ($this->_tpl_vars['close2limit']): ?>
		<div class="resultMessage" style="border:1px solid #FCD68D; font-size:14px; margin-bottom:10px;">
<?php if (! $this->_tpl_vars['isAllTime']): ?>
	<?php if ($this->_tpl_vars['admin']['limit_mail_type'] == 'month1st'): ?>
			<?php echo ((is_array($_tmp="Your account is nearing your email sending limit of %s emails per calendar month.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['limit_mail']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['limit_mail'])); ?>

	<?php else: ?>
			<?php echo ((is_array($_tmp="Your account is nearing your email sending limit of %s emails per %s.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['limit_mail'], $this->_tpl_vars['admin']['limit_mail_type']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['limit_mail'], $this->_tpl_vars['admin']['limit_mail_type'])); ?>

	<?php endif; ?>
<?php else: ?>
			<?php echo ((is_array($_tmp="Your account is nearing your email sending limit of %s emails.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['limit_mail']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['limit_mail'])); ?>

<?php endif; ?>
			<?php echo ((is_array($_tmp="You have %s emails left.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['availLeft']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['availLeft'])); ?>

			<?php echo ((is_array($_tmp="Any campaigns that exceed your limit will not start sending.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['site']['mail_abuse'] && $this->_tpl_vars['admin']['abuseratio_overlimit']): ?>
		<div class="resultMessage" style="border:1px solid #FCD68D; font-size:14px; margin-bottom:10px;">
			<?php echo ((is_array($_tmp="Your account has been temporarily suspended due to a high number of abuse complaints.  You have had %s subscribers report your email.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['abuses_reported']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['abuses_reported'])); ?>

			<?php echo ((is_array($_tmp="That is %s%% of all emails you have sent so far, which is more than the %s%% that we allow for our service.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['abuseratio_current'], $this->_tpl_vars['admin']['abuseratio']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['abuseratio_current'], $this->_tpl_vars['admin']['abuseratio'])); ?>

			<?php echo ((is_array($_tmp="The ability to send emails has been disabled at this time.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
<?php endif; ?>

<?php if (! $this->_tpl_vars['__ishosted'] && $this->_tpl_vars['site']['mail_abuse'] && $this->_tpl_vars['abusers']): ?>
		<div class="resultMessage" style="border:1px solid #FCD68D; font-size:14px; margin-bottom:10px;">
			<?php echo ((is_array($_tmp="There is %s user group(s) that are currently suspended due to abuse complaints.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['abusers']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['abusers'])); ?>

			<a href="desk.php?action=abuse"><?php echo ((is_array($_tmp="Manage & Take Action")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		</div>
<?php endif; ?>

<?php if (! $this->_tpl_vars['__ishosted'] && $this->_tpl_vars['approvals']): ?>
		<div class="resultMessage" style="border:1px solid #FCD68D; font-size:14px; margin-bottom:10px;">
			<?php echo ((is_array($_tmp="There is %s campaign(s) that need your approval.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['approvals']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['approvals'])); ?>

			<a href="desk.php?action=approval"><?php echo ((is_array($_tmp="View & Approve Campaigns")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		</div>
<?php endif; ?>

 

<?php if ($this->_tpl_vars['down4maint']): ?>
		<div class="resultMessage" style="border:1px solid #FCD68D; font-size:14px; margin-bottom:10px;">
			<?php echo ((is_array($_tmp="The software is currently set to be \"down for maintenance\".")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<a href="desk.php?action=settings#general"><?php echo ((is_array($_tmp='Click here to turn this setting off')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		</div>
<?php endif; ?>

<?php if (! $this->_tpl_vars['__ishosted']): ?>
	<?php if (! $this->_tpl_vars['canAddSubscriber']): ?>
		<div class="resultMessage" style="border:1px solid #FCD68D; font-size:14px; margin-bottom:10px;">
			<?php echo ((is_array($_tmp="You have maxed out the subscribers allowed with your account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<?php if ($this->_tpl_vars['site']['brand_links'] && adesk_admin_ismaingroup ( )): ?>
			<?php echo ((is_array($_tmp='No new subscribers will be able to subscribe until you either delete subscribers or <a href="https://awebdesk.com/order/" target="_blank">purchase more user licenses to increase your subscriber limit</a>.')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<?php else: ?>
			<?php echo ((is_array($_tmp="No new subscribers will be able to subscribe until you delete some subscribers.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<?php endif; ?>
		</div>
	<?php elseif ($this->_tpl_vars['close2subscriberlimit']): ?>
		<div class="resultMessage" style="border:1px solid #FCD68D; font-size:14px; margin-bottom:10px;">
			<?php echo ((is_array($_tmp="You have nearly maxed out the subscribers allowed with your account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<?php if ($this->_tpl_vars['site']['brand_links'] && adesk_admin_ismaingroup ( )): ?>
			<?php echo ((is_array($_tmp='You may want to either delete subscribers or <a href="https://awebdesk.com/order/" target="_blank">purchase more user licenses to increase your subscriber limit</a>.')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<?php else: ?>
			<?php echo ((is_array($_tmp="No new subscribers will be able to subscribe until you delete some subscribers.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php endif; ?>



 
<?php if ($this->_tpl_vars['admin']['pg_startup_gettingstarted'] == 1): ?>


<div class="row"  id="startup_box_getting_started"><div style="float:right; padding-top:2px; padding-right:20px;">
			<a href="#" onclick="startup_gettingstarted_hide(<?php echo $this->_tpl_vars['groupids']; ?>
); return false;" style="color:#ddd;"><?php echo ((is_array($_tmp='Hide This Section')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		</div>
<div class="col-lg-12">
          <!-- .crousel slide -->
          <section class="panel">
            <div id="c-slide" class="carousel slide  panel-body">
                <ol class="carousel-indicators out">
                  <li class="active" data-slide-to="0" data-target="#c-slide"></li>
                  <li class="" data-slide-to="1" data-target="#c-slide"></li>
                  <li class="" data-slide-to="2" data-target="#c-slide"></li>
                  <li class="" data-slide-to="3" data-target="#c-slide"></li>
                  <li class="" data-slide-to="4" data-target="#c-slide"></li>
                  <li class="" data-slide-to="5" data-target="#c-slide"></li>
                </ol>
                <div class="carousel-inner">
                  <div class="item active">
                    <p class="text-center">
                      <em class="h4 text-mute"><?php echo ((is_array($_tmp="Let's get started...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</em><br>
                      <small class="text-muted"><?php echo ((is_array($_tmp='Simply follow this carousel for quick startup')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</small>
                    </p>
                  </div>
                <?php if ($this->_tpl_vars['canAddList']): ?>  <div class="item">
                      <p class="text-center">
                      <em class="h4 text-mute"><a href="desk.php?action=list#form-0"><?php echo ((is_array($_tmp='Create a list')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></em> <?php if ($this->_tpl_vars['group_lists_status'] == 0): ?>&nbsp;<span class="label bg-success"><?php echo ((is_array($_tmp='Completed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span><?php endif; ?><br>
                      <small class="text-muted"><?php echo "A list has subscribers whom you will send emails frequently."; ?>
</small>
                     </p>
                  </div><?php endif; ?>
                 <?php if ($this->_tpl_vars['canAddSubscriber']): ?>  <div class="item">
                    <p class="text-center">
                      <em class="h4 text-mute"><a href="desk.php?action=subscriber_import"><?php echo ((is_array($_tmp='Import subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></em><?php if ($this->_tpl_vars['group_lists_subscribers_status'] == 0): ?>&nbsp;<span class="label bg-success"><?php echo ((is_array($_tmp='Completed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span><?php endif; ?><br>
                      <small class="text-muted"><?php echo "Import using CSV file, copy paste data, or from Gmail , Salesforce, Highrise, Zendesk and many others."; ?>
</small>
                    </p>
                  </div><?php endif; ?>
                   <?php if ($this->_tpl_vars['admin']['pg_form_edit']): ?><div class="item">
                    <p class="text-center">
                      <em class="h4 text-mute"><a href="desk.php?action=form"><?php echo ((is_array($_tmp='Integrate with website')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  echo ((is_array($_tmp="(Optional)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></em> <?php if ($this->_tpl_vars['group_lists_forms_status'] == 0): ?>&nbsp;<span class="label bg-success"><?php echo ((is_array($_tmp='Completed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span><?php endif; ?><br>
                      <small class="text-muted"><?php echo "Create subscription forms and generate html codes to paste it on your website."; ?>
</small>
                    </p>
                  </div><?php endif; ?>
                  <?php if ($this->_tpl_vars['admin']['pg_message_add']): ?><div class="item">
                    <p class="text-center">
                      <em class="h4 text-mute"><a href="desk.php?action=campaign_new"><?php echo ((is_array($_tmp='Create a campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></em> <?php if ($this->_tpl_vars['group_lists_campaigns_status'] == 0): ?>&nbsp;<span class="label bg-success"><?php echo ((is_array($_tmp='Completed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span><?php endif; ?><br>
                      <small class="text-muted"><?php echo "This is your message/newsletter content. Use HTML/texts/images/ attachments , the choice is yours."; ?>
</small>
                    </p>
                  </div><?php endif; ?>
                  <?php if ($this->_tpl_vars['admin']['pg_reports_campaign']): ?><div class="item">
                    <p class="text-center">
                      <em class="h4 text-mute"><a href="desk.php?action=campaign&reports=1"><?php echo ((is_array($_tmp='View reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></em> <?php if ($this->_tpl_vars['group_reports_link_status'] == 0): ?>&nbsp;<span class="label bg-success"><?php echo ((is_array($_tmp='Completed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span><?php endif; ?><br>
                      <small class="text-muted"><?php echo "View realtime reports of email opens, clicks, bounces and much more."; ?>
</small>
                    </p>
                  </div><?php endif; ?>
                </div>
                <a data-slide="prev" href="#c-slide" class="left carousel-control">
                  <i class="fa fa-chevron-left"></i>
                </a>
                <a data-slide="next" href="#c-slide" class="right carousel-control">
                  <i class="fa fa-chevron-right"></i>
                </a>
            </div>
          </section>
          <!-- / .carousel slide -->
        </div>
</div>

 
<?php endif; ?>
     
		 
 			 <div class="row">
             
             
            <!-- easypiechart -->
            <div class="col-lg-12">              
              <section class="panel">
                <header class="panel-heading bg-white">
                  <div class="text-center h5"><?php echo ((is_array($_tmp='Quick Stats')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
                </header>
                <div class="panel-body pull-in text-center">
                    
                <div class="col-lg-3">
              <section class="panel text-center">
                <div class="panel-body">
                  <a class="btn btn-circle btn-twitter btn-lg"><i class="fa fa-bars"></i></a>
                  <div class="h6"><?php echo ((is_array($_tmp='Total Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
                  <div class="line m-l m-r"></div>
                  <h4 class="text-success"><strong><?php echo $this->_tpl_vars['listcounts']; ?>
</strong></h4>
                   
                </div>
              </section>
            </div>
                <div class="col-lg-3">
              <section class="panel text-center">
                <div class="panel-body">
                  <a class="btn btn-circle btn-twitter btn-lg"><i class="fa fa-users"></i></a>
                  <div class="h6"><?php echo ((is_array($_tmp='Active')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp='Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
                  <div class="line m-l m-r"></div>
                  <h4 class="text-success"><strong><?php echo $this->_tpl_vars['subscriberscount']; ?>
</strong></h4>
                   
                </div>
              </section>
            </div>
            
              <div class="col-lg-3">
              <section class="panel text-center">
                <div class="panel-body">
                  <a class="btn btn-circle btn-twitter btn-lg"><i class="fa fa-envelope-o"></i></a>
                  <div class="h6"><?php echo ((is_array($_tmp='Total emails sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </div>
                  <div class="line m-l m-r"></div>
                  <h4 class="text-success"><strong><?php echo $this->_tpl_vars['totalemailc']; ?>
</strong></h4>
                   
                </div>
              </section>
            </div>
            
            
            
            
              <div class="col-lg-3">
              <section class="panel text-center">
                <div class="panel-body">
                  <a class="btn btn-circle btn-twitter btn-lg"><i class="fa fa-envelope"></i></a>
                  <div class="h6"><?php echo ((is_array($_tmp='Campaigns sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
                  <div class="line m-l m-r"></div>
                  <h4 class="text-success"><strong><?php echo $this->_tpl_vars['campcount']; ?>
</strong></h4>
                   
                </div>
              </section>
            </div>
            
            
                </div>
               <?php if ($this->_tpl_vars['admin']['limit_subscriber'] || $this->_tpl_vars['admin']['limit_mail']): ?> 
                <div class="panel-body">
               <?php if ($this->_tpl_vars['admin']['limit_mail']): ?> 
              <div class="media">
              <div class="pull-left media-small"><?php echo ((is_array($_tmp='Sending Limit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
               <div class="pull-right media-small"><?php echo $this->_tpl_vars['admin']['limit_mail']; ?>
</div>
                <div class="progress bg-success">
                  <div style='width: <?php echo smarty_function_math(array('equation' => "(x-w)/y*z",'x' => $this->_tpl_vars['admin']['limit_mail'],'w' => $this->_tpl_vars['availLeft'],'y' => $this->_tpl_vars['admin']['limit_mail'],'z' => 100), $this);?>
%' class="progress-bar bg-danger">&nbsp;<?php echo smarty_function_math(array('equation' => "x-w",'x' => $this->_tpl_vars['admin']['limit_mail'],'w' => $this->_tpl_vars['availLeft']), $this);?>
</div>
                </div>
              </div>
              <?php endif; ?>
              
              
             <?php if ($this->_tpl_vars['admin']['limit_subscriber']): ?> <div class="media m-t-none">
              <div class="pull-left media-small"><?php echo ((is_array($_tmp='Subscribers Limit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
              
               
                <div class="pull-right media-small"><?php echo $this->_tpl_vars['admin']['limit_subscriber']; ?>
</div>
                <div class="progress bg-success">
                  <div style='width: <?php echo smarty_function_math(array('equation' => "x/y*z",'x' => $this->_tpl_vars['subscriberscount'],'y' => $this->_tpl_vars['admin']['limit_subscriber'],'z' => 100), $this);?>
%' class="progress-bar bg-danger">
                   &nbsp;<?php echo $this->_tpl_vars['subscriberscount']; ?>
</div>
                </div>
              </div><?php endif; ?>
              
              
         
             
                </div><?php endif; ?>
                 
              </section>
            </div>
             
            
             
       
            
          
                 
            </div>
            
            

 
 
<div class="row">
<div class="col-lg-6">
 
	  <?php if ($this->_tpl_vars['admin']['pg_reports_campaign']): ?>
	  <div class="startup_box_container_shadow">
	  <div id="startup_box_container_trend" class="startup_box_container">
		<div class="startup_box_title">
		  <span id="startup_box_span_subscribe" class="startup_selected"><a href="#" onclick="startup_toggle_tab('startup_box_container_trend', 'subscribe');return false;"><?php echo ((is_array($_tmp='Subscribe Trend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
		  <span id="startup_box_span_unsubscribe"><a href="#" onclick="startup_toggle_tab('startup_box_container_trend', 'unsubscribe');return false;"><?php echo ((is_array($_tmp='Unsubscribe Trend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
		</div>
		<script type="text/javascript" src="../awebdesk/amline/swfobject.js"></script>
		<div id="startup_box_div_subscribe" class="startup_box_container_inner">
		  <?php echo smarty_function_adesk_amchart(array('type' => 'line','divid' => 'chart_subscribed_bydate','location' => 'admin','url' => "graph.php?g=subscribed_bydate",'width' => "100%",'height' => '250','bgcolor' => "#FFFFFF"), $this);?>

		</div>
		<div id="startup_box_div_unsubscribe" class="startup_box_container_inner adesk_hidden">
		  <?php echo smarty_function_adesk_amchart(array('type' => 'line','divid' => 'chart_unsubscribed_bydate','location' => 'admin','url' => "graph.php?g=unsubscribed_bydate",'width' => "100%",'height' => '250','bgcolor' => "#FFFFFF"), $this);?>

		</div>
	  </div>
	  </div>
	  <?php endif; ?>
<section class="panel" style="margin-top:10px;">
            <header class="panel-heading">
              <span class="label bg-info pull-right"><?php if ($this->_tpl_vars['processesCnt']):  echo ((is_array($_tmp="%s")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['processesCnt']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['processesCnt']));  else: ?>0<?php endif; ?></span>
            <?php echo ((is_array($_tmp='Ongoing Processes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
            <section class="panel-body">
              <article class="media">
                <div class="pull-left thumb-small">
                  <span class="fa-stack fa-lg">
                  <?php if ($this->_tpl_vars['pausedProcessesCnt']): ?>
                    <i class="fa fa-circle fa-stack-2x text-danger"></i>
                    <i class="fa fa-flag fa-stack-1x text-white"></i>
                    <?php else: ?>
                    <i class="fa fa-circle fa-stack-2x text-success"></i>
                    <i class="fa fa-flag fa-stack-1x text-white"></i>
                    <?php endif; ?>
                  </span>
                </div>
                <div class="media-body">
                 
                
                  <?php if ($this->_tpl_vars['processesCnt'] || $this->_tpl_vars['pausedProcessesCnt']):  if ($this->_tpl_vars['processesCnt']): ?>
   <a class="h4" href="desk.php?action=processes#list-01-0-0"><?php echo ((is_array($_tmp="Current Processes (%s)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['processesCnt']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['processesCnt'])); ?>
</a>
    <?php endif;  if ($this->_tpl_vars['pausedProcessesCnt']): ?><small class="block"><a class="" href="desk.php?action=processes&status=paused#list-01-0-0"><?php echo ((is_array($_tmp="Paused Processes (%s)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['pausedProcessesCnt']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['pausedProcessesCnt'])); ?>
</a>  </small>
		
	 
		<?php endif; ?>
 
<?php else: ?>
 
<a class="h4" href="desk.php?action=processes#list-01-0-0"><?php echo ((is_array($_tmp='No Active process Now')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>

<?php endif; ?> 
                </div>
              </article>
              
       
            </section>
          </section>
</div>
<div class="col-lg-6">  
 

	  <?php if ($this->_tpl_vars['admin']['pg_subscriber_add'] || $this->_tpl_vars['admin']['pg_subscriber_edit'] || $this->_tpl_vars['admin']['pg_subscriber_delete']): ?>
	  <div class="startup_box_container_shadow">
	  <div id="startup_box_container_recent" class="startup_box_container">
		<div class="startup_box_title">
		  <span id="startup_box_span_recentsubscribers" class="startup_selected"><a href="#" onclick="startup_toggle_tab('startup_box_container_recent', 'recentsubscribers');return false;"><?php echo ((is_array($_tmp='Recent Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
		  <span id="startup_box_span_recentcampaigns"><a href="#" onclick="startup_toggle_tab('startup_box_container_recent', 'recentcampaigns');return false;"><?php echo ((is_array($_tmp='Recent Campaigns')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
		</div>

		<div id="startup_box_div_recentsubscribers" class="startup_box_container_inner">
		 <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%"  >
			<tbody>
			  <tr>
				<th style="color:#999999;"><?php echo ((is_array($_tmp='List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				<th style="color:#999999;"><?php echo ((is_array($_tmp='Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				<th style="color:#999999;"><?php echo ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				<th style="color:#999999;"><?php echo ((is_array($_tmp="Date/Time")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
			  </tr>
			</tbody>
			<tbody id="subTable">
			</tbody>
		  </table></div> 
		</div>
		<div id="subLoadingBar" class="adesk_block" style="color: #999999; font-size: 10px; margin-bottom:10px; margin-top:0px; margin-left:12px; ">
		  <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
		<?php endif; ?>

		<div id="startup_box_div_recentcampaigns" class="startup_box_container_inner adesk_hidden">
		  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%">
			<tbody>
			  <tr>
				<th style="color:#999999;"><?php echo ((is_array($_tmp='Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				<th style="color:#999999;"><?php echo ((is_array($_tmp="List(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				<th style="color:#999999;"><?php echo ((is_array($_tmp='Status')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				<th style="color:#999999;"><?php echo ((is_array($_tmp="Date/Time")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				<th style="color:#999999;"><?php echo ((is_array($_tmp='View Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
			  </tr>
			</tbody>
			<tbody id="campTable">
			</tbody>
		  </table></div>
		</div><div class=" table-responsive">
		<div id="campLoadingBar" class="adesk_hidden" style="color: #999999; font-size: 10px; margin-bottom:10px; margin-top:0px; margin-left:12px; ">
		  <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div></div>
	  </div>
	  </div>
	  
	 </div>
     </div>

<div id="badhttp" class="adesk_hidden">
  <div class="adesk_modal" align="center">
	<div class="adesk_modal_inner" align="left">
	  <h1 style="color:red;"><?php echo ((is_array($_tmp='A server problem was detected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	  <p><?php echo ((is_array($_tmp="The software is unable to contact itself from your server.  This prevents certain features from working properly.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <br />
	  <br />

	  <strong><?php echo ((is_array($_tmp="Common Causes:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong>  </p>
	  <ul>
		<li><?php echo ((is_array($_tmp="A firewall that is blocking any attempts to:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<br />
		<?php echo $this->_tpl_vars['site']['p_link']; ?>

		<br />
		<?php echo ((is_array($_tmp="from your server.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<br />
		<br />
		</li>
		<li><?php echo ((is_array($_tmp="A local DNS issue (on your server) that makes it so your server cannot resolve to:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<br />
		<?php echo $this->_tpl_vars['site']['p_link']; ?>
    </li>
	  </ul>
	  <?php if (! $this->_tpl_vars['isWindows']): ?>
	  <p><strong><?php echo ((is_array($_tmp="How To Prove This:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong></p>
	  <ul>
		<li><?php echo ((is_array($_tmp="Login to your server with SSH (or ask your web host/server admin to do these steps)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
		<li><?php echo ((is_array($_tmp='Go to the directory where your software is installed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
		<li><?php echo ((is_array($_tmp="Enter the following command:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<br />
		wget <?php echo $this->_tpl_vars['site']['p_link']; ?>
    </li>
		<li><?php echo ((is_array($_tmp="See if there are any results (it will likely stall out without results proving the server cannot contact your software URL due to a firewall or DNS issue.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
	  </ul>
	  <?php endif; ?>
	  <br />

	  <form>
		<input type="button" value="<?php echo ((is_array($_tmp="Re-Check")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" style="font-weight:bold;" onclick="$('badhttp').className = 'adesk_hidden'; startup_viable()"/> &nbsp; <input type="button" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('badhttp').className = 'adesk_hidden'"/>
	  </form>
	</div>
  </div>
</div>

<div id="badfriendlyurls" class="adesk_hidden">
  <div class="adesk_modal" align="center">
	<div class="adesk_modal_inner" align="left">
	  <h1 style="color:red;"><?php echo ((is_array($_tmp='A server problem was detected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	  <p><?php echo ((is_array($_tmp="You have (Use search-friendly URLs) enabled on the Settings > General Settings > Public page but your server does not seem to be able to use the search-engine friendly URLs")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <br />
	  <br />

	  <strong><?php echo ((is_array($_tmp="Things to check:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong>  </p>
	  <ul>
		<li><?php echo ((is_array($_tmp="Verify that you put the .htaccess file")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 (<a href="desk.php?action=settings#public" target="_blank"><?php echo ((is_array($_tmp="from the Settings > General Settings > Public page")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>) <?php echo ((is_array($_tmp="in your main software folder.  You can get the contents of what should be put in the .htaccess file by going to Settings > General Settings > Public and clicking (View .htaccess Content)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<br />
		<br />
		</li>
		<li><?php echo ((is_array($_tmp="If the .htaccess file is in your main software folder and search-engine friendly URLs still do not work contact your web host or webmaster for further assistance.  You can also disable the (Use search-friendly URLs) until your web host or webmaster fixes this for you.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

  		</li>
	  </ul>
	  <br />

	  <form>
		<input type="button" value="<?php echo ((is_array($_tmp="Re-Check")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" style="font-weight:bold;" onclick="$('badfriendlyurls').className = 'adesk_hidden'; location.reload()"/> &nbsp; <input type="button" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('badfriendlyurls').className = 'adesk_hidden'"/>
	  </form>
	</div>
  </div>
</div>

<script type="text/javascript">
startup_recent_subscribers(10);
startup_recent_campaigns(10);
<?php if (! $this->_tpl_vars['__ishosted']): ?>
	<?php echo '
	window.setTimeout(function() {
	'; ?>

		startup_viable();
		<?php if (! $this->_tpl_vars['site']['general_maint']): ?>
		startup_rewrite();
		<?php endif; ?>
	<?php echo '
	}, 7 * 1000);
	'; ?>

<?php endif; ?>
</script>