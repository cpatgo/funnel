<?php
$msg = isset($_GET['msg'])?$_GET['msg']:"";
$err = isset($_GET['err'])?$_GET['err']:"";
$document_type = array(1 => 'Identification', 2 => 'Tax Form', 3 => 'W-8 BEN', 4 => 'Corporate Tax Number');
//approve document
$approve = (isset($_GET["approve"]))?$_GET["approve"]:"";
$time = time();
if ($approve != "") 
{
	mysqli_query($GLOBALS["___mysqli_ston"], "update documents set approved = 1, dateapproved = '$time'  where image_id = $approve ");	
}

//deny document
$deny = (isset($_GET["deny"]))?$_GET["deny"]:"";
if ($deny != "") 
{
	mysqli_query($GLOBALS["___mysqli_ston"], "update documents set approved = 2, dateapproved = '$time'  where image_id = $deny ");	
}

//pending documents
$sql = "SELECT image_id, d.user_id, image_type, image, image_size, image_name, doctype, approved, d.date, username FROM documents d INNER JOIN users u on d.user_id=u.id_user WHERE approved = 0";
$docs = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

//approved documents
$sql_approved = "SELECT image_id, d.user_id, image_type, image, image_size, image_name, doctype, approved, d.date, username FROM documents d INNER JOIN users u on d.user_id=u.id_user WHERE approved = 1";
$docs_approved = mysqli_query($GLOBALS["___mysqli_ston"], $sql_approved);

//denied documents
$sql_denied = "SELECT image_id, d.user_id, image_type, image, image_size, image_name, doctype, approved, d.date, username FROM documents d INNER JOIN users u on d.user_id=u.id_user WHERE approved = 2";
$docs_denied = mysqli_query($GLOBALS["___mysqli_ston"], $sql_denied);
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
                <div class="ibox-title">
				<h3>
					Pending Documents
				</h3>
			</div>
			<div class="ibox-content">
						<!-- Content Starts Here -->
			<?php
			if($msg != "" || $err != "") { ?>
			<div class="alert alert-<?php echo $c = ($err != "")?"error":"success"; ?>">				
				<?php echo $c = ($err != "")?$err:$msg; ?>
			</div>
			<?php } ?>
			<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
				<thead>				
					<tr>
						<th scope="col">Id</th>
						<th scope="col">Member</th>
						<th scope="col">Document</th>
						<th scope="col">Date</th>
						<th scope="col">Document Type</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($docs as $row)
				{ 
					?>
					<tr>
						<td><?php echo $row['image_id']; ?></td>
						<td><?php echo $row['username']; ?></td>
						<td>
							<?php if($row['doctype'] !== 4): ?>
								<a title="Document" class="look_doc_info" href="<?php printf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']) ?>/glc/documents/<?php echo $row["image_name"]; ?>" target="_blank">
									<?php echo $row['image_name']; ?>
								</a>
							<?php else:
								echo $row['image_name'];
							endif; ?>
						</td>
						<td><?php echo date('m/d/Y h:i:s A' ,$row['date'] ); ?></td>
						<td><?php echo $document_type[$row['doctype']] ?></td>
						<td><strong><a onclick="return confirm('Approve document  <?php echo $row["image_name"]; ?> for <?php echo $row['username']; ?>?');" title="Approve" class="icon-5 info-tooltip float-left text-info" href="index.php?page=documents&approve=<?php echo $row["image_id"]; ?>&user_id=<?php echo $row["user_id"]; ?>"><i class="fa fa-square-o"></i> Approve</a>&nbsp;&nbsp;&nbsp;&nbsp; <a onclick="return confirm('Deny document  <?php echo $row["image_name"]; ?> for <?php echo $row['username']; ?>?');" title="Deny" class="icon-5 info-tooltip float-left text-danger" href="index.php?page=documents&deny=<?php echo $row["image_id"]; ?>&user_id=<?php echo $row["user_id"]; ?>"><i class="fa fa-square-o"></i> Deny</a></strong></td>
					</tr>
					<?php 
				}
				?>	
				</tbody>
			
			</table>
			<!-- Content Ends Here -->
			</div>
		</div>
	</div>		
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
                <div class="ibox-title text-info">
				<h3>
					Approved Documents
				</h3>
			</div>
			<div class="ibox-content">
			<table class="table table-striped table-bordered dataTableseDocuments" cellspacing="0" width="100%">
			
				<thead>				
					<tr>
						<th scope="col">Id</th>
						<th scope="col">Member</th>
						<th scope="col">Document</th>
						<th scope="col">Date</th>
						<th scope="col">Document Type</th>
					</tr>
				</thead>
				
				<tbody>
				<?php
				foreach($docs_approved as $rowa)
				{ 
					?>
					<tr>
						<td><?php echo $rowa['image_id']; ?></td>
						<td><?php echo $rowa['username']; ?></td>
						<td>
							<a title="Document" class="look_doc_info" href="<?php printf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']) ?>/glc/documents/<?php echo $rowa["image_name"]; ?>" target="_blank">
								<?php echo $rowa['image_name']; ?>
							</a>
						</td>
						<td><?php echo date('m/d/Y h:i:s A' ,$rowa['date'] ); ?></td>
						<td><?php echo $document_type[$rowa['doctype']] ?></td>
					</tr>
					<?php 
				}
				?>	
				</tbody>
			
			</table>
			<!-- Content Ends Here -->
			</div>
		</div>
	</div>		
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
                <div class="ibox-title text-danger">
				<h3>
					Denied Documents
				</h3>
			</div>
			<div class="ibox-content">
			<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
				<thead>				
					<tr>
						<th scope="col">Id</th>
						<th scope="col">Member</th>
						<th scope="col">Document</th>
						<th scope="col">Date</th>
						<th scope="col">Document Type</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($docs_denied as $rowd)
				{ 
					?>
					<tr>
						<td><?php echo $rowd['image_id']; ?></td>
						<td><?php echo $rowd['username']; ?></td>
						<td>
							<a title="Document" class="look_doc_info" href="<?php printf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']) ?>/glc/documents/<?php echo $rowd["image_name"]; ?>" target="_blank">
								<?php echo $rowd['image_name']; ?>
							</a>
						</td>
						<td><?php echo date('m/d/Y h:i:s A' ,$rowd['date'] ); ?></td>
						<td><?php echo $document_type[$rowd['doctype']] ?></td>
						<td><strong><a onclick="return confirm('Approve document  <?php echo $rowd["image_name"]; ?> for <?php echo $rowd['username']; ?>?');" title="Approve" class="icon-5 info-tooltip float-left text-info" href="index.php?page=documents&approve=<?php echo $rowd["image_id"]; ?>&user_id=<?php echo $rowd["user_id"]; ?>"><i class="fa fa-square-o"></i> Approve</a></strong></td>
					</tr>
					<?php 
				}
				?>	
				</tbody>
			</table>
			<!-- Content Ends Here -->
			</div>
		</div>
	</div>		
</div>	