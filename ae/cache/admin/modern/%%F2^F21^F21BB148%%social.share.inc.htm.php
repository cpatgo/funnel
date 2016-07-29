<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from social.share.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'plang', 'social.share.inc.htm', 7, false),array('modifier', 'alang', 'social.share.inc.htm', 7, false),)), $this); ?>
<?php if (! isset ( $this->_tpl_vars['shareTitle'] )):  $this->assign('shareTitle', '');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['filter'] )):  $this->assign('filter', '');  endif; ?>

<?php if (isset ( $this->_tpl_vars['shareURL'] )): ?>

	<?php if (! $this->_tpl_vars['filter'] || $this->_tpl_vars['filter'] == 'facebook'): ?>
	<a target="_blank" href="<?php if (isset ( $this->_tpl_vars['shareURL_facebook_external'] )):  echo $this->_tpl_vars['shareURL_facebook_external'];  else:  echo $this->_tpl_vars['shareURL']; ?>
&ref=facebook<?php endif; ?>" title="<?php echo ((is_array($_tmp='Share on Facebook')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" aclinkname="Social: Facebook" target="_blank"><img src="<?php if (isset ( $this->_tpl_vars['site']['p_link2'] )):  echo $this->_tpl_vars['site']['p_link2'];  else:  echo $this->_tpl_vars['site']['p_link'];  endif; ?>/awebdesk/media/social-facebook.png" border="0" height="16" width="16" alt="<?php echo ((is_array($_tmp='Facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" /></a>
	<?php endif; ?>

	<?php if (! $this->_tpl_vars['filter'] || $this->_tpl_vars['filter'] == 'twitter'): ?>
	<a target="_blank" href="<?php if (isset ( $this->_tpl_vars['shareURL_twitter_external'] )):  echo $this->_tpl_vars['shareURL_twitter_external'];  else:  echo $this->_tpl_vars['shareURL']; ?>
&ref=twitter<?php endif; ?>" title="<?php echo ((is_array($_tmp='Share via Twitter')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" aclinkname="Social: Twitter" target="_blank"><img src="<?php if (isset ( $this->_tpl_vars['site']['p_link2'] )):  echo $this->_tpl_vars['site']['p_link2'];  else:  echo $this->_tpl_vars['site']['p_link'];  endif; ?>/awebdesk/media/social-twitter.png" border="0" height="16" width="16" alt="<?php echo ((is_array($_tmp='Twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" /></a>
	<?php endif; ?>

	<?php if (! $this->_tpl_vars['filter'] || $this->_tpl_vars['filter'] == 'digg'): ?>
	<a target="_blank" href="<?php if (isset ( $this->_tpl_vars['shareURL_digg_external'] )):  echo $this->_tpl_vars['shareURL_digg_external'];  else:  echo $this->_tpl_vars['shareURL']; ?>
&ref=digg<?php endif; ?>" title="<?php echo ((is_array($_tmp='Share on Digg')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" aclinkname="Social: Digg" target="_blank"><img src="<?php if (isset ( $this->_tpl_vars['site']['p_link2'] )):  echo $this->_tpl_vars['site']['p_link2'];  else:  echo $this->_tpl_vars['site']['p_link'];  endif; ?>/awebdesk/media/social-digg.png" border="0" height="16" width="16" alt="<?php echo ((is_array($_tmp='Digg')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" /></a>
	<?php endif; ?>

	<?php if (! $this->_tpl_vars['filter'] || $this->_tpl_vars['filter'] == 'delicious'): ?>
	<a target="_blank" href="<?php if (isset ( $this->_tpl_vars['shareURL_delicious_external'] )):  echo $this->_tpl_vars['shareURL_delicious_external'];  else:  echo $this->_tpl_vars['shareURL']; ?>
&ref=delicious<?php endif; ?>" title="<?php echo ((is_array($_tmp='Share on del.icio.us')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" aclinkname="Social: del.icio.us" target="_blank"><img src="<?php if (isset ( $this->_tpl_vars['site']['p_link2'] )):  echo $this->_tpl_vars['site']['p_link2'];  else:  echo $this->_tpl_vars['site']['p_link'];  endif; ?>/awebdesk/media/social-delicious.png" border="0" height="16" width="16" alt="del.icio.us" /></a> 	<?php endif; ?>

	<?php if (! $this->_tpl_vars['filter'] || $this->_tpl_vars['filter'] == 'greader'): ?>
	<a target="_blank" href="<?php if (isset ( $this->_tpl_vars['shareURL_greader_external'] )):  echo $this->_tpl_vars['shareURL_greader_external'];  else:  echo $this->_tpl_vars['shareURL']; ?>
&ref=greader<?php endif; ?>" title="<?php echo ((is_array($_tmp='Share on buzz')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" aclinkname="Social: buzz" target="_blank"><img src="<?php if (isset ( $this->_tpl_vars['site']['p_link2'] )):  echo $this->_tpl_vars['site']['p_link2'];  else:  echo $this->_tpl_vars['site']['p_link'];  endif; ?>/awebdesk/media/social-buzz.png" border="0" height="16" width="16" alt="<?php echo ((is_array($_tmp='Google Reader')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" /></a>
	<?php endif; ?>

	<?php if (! $this->_tpl_vars['filter'] || $this->_tpl_vars['filter'] == 'reddit'): ?>
	<a target="_blank" href="<?php if (isset ( $this->_tpl_vars['shareURL_reddit_external'] )):  echo $this->_tpl_vars['shareURL_reddit_external'];  else:  echo $this->_tpl_vars['shareURL']; ?>
&ref=reddit<?php endif; ?>" title="<?php echo ((is_array($_tmp='Share on Reddit')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" aclinkname="Social: Reddit" target="_blank"><img src="<?php if (isset ( $this->_tpl_vars['site']['p_link2'] )):  echo $this->_tpl_vars['site']['p_link2'];  else:  echo $this->_tpl_vars['site']['p_link'];  endif; ?>/awebdesk/media/social-reddit.png" border="0" height="16" width="16" alt="<?php echo ((is_array($_tmp='Reddit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" /></a>
	<?php endif; ?>

	<?php if (! $this->_tpl_vars['filter'] || $this->_tpl_vars['filter'] == 'stumbleupon'): ?>
	<a target="_blank" href="<?php if (isset ( $this->_tpl_vars['shareURL_stumbleupon_external'] )):  echo $this->_tpl_vars['shareURL_stumbleupon_external'];  else:  echo $this->_tpl_vars['shareURL']; ?>
&referral=stumbleupon<?php endif; ?>" title="<?php echo ((is_array($_tmp='Share on StumbleUpon')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" aclinkname="Social: StumbleUpon" target="_blank"><img src="<?php if (isset ( $this->_tpl_vars['site']['p_link2'] )):  echo $this->_tpl_vars['site']['p_link2'];  else:  echo $this->_tpl_vars['site']['p_link'];  endif; ?>/awebdesk/media/social-stumble.png" border="0" height="16" width="16" alt="<?php echo ((is_array($_tmp='StumbleUpon')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" /></a>
	<?php endif; ?>

<?php endif; ?>