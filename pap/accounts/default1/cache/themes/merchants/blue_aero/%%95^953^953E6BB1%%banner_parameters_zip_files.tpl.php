<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:25
         compiled from banner_parameters_zip_files.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_parameters_zip_files.tpl', 4, false),)), $this); ?>
<!-- banner_parameters_zip_files -->

<fieldset class="BannerSite">
<legend><?php echo smarty_function_localize(array('str' => 'Files in zip folder'), $this);?>
</legend>
    <?php echo "<div id=\"zipFolder\" class=\"SiteFolder\"></div>"; ?>
    <table width="100%">
        <tr><td valign="top" width="50%"><?php echo "<div id=\"filesTree\" class=\"FilesTree\"></div>"; ?></td>
            <td valign="top" width="50%"><?php echo "<div id=\"variableList\" class=\"ListOfVariables\"></div>"; ?></td></tr>
    </table>
</fieldset>