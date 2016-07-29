<div class="alert alert-success" style="visibility:hidden;"></div>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Email</h5>
    </div>
    <div class="ibox-content">  
    	<form id="send_multiple">
	        <table class="table table-striped table-bordered table-hover">
	        	<tr>
	                <td>Recepient</td>
	                <td>
	                	<?php printf("%s %s %s", $contact_details->contact->firstName, $contact_details->contact->lastName, $contact_details->contact->email) ?>
	                </td>
	            </tr>
	            <tr>
	                <td>Subject</td>
	                <td><input type="text" name="subject" id="subject"></td>
	            </tr>
	            <tr>
	                <td>Body</td>
	                <td><textarea name="body" id="body"></textarea></td>
	            </tr>
	        </table>
	        <input type="hidden" name="contactIds" value="<?php echo $contact_details->contact->contactId ?>">
	        <button class="btn btn-primary" id="send_single_email">SEND EMAIL</button>
		</form>
    </div>
</div>