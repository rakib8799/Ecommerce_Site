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
	}
}
?>

<?php

// Delete from tbl_employee
$statement = $pdo->prepare("DELETE FROM tbl_employee WHERE emp_id=?");
$statement->execute(array($_REQUEST['id']));

// Delete from tbl_rating
// $statement = $pdo->prepare("DELETE FROM tbl_rating WHERE emp_id=?");
// $statement->execute(array($_REQUEST['id']));

// header('location: employee.php');
// 
?>