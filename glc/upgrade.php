<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
require_once("config.php");
if(!isset($_SESSION['dennisn_user_id'])) printf('<script type="text/javascript">window.location="%s";</script>', GLC_URL);

require_once("header-upgrade.php");

$user_id = $_SESSION['dennisn_user_id'];
$user_class = getInstance('Class_User');
$membership_class = getInstance('Class_Membership');
$merchant_class = getInstance('Class_Merchant');
$user = $user_class->get_user($user_id);
$user_membership = $user_class->user_membership($user_id);
$user = $user[0]; 
$user_membership = $user_membership[0];
$membership = $membership_class->get_memberships();
$country = $user['country'];
$active_merchants = $merchant_class->get_all_active_payment_methods();
$creditcard_merchant = $echeck_merchant = 0;
$wp_membership = get_user_meta(get_current_user_id(), 'membership', true);
foreach($active_merchants as $mkey => $mvalue) {
    if($mvalue['slug'] === 'creditcard') $creditcard_merchant = $mvalue['merchant_id'];
    if($mvalue['slug'] === 'echeck') $echeck_merchant = $mvalue['merchant_id'];
}
?>

<style type="text/css">
    .cc_form, .echeck_form{ display:none; }
    body {
        background-color: #fff;
        margin: 0 auto;
    }
</style>
<!DOCTYPE html>
<html lang="en">

<!-- If User's membership is not Master's, allow for upgrade -->
<?php if((int)$user_membership['id'] < 4): ?>    
    <input type="hidden" id="current_membership_amount" value="<?php echo $user_membership['amount'] ?>">
    <div style="background-color:#fff;margin-top:20px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <?php if(isset($_GET['msg']) && !empty($_GET['msg'])) printf('<div class="alert alert-success">%s</div>', $_GET['msg']); ?>
                    <?php if(isset($_GET['err']) && !empty($_GET['err'])) printf('<div class="alert alert-danger">%s</div>', $_GET['err']); ?>
                    <!-- <button class="btn btn-primary btn-large">BACK TO MY HUB</button> -->
                </div>
                <div class="col-md-6 col-md-offset-3">
                    <form class="form-horizontal" id="upgrade_form_special_membership" accept-charset="utf-8">

                        <?php if($membership != 'Free'): ?>
                        <br>
                        <h3><a href="/myhub" class="btn btn-primary blue" style="background-color: #858585; border-color: #858585">BACK TO MY HUB</a></h3>
                        <div class="ibox-title">
                            <h5>Select membership and fill out the payment form.</h5>
                        </div>
                        <div class="form-group">
                            <label for="selected_upgrade" class="col-sm-3 control-label">Your current Membership is:</label>
                            <div class="col-sm-9">
                                <div style="padding-top:10px">
                                    <b><?php echo get_user_meta(get_current_user_id(), 'membership', true); ?></b>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="selected_upgrade" class="col-sm-3 control-label">Upgrade To:</label>
                            <div class="col-sm-9">
                                <div class="radio i-checks">
                                    <label>
                                        <input type="radio" value="special" name="upgrade_membership" checked="checked" data-amt="<?php echo glc_option('aem_special_registration'); ?>"> <i></i>  Professional 
                                    </label>
                                    <span id="amount_text" class="alert-danger" style="padding:5px;"> - Balance Due <b>$<?php echo glc_option('aem_special_registration'); ?></b>. USD</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="selected_method" class="col-sm-3 control-label">Select Payment Method: </label>
                            <div class="col-sm-9">
                                <select name="payment_method" class="form-control" id="payment_method">
                                    <option value disabled selected>- select method - </option>
                                    <?php foreach ($active_merchants as $key => $value) {
                                        if($value['slug'] == 'echeck' && $country !== 'United States' && $country !== 'US') continue;
                                        printf('<option value="%s">%s</option>', $value['slug'], $value['method']);
                                    } ?>
                                </select>
                            </div>
                        </div>
                            
                        <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
                        <input type="hidden" name="cc_merchant_id" value="<?php echo $creditcard_merchant ?>" />
                        <input type="hidden" name="echeck_merchant_id" value="<?php echo $echeck_merchant ?>" />

                            <!-- Common Fields for eCheck and Creditcard -->
                        <div class="common_fields">
                            <div class="form-group">
                                <label for="payment_f_name" class="col-sm-3 control-label">First Name: <span class="required" aria-required="true">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="payment_f_name" placeholder="Account holder first name" required />
                                    <div id="payment_f_name_error_container"></div>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="payment_l_name" class="col-sm-3 control-label">Last Name: <span class="required" aria-required="true">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="payment_l_name" placeholder="Account holder last name" required />
                                    <div id="payment_l_name_error_container"></div>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="company_account_name" class="col-sm-3 control-label">(Company) Account Name:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="company_account_name" placeholder="Company or account name" />
                                    <div id="company_account_name_error_container"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="zip" class="col-sm-3 control-label">Zip Code / Postal Code: <span class="required" aria-required="true">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" id="zip" class="form-control" name="zip" placeholder="Zip Code / Postal Code" required />
                                    <div id="zip_error_container"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_1" class="col-sm-3 control-label">Address 1: <span class="required" aria-required="true">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="address_1" placeholder="Address 1" required />
                                    <div id="address_1_error_container"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_2" class="col-sm-3 control-label">Address 2:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="address_2" placeholder="Address 2" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-3 control-label">City: <span class="required" aria-required="true">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="city" placeholder="City" required />
                                    <div id="city_error_container"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="us_state" class="col-sm-3 control-label">Province / State: <span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-9">
                                        <?php if($country === 'US' || $country === 'United States'): ?>
                                            <div id="statebox">
                                                <select class="form-control required" name="us_state" id="us_state" aria-required="true" required><option value="" disabled selected>Choose Your State</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option></select>
                                            </div>
                                        <?php else: ?>
                                                <input type="text" class="form-control" name="us_state" placeholder="Provice / State" required />
                                        <?php endif; ?>
                                        <div id="us_state_error_container"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Common Fields for eCheck and Creditcard ENDS here -->

                            <!-- Fields for authorize_net creditcard -->
                            <div class="cc_form">
                                <div class="form-group">
                                    <label for="address_2" class="col-sm-3 control-label">Address 2:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="address_2" placeholder="Address 2" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="cc_number" class="col-sm-3 control-label">Debit Card / CC: <span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="cc_number" name="cc_number" placeholder="XXXX-XXXX-XXXX-XXXX" required />
                                        <div id="cc_number_error_container"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="cc_ccv" class="col-sm-3 control-label">CCV: <span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" id="cc_ccv" class="form-control" name="cc_ccv" placeholder="CCV - (i.e. 123)" required />
                                        <div id="cc_ccv_error_container"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="cc_ccv" class="col-sm-3 control-label">CC Expiry: <span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select name="expireMM" id="expireMM" class="form-control required" required>
                                                    <option value="" selected disabled>Month</option>
                                                    <option value="01">01 (Jan)</option>
                                                    <option value="02">02 (Feb)</option>
                                                    <option value="03">03 (Mar)</option>
                                                    <option value="04">04 (Apr)</option>
                                                    <option value="05">05 (May)</option>
                                                    <option value="06">06 (Jun)</option>
                                                    <option value="07">07 (Jul)</option>
                                                    <option value="08">08 (Aug)</option>
                                                    <option value="09">09 (Sep)</option>
                                                    <option value="10">10 (Oct)</option>
                                                    <option value="11">11 (Nov)</option>
                                                    <option value="12">12 (Dec)</option>
                                                </select> 
                                                <div id="expireMM_error_container"></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <select name="expireYY" id="expireYY" class="form-control required" required>
                                                    <option value='' selected disabled>Year</option>
                                                    <option value='2016'>2016</option>
                                                    <option value='2017'>2017</option>
                                                    <option value='2018'>2018</option>
                                                    <option value='2019'>2019</option>
                                                    <option value='2020'>2020</option>
                                                    <option value='2021'>2021</option>
                                                    <option value='2022'>2022</option>
                                                    <option value='2023'>2023</option>
                                                    <option value='2024'>2024</option>
                                                    <option value='2025'>2025</option>
                                                    <option value='2026'>2026</option>
                                                    <option value='2027'>2027</option>
                                                    <option value='2028'>2028</option>
                                                    <option value='2029'>2029</option>
                                                    <option value='2030'>2030</option>
                                                </select>
                                                <div id="expireYY_error_container"></div>
                                            </div>                                      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fields for Authorize.net card ENDED -->

                            <!-- Fields for eCheck Starts Here -->
                            <div class="echeck_form">
                                <div class="form-group">
                                    <label for="phone" class="col-sm-3 control-label">Phone: <span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" id="phone" class="form-control required" name="phone" placeholder="Your Phone (555)-555-5555" required />
                                        <div id="phone_error_container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="checknum" class="col-sm-3 control-label">Check Number: <span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control required" name="checknum" placeholder="Check Number" required />
                                        <div id="checknum_error_container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="routingnum" class="col-sm-3 control-label">Routing Number: <span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control required" name="routingnum" placeholder="Routing Number" required />
                                        <div id="routingnum_error_container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accountnum" class="col-sm-3 control-label">Account Number: <span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control required" name="accountnum" placeholder="Account Number" required />
                                        <div id="accountnum_error_container"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fields for eCheck ENDS here -->


                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">

                                    <div class="loader_processing" style="display:none;">
                                        <div class="alert alert-success" style="width:80%; margin:0 auto; text-align: center;">
                                            <br />
                                            <img src="img/ajax-loader.gif" />
                                            <br /><br />
                                            <p>We’re processing your order.</p><p>Please be patient as this may take up to a minute, so please be patient.</p>
                                            <br /><br />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-md-offset-6">
                                <button class="btn btn-success btn-lg" id="submit_form" style="float:right" type="submit">Submit</button>
                            </div>
                        <?php endif; ?>
                    </form>  
                </div>
            </div>
        </div>
    </div>

<!-- Do not allow to upgrade if membership is Masters already -->
<?php else: ?>
    <div class="ibox-title">
        <h5>You account cannot be upgraded further. Return to <a href="/myhub">GLC Hub</a> </h5>
    </div>
<?php endif; ?>
</html>

<?php require_once('footer-upgrade.php') ?>