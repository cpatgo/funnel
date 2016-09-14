<?php
$id = $_SESSION['dennisn_user_id'];
$msg = "";
$err = "";
/*** check if a file was submitted ***/
if($_POST) {
	$doctype = $_POST['doctype'];

	if(isset($_POST['corporate_tax_number'])){
		if(!empty($_POST['corporate_tax_number'])):
			$user_class = getInstance('Class_User');
			$doc_data = array(
				'user_id' 		=> $id,
				'image_type' 	=> '',
				'image'			=> '',
				'image_size'	=> 0,
				'image_ctgy' 	=> '',
				'image_name' 	=> $_POST['corporate_tax_number'],
				'doctype' 		=> $doctype,
				'approved' 		=> 0,
				'date' 			=> time(),
				'dateapproved' 	=> ''
			);
			$user_class->insert_document($doc_data);
			$user_class->glc_update_usermeta($id, 'corporate_tax_number', $_POST['corporate_tax_number']);
			$msg = 'Thank you for submitting!';
		else:
			$err = "Please enter your Corporate Tax Number";
		endif;
	} else {
		if(!isset($_FILES['userfile'])) {
			$err = 'Please select a file';
		} else {
			try {
				upload($id, $doctype);
				$msg = 'Thank you for submitting!';
			}
			catch(Exception $e)
			{
				$err = $e->getMessage();
			}
		}
	}

	$result = "";
	if(!empty($msg)) $result .= sprintf('&msg=%s', $msg);
	if(!empty($err)) $result .= sprintf('&err=%s', $err);

    printf('<script type="text/javascript">window.location="%s/glc/index.php?page=documents%s";</script>', GLC_URL, $result);
}
function upload($id, $doctype){
// we first include the upload class, as we will need it here to deal with the uploaded file
include(dirname(__FILE__).'/class.upload.php');
// retrieve eventual CLI parameters
$cli = (isset($argc) && $argc > 1);
if ($cli) {
    if (isset($argv[1])) $_GET['file'] = $argv[1];
    if (isset($argv[2])) $_GET['dir'] = $argv[2];
    if (isset($argv[3])) $_GET['pics'] = $argv[3];
}

// set variables
$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'documents/');
$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);

$error = array();
$messsage = array();
$time = time();
if($_FILES['userfile']["size"] > 0){
		$file_type = $_FILES['userfile']['type']; //returns the mimetype
		$allowed = array("image/jpeg", 'image/gif', 'image/png', 'application/pdf', 'application/rtf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		if(!in_array($file_type, $allowed)) {
		  $error[] = 'Document is '.$file_type.' - this is not allowed file type.';
		}
		else
		{
			// ---------- IMAGE UPLOAD ----------
			// we create an instance of the class, giving as argument the PHP object
			// corresponding to the file field from the form
			// All the uploads are accessible from the PHP object $_FILES
			$handle = new Upload($_FILES['userfile']);
			$handlebig = new Upload($_FILES['userfile']);

			// then we check if the file has been uploaded properly
			// in its *temporary* location in the server (often, it is /tmp)
			if ($handle->uploaded) {
				// yes, the file is on the server
				// below are some example settings which can be used if the uploaded file is an image.
				$doctype_arr = array(1 => "identification", 2 => "taxinfo", 3 => "w8ben", 4 => "taxnumber");
				$dtype = $doctype_arr[$doctype];
				$file_name =  "document_user_".$id."_".$dtype."_".$time;
				$handle->file_new_name_body = $file_name;
				$handle->file_overwrite = true;
				$handle->dir_auto_chmod = true;
				$thefile = $file_name.".".$handle->file_src_name_ext;
				// now, we start the upload 'process'. That is, to copy the uploaded file
				// from its temporary location to the wanted location
				// It could be something like $handle->Process('/home/www/my_uploads/');
				$handle->Process($dir_dest);
				$handlebig->Process($dir_pics_big);
				
				// we check if everything went OK
				if ($handle->processed && $handlebig->processed) {
					$messsage[] = "Document is successfully uploaded!";
				} else {
					$error[] = 'Error: ' . $handle->error;
					$error[] = 'Error: ' . $handlebig->error;
				}
				
				// we delete the temporary files
				$handle-> Clean();

				$user_class = getInstance('Class_User');
				$doc_data = array(
					'user_id' 		=> $id,
					'image_type' 	=> $file_type,
					'image'			=> '',
					'image_size'	=> $_FILES['userfile']["size"],
					'image_ctgy' 	=> '',
					'image_name' 	=> $thefile,
					'doctype' 		=> $doctype,
					'approved' 		=> 0,
					'date' 			=> $time,
					'dateapproved' 	=> ''
				);
				$user_class->insert_document($doc_data);

			} else {
				// if we're here, the upload file failed for some reasons
				// i.e. the server didn't receive the file
				$error[] = 'Error: ' . $handle->error;
			}
		}
	}	
}
?>