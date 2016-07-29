<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://10cbfa9612ffe3d68ef77981b6bdaf33 */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://10cbfa9612ffe3d68ef77981b6bdaf33', 1, false),array('modifier', 'escape', 'text://10cbfa9612ffe3d68ef77981b6bdaf33', 10, false),)), $this); ?>
<h1 style="font-family: Arial;"><font size="4"><?php echo smarty_function_localize(array('str' => 'Congratulations'), $this);?>
</font></h1>
<p style="font-family: Arial;"><font size="2"><?php echo smarty_function_localize(array('str' => 'Your mail account is configured correctly and your installation is capable to send mails.'), $this);?>
</font></p>
<div style="font-family: Arial;">
    <fieldset>
        <legend><font size="2"><?php echo smarty_function_localize(array('str' => 'Mail account setup'), $this);?>
</font></legend>
        <table>
            <tbody><tr>
                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'Mail Account Name'), $this);?>
</font></td>

                <td><font size="2"><?php echo ((is_array($_tmp=$this->_tpl_vars['account_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</font></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'From Name'), $this);?>
</font></td>
                <td><font size="2"><?php echo ((is_array($_tmp=$this->_tpl_vars['from_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</font></td>
            </tr>
            <tr>

                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'From Email'), $this);?>
</font></td>
                <td><font size="2"><?php echo ((is_array($_tmp=$this->_tpl_vars['account_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</font></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'Use SMTP protocol'), $this);?>
</font></td>
                <td><font size="2"><?php echo $this->_tpl_vars['use_smtp']; ?>
</font></td>
            </tr>

            <tr>
                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'SMTP Server'), $this);?>
</font></td>
                <td><font size="2"><?php echo ((is_array($_tmp=$this->_tpl_vars['smtp_server'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</font></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'SMTP Port'), $this);?>
</font></td>
                <td><font size="2"><?php echo ((is_array($_tmp=$this->_tpl_vars['smtp_port'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</font></td>

            </tr>
            <tr>
                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'SMTP Authentication'), $this);?>
</font></td>
                <td><font size="2"><?php echo $this->_tpl_vars['smtp_auth']; ?>
</font></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'Use SSL connection'), $this);?>
</font></td>

                <td><font size="2"><?php echo $this->_tpl_vars['smtp_ssl']; ?>
</font></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'SMTP Username'), $this);?>
</font></td>
                <td><font size="2"><?php echo ((is_array($_tmp=$this->_tpl_vars['smtp_username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</font></td>
            </tr>
            <tr>

                <td style="font-weight: bold;"><font size="2"><?php echo smarty_function_localize(array('str' => 'Is default mail account'), $this);?>
</font></td>
                <td><font size="2"><?php echo $this->_tpl_vars['is_default']; ?>
</font></td>
            </tr>
        </tbody></table>
    </fieldset>
</div>