<?php
$iContact = getClass('Class_Icontact');
$iContact::getInstance()->setConfig(array(
  'appId'       => $icontact_appId, 
  'apiPassword' => $icontact_apiPassword, 
  'apiUsername' => $icontact_apiUsername
));
$oiContact = $iContact::getInstance();
if(strpos($icontact_apiUsername, 'beta')) $oiContact->useSandbox();

//If sending to one contact
if(isset($_GET['contactId'])):
	$contactIds = $_GET['contactId'];
	try {
		$contact_details = $oiContact->getContact($contactIds);
	} catch (Exception $oException) { // Catch any exceptions
	  	$error_obj = json_decode($oiContact->getLastResponse());
	  	if(isset($error_obj)) printf('<div class="alert alert-danger">%s</div>', $error_obj->errors[0]);
	}
	
	include_once('icontact_send_one.php');
//If sending to multiple contacts
elseif(isset($_POST['send_mass_email'])):
	try {
		$oiContact->addCustomQueryField('listId', $icontact_contactList);
		$contacts = $oiContact->getContacts();
	} catch (Exception $oException) { // Catch any exceptions
	  	$error_obj = json_decode($oiContact->getLastResponse());
	  	if(isset($error_obj)) printf('<div class="alert alert-danger">%s</div>', $error_obj->errors[0]);
	}
	include_once('icontact_send_multiple.php');
//Display all contacts
else:
	try {
		$oiContact->addCustomQueryField('listId', $icontact_contactList);
		$contacts = $oiContact->getContacts();
	} catch (Exception $oException) { // Catch any exceptions
	  	$error_obj = json_decode($oiContact->getLastResponse());
	  	if(isset($error_obj)) printf('<div class="alert alert-danger">%s</div>', $error_obj->errors[0]);
	}
	?>
	<div class="alert alert-success" style="visibility:hidden;"></div>
    <div class="ibox float-e-margins">
    	<div class="ibox-title">
            <h5>iContact Email Addresses</h5>
        </div>
        <div class="ibox-content">  
            <p>
            	Click <b>"Update Contacts"</b> button to update the contacts from GLC to iContact.  
            	<!-- <br>
            	Click <b>"Send Mass Email"</b> button to send email to multiple contacts.
            	<br>
            	Click <b>"Send Email"</b> button to send email to single contact. -->
            </p>
        </div>
    </div>
	<form method="post">
		<button class="btn btn-primary" id="update_contacts" name="update_contacts">UPDATE CONTACTS</button>
		<!-- <button class="btn btn-primary" name="send_mass_email">SEND MASS EMAIL</button> -->
		<div class="ibox float-e-margins">
		    <div class="ibox-title">
		        <h5>Contacts</h5>
		    </div>
		    <div class="ibox-content">  
		        <table class="table table-striped table-bordered table-hover dataTableContacts">
		            <thead>
		                <tr>
		                    <th class="text-center">Name</th>
		                    <th class="text-center">Email Address</th>
		                    <!-- <th class="text-center">Action</th> -->
		                </tr>
		            </thead>
		            <tbody>
		                <?php foreach($contacts->contacts as $key => $value) { ?>
		                <tr class="text-center">
		                    <td><?php printf('%s %s', $value->firstName, $value->lastName); ?></td>
		                    <td><?php printf('%s', $value->email); ?></td>
		                    <!-- <td><button class="btn btn-primary" id="send_one_email" data-contactid="<?php echo $value->contactId ?>">SEND EMAIL</button></td> -->
		                </tr>
		                <?php } ?>
		            </tbody>
		        </table>
		    </div>
		</div>
	</form>
	<?php
endif;
?>
<!-- JQUERY -->
<script type="text/javascript">
    $(function() {
    	var email_url = "<?php printf('%s/glc/admin/index.php?page=email', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
        var template_url = "<?php printf('%s/glc/admin/template/', GLC_URL); ?>";

        $('.dataTableContacts').DataTable({
            "iDisplayLength": 10,
            responsive: true,
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            }
        });

        $('.dataTableContactList').DataTable({
            "iDisplayLength": -1,
            responsive: true,
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            }
        });

        $('body').on('click', '#pay_comm_button', function(e){
            e.preventDefault();
            $('body').find('#pay_commissions').submit();
        });

        $('body').on('click', '#send_one_email', function(e){
        	e.preventDefault();
        	window.location.href = email_url+'&contactId='+$(this).data('contactid');
        });

        $('body').on('click', '#send_single_email', function(e){
        	e.preventDefault();
        	var alert_html = $('body').find('.alert');
            alert_html.text('Please wait. The page will reload after sending the email. This may take some time...');
            alert_html.css('visibility', 'visible');
        	$.ajax({
                method: "post",
                url: ajax_url+"icontact_send_email.php",
                data: {
                	'action': 'send_single',
                	details : $('#send_multiple').serialize()
                },
                dataType: 'json',
                success:function(result) {
                    console.log(result);
                    if(result.type == 'success'){
                    	$('body').find('#body').text('');
                    	$('body').find('#subject').text('');
                    	alert_html.text(result.message);
                    } else {
                        alert(result.message);    
                    }
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });

		$('body').on('click', '#send_multiple_email', function(e){
        	e.preventDefault();
        	var alert_html = $('body').find('.alert');
            alert_html.text('Please wait. The page will reload after sending the email. This may take some time...');
            alert_html.css('visibility', 'visible');
        	$.ajax({
                method: "post",
                url: ajax_url+"icontact_send_email.php",
                data: {
                	'action': 'send_multiple',
                	details : $('#send_multiple_email_form').serialize()
                },
                dataType: 'json',
                success:function(result) {
                    console.log(result);
                    if(result.type == 'success'){
                    	$('body').find('#body').text('');
                    	$('body').find('#subject').text('');
                    	alert_html.text(result.message);
                    } else {
                        alert(result.message);    
                    }
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });

        $('body').on('click', '#update_contacts', function(e){
            e.preventDefault();
            var alert_html = $('body').find('.alert');
            alert_html.text('Please wait. The page will reload after updating the contacts. This may take some time...');
            alert_html.css('visibility', 'visible');
            $.ajax({
                method: "post",
                url: ajax_url+"update_contacts.php",
                dataType: 'json',
                success:function(result) {
                    console.log(result);
                    if(result.type == 'success'){
                    	window.location.href = email_url;
                    } else {
                        alert(result.message);    
                    }
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });
    });
</script>
<!-- END JQUERY -->