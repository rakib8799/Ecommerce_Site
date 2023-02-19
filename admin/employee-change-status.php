<?php require_once('header.php'); ?>

<?php
if (!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_employee WHERE emp_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if ($total == 0) {
		header('location: employee_logout.php');
		exit;
	} else {
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $row) {
			$emp_status = $row['emp_status'];
		}
	}
}
?>

<?php
if ($emp_status == 0) {
	$final = 1;
} else {
	$final = 0;
}
$statement = $pdo->prepare("UPDATE tbl_employee SET emp_status=? WHERE emp_id=?");
$statement->execute(array($final, $_REQUEST['id']));

header('location: employee.php');
?>