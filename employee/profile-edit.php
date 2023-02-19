<?php require_once('header.php'); ?>

<?php
// Check if the employee is logged in or not
if (!isset($_SESSION['employee'])) {
	header('location: ' . BASE_URL . 'logout.php');
	exit;
} else {
	// If employee is logged in, but admin make him inactive, then force logout this user.
	$statement = $pdo->prepare("SELECT * FROM tbl_employee WHERE emp_id=? AND emp_status=?");
	$statement->execute(array($_SESSION['employee']['emp_id'], 0));
	$total = $statement->rowCount();
	if ($total) {
		header('location: ' . BASE_URL . 'logout.php');
		exit;
	}
}
?>

<?php
if (isset($_POST['form1'])) {

	$valid = 1;

	if (empty($_POST['emp_name'])) {
		$valid = 0;
		$error_message .= LANG_VALUE_123 . "<br>";
	}

	if (empty($_POST['emp_phone'])) {
		$valid = 0;
		$error_message .= LANG_VALUE_124 . "<br>";
	}

	if (empty($_POST['emp_address'])) {
		$valid = 0;
		$error_message .= LANG_VALUE_125 . "<br>";
	}

	if (empty($_POST['emp_country'])) {
		$valid = 0;
		$error_message .= LANG_VALUE_126 . "<br>";
	}

	if (empty($_POST['emp_city'])) {
		$valid = 0;
		$error_message .= LANG_VALUE_127 . "<br>";
	}

	if (empty($_POST['emp_state'])) {
		$valid = 0;
		$error_message .= LANG_VALUE_128 . "<br>";
	}

	if (empty($_POST['emp_zip'])) {
		$valid = 0;
		$error_message .= LANG_VALUE_129 . "<br>";
	}

	if ($valid == 1) {

		// update data into the database
		$statement = $pdo->prepare("UPDATE tbl_employee SET emp_name=?, emp_username=?, emp_phone=?, emp_country=?, emp_address=?, emp_city=?, emp_state=?, emp_zip=? WHERE emp_id=?");
		$statement->execute(array(
			strip_tags($_POST['emp_name']),
			strip_tags($_POST['emp_username']),
			strip_tags($_POST['emp_phone']),
			strip_tags($_POST['emp_country']),
			strip_tags($_POST['emp_address']),
			strip_tags($_POST['emp_city']),
			strip_tags($_POST['emp_state']),
			strip_tags($_POST['emp_zip']),
			$_SESSION['employee']['emp_id']
		));

		$success_message = LANG_VALUE_130;

		$_SESSION['employee']['emp_name'] = $_POST['emp_name'];
		$_SESSION['employee']['emp_username'] = $_POST['emp_username'];
		$_SESSION['employee']['emp_phone'] = $_POST['emp_phone'];
		$_SESSION['employee']['emp_country'] = $_POST['emp_country'];
		$_SESSION['employee']['emp_address'] = $_POST['emp_address'];
		$_SESSION['employee']['emp_city'] = $_POST['emp_city'];
		$_SESSION['employee']['emp_state'] = $_POST['emp_state'];
		$_SESSION['employee']['emp_zip'] = $_POST['emp_zip'];
	}
}
?>

<div class="page">
	<div class="container">
		<div class="row">
			<!-- <div class="col-md-12">
				<?php require_once('employee-sidebar.php'); ?>
			</div> -->
			<div class="col-md-12">
				<div class="user-content">
					<h3>
						<?php echo LANG_VALUE_117; ?>
					</h3>
					<?php
					if ($error_message != '') {
						echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $error_message . "</div>";
					}
					if ($success_message != '') {
						echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
					}
					?>
					<form action="" method="post">
						<?php $csrf->echoInputField(); ?>
						<div class="row">
							<div class="col-md-6 form-group">
								<label for=""><?php echo LANG_VALUE_102; ?> *</label>
								<input type="text" class="form-control" name="emp_name" value="<?php echo $_SESSION['employee']['emp_name']; ?>">
							</div>
							<div class="col-md-6 form-group">
								<label for=""><?php echo LANG_VALUE_103; ?></label>
								<input type="text" class="form-control" name="emp_username" value="<?php echo $_SESSION['employee']['emp_username']; ?>">
							</div>
							<div class="col-md-6 form-group">
								<label for=""><?php echo LANG_VALUE_94; ?> *</label>
								<input type="text" class="form-control" name="" value="<?php echo $_SESSION['employee']['emp_email']; ?>" disabled>
							</div>
							<div class="col-md-6 form-group">
								<label for=""><?php echo LANG_VALUE_104; ?> *</label>
								<input type="text" class="form-control" name="emp_phone" value="<?php echo $_SESSION['employee']['emp_phone']; ?>">
							</div>
							<div class="col-md-12 form-group">
								<label for=""><?php echo LANG_VALUE_105; ?> *</label>
								<textarea name="emp_address" class="form-control" cols="30" rows="10" style="height:70px;"><?php echo $_SESSION['employee']['emp_address']; ?></textarea>
							</div>
							<div class="col-md-6 form-group">
								<label for=""><?php echo LANG_VALUE_106; ?> *</label>
								<select name="emp_country" class="form-control">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);
									foreach ($result as $row) {
									?>
										<option value="<?php echo $row['country_id']; ?>" <?php if ($row['country_id'] == $_SESSION['employee']['emp_country']) {
																								echo 'selected';
																							} ?>><?php echo $row['country_name']; ?></option>
									<?php
									}
									?>
								</select>
							</div>

							<div class="col-md-6 form-group">
								<label for=""><?php echo LANG_VALUE_107; ?> *</label>
								<input type="text" class="form-control" name="emp_city" value="<?php echo $_SESSION['employee']['emp_city']; ?>">
							</div>
							<div class="col-md-6 form-group">
								<label for=""><?php echo LANG_VALUE_108; ?> *</label>
								<input type="text" class="form-control" name="emp_state" value="<?php echo $_SESSION['employee']['emp_state']; ?>">
							</div>
							<div class="col-md-6 form-group">
								<label for=""><?php echo LANG_VALUE_109; ?> *</label>
								<input type="text" class="form-control" name="emp_zip" value="<?php echo $_SESSION['employee']['emp_zip']; ?>">
							</div>
						</div>
						<input type="submit" class="btn btn-primary" value="<?php echo LANG_VALUE_5; ?>" name="form1">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<?php require_once('footer.php'); ?>