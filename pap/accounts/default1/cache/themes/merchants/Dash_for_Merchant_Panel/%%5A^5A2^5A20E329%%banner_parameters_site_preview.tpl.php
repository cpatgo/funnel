<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from banner_parameters_site_preview.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_parameters_site_preview.tpl', 3, false),)), $this); ?>
<!-- banner_parameters_site_preview -->
<div class="BannerSite">
  <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Replicated site preview'), $this);?>
</div>
    <div class="FormFieldLabel"><div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Preview for'), $this);?>
</div></div>
    <div class="FormFieldInputContainer">
        <div class="FormFieldInput AffiliateInput"><?php echo "<div id=\"affiliate\"></div>"; ?></div>
        <div class="Inline"><?php echo "<div id=\"previewLink\"></div>"; ?></div>
    </div>
    <div class="clear" style="height: 10px;"></div>
</div>