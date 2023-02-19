<?php
ob_start();
session_start();
include 'inc/config.php';
unset($_SESSION['employee']);
header("location: " . BASE_URL . 'employee_login.php');
