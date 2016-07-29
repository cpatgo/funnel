<div class="alert alert-success" style="visibility:hidden;"></div>
<form id="send_multiple_email_form" method="post">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Email</h5>
        </div>
        <div class="ibox-content">  
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <td>Subject</td>
                    <td><input type="text" name="subject" id="subject"></td>
                </tr>
                <tr>
                    <td>Body</td>
                    <td><textarea name="body" id="body"></textarea></td>
                </tr>
            </table>
        </div>
    </div>
    <button class="btn btn-primary" id="send_multiple_email">SEND EMAIL</button>
    <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="btn btn-primary">CANCEL</a>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Contact List: <span>Please uncheck the contact you don't wish to receive this email.</span></h5>
        </div>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center"></th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($contacts->contacts as $key => $value) { ?>
                <tr class="text-center">
                    <td><input type="checkbox" name="contactids[]" value="<?php echo json_encode(array($value->contactId)); ?>" checked="checked"></td>
                    <td><?php printf('%s %s', $value->firstName, $value->lastName); ?></td>
                    <td><?php printf('%s', $value->email); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</form>