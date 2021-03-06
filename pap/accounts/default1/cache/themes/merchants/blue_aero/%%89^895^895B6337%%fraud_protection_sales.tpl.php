<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:10
         compiled from fraud_protection_sales.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'fraud_protection_sales.tpl', 9, false),)), $this); ?>
<!-- fraud_protection_sales -->

<div class="FraudProtection">

    <?php echo "<div id=\"show_notes_to_affiliates\"></div>"; ?>
    <div class="Line" ></div>

    <div class="Inliner"><?php echo "<div id=\"duplicate_orders_ipInput\"></div>"; ?></div>
    <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Recognize  duplicate orders coming from same IP address within'), $this);?>
</div>
    <div class="FormFieldMediumInline"><?php echo "<div id=\"duplicate_orders_ip_seconds\"></div>"; ?></div>
    <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'seconds.'), $this);?>
</div>
    <div class="clear"></div>
    <div class="FraudProtectionAction">
        <div class="InlinerLabel"><?php echo "<div id=\"duplicate_orders_ip_samecampaign\"></div>"; ?></div>
        <div class="ClearBoth"></div>
        <div class="InlinerLabel"><?php echo "<div id=\"duplicate_orders_ip_sameorderid\"></div>"; ?></div>
        <div class="ClearBoth"></div>
        <div class="Inliner FraudProtectionActionLabel"><?php echo smarty_function_localize(array('str' => 'What to do with these orders'), $this);?>
</div>
        <div class="Inliner" style="width: 150px;"><?php echo "<div id=\"duplicate_orders_ip_action\"></div>"; ?></div>
        <div class="ClearBoth"></div>
        <div class="InlinerLabel"><?php echo "<div id=\"duplicate_orders_ip_message\"></div>"; ?></div>
        <div class="ClearBoth"></div>
    </div>
    
    <div class="Line" ></div>
    
    <div class="clear"></div>
    <div class="Inliner"><?php echo "<div id=\"duplicate_orders_idInput\"></div>"; ?></div>
    <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Recognize duplicate orders coming with the same order ID within'), $this);?>
</div>
    <div class="Inliner"><?php echo "<div id=\"duplicate_order_id_hours\"></div>"; ?></div>
    <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'hours from initial sale.'), $this);?>
</div>
    <div class="clear"></div>
    <div class="FraudProtectionAction">
        <div class="Inliner"><?php echo "<div id=\"aplly_to_empty_orders_idInput\"></div>"; ?></div>
        <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Apply also to empty OrderIDs.'), $this);?>
</div>
        <div class="ClearBoth"></div>
        <div class="InlinerLabel"><?php echo "<div id=\"duplicate_orders_id_samecampaign\"></div>"; ?></div>
        <div class="ClearBoth"></div>
        <div class="Inliner FraudProtectionActionLabel"><?php echo smarty_function_localize(array('str' => 'What to do with these orders'), $this);?>
</div>
        <div class="Inliner" style="width: 150px;"><?php echo "<div id=\"duplicate_orders_id_action\"></div>"; ?></div>
        <div class="ClearBoth"></div>
        <div class="InlinerLabel"><?php echo "<div id=\"duplicate_orders_id_message\"></div>"; ?></div>
        <div class="ClearBoth"></div>
    </div>
        
    <div class="Line" ></div>
    
    <div class="Inliner"><?php echo "<div id=\"bannedips_salesInput\"></div>"; ?></div>
    <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Ban sales from IP addresses'), $this);?>
</div>
    <div class="Inliner"><?php echo "<div id=\"bannedips_list_sales\" class=\"BannedIps\"></div>"; ?></div> 
    <div class="clear"></div>
    <div class="FraudProtectionAction">
        <div class="Inliner FraudProtectionActionLabel"><?php echo smarty_function_localize(array('str' => 'What to do with these orders'), $this);?>
</div>
        <div class="Inliner"><?php echo "<div id=\"bannedips_sales_action\"></div>"; ?></div>
        <div class="ClearBoth"></div>
        <div class="InlinerLabel"><?php echo "<div id=\"bannedips_sales_message\"></div>"; ?></div>
        <div class="ClearBoth"></div>
    </div>

    <div class="Line" ></div>

    <div class="Inliner"><?php echo "<div id=\"bannedreferers_salesInput\"></div>"; ?></div>
    <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Ban sales from urls (HTTP_REFERER)'), $this);?>
</div>
    <div class="Inliner"><?php echo "<div id=\"bannedreferers_list_sales\" class=\"BannedIps\"></div>"; ?></div>
    <div class="clear"></div>
    <div class="FraudProtectionAction">
        <div class="Inliner FraudProtectionActionLabel"><?php echo smarty_function_localize(array('str' => 'What to do with these orders'), $this);?>
</div>
        <div class="Inliner"><?php echo "<div id=\"bannedreferers_sales_action\"></div>"; ?></div>
        <div class="ClearBoth"></div>
        <div class="InlinerLabel"><?php echo "<div id=\"bannedreferers_sales_message\"></div>"; ?></div>
        <div class="ClearBoth"></div>
    </div>
    
    <div class="Line" ></div>

    <div class="Inliner"><?php echo "<div id=\"allowedreferers_salesInput\"></div>"; ?></div>
    <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Allow only sales from urls (HTTP_REFERER)'), $this);?>
</div>
    <div class="Inliner"><?php echo "<div id=\"allowedreferers_list_sales\" class=\"BannedIps\"></div>"; ?></div>
    <div class="clear"></div>
    <div class="Inliner SimpleCheckBox"><?php echo "<div id=\"allow_destination_domains_sales\"></div>"; ?></div>
    <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'and sales from banner destination domains'), $this);?>
</div>
    <div class="clear"></div>
    <div class="Inliner SimpleCheckBox"><?php echo "<div id=\"allow_empty_referers_sales\"></div>"; ?></div>
    <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'allow empty referer (HTTP_REFERER)'), $this);?>
</div>
    <div class="clear"></div>
    <div class="FraudProtectionAction">
        <div class="Inliner FraudProtectionActionLabel"><?php echo smarty_function_localize(array('str' => 'What to do with not allowed orders'), $this);?>
</div>
        <div class="Inliner"><?php echo "<div id=\"allowedreferers_sales_action\"></div>"; ?></div>
        <div class="ClearBoth"></div>
        <div class="InlinerLabel"><?php echo "<div id=\"allowedreferers_sales_message\"></div>"; ?></div>
        <div class="ClearBoth"></div>
    </div>

    <div class="Line" ></div>
    <div class="Line" ></div>

    <?php echo "<div id=\"FraudFeaturesPanel\"></div>"; ?>
</div>