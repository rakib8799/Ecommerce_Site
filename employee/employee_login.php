<?php require_once('header.php'); ?>
<?php require_once('employee_header.php'); ?>
<!-- fetching row banner login -->
<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_login = $row['banner_login'];
}
?>
<!-- login form -->
<?php
if (isset($_POST['form1'])) {

    if (empty($_POST['emp_email']) || empty($_POST['emp_password'])) {
        $error_message = LANG_VALUE_132 . '<br>';
    } else {

        $emp_email = strip_tags($_POST['emp_email']);
        $emp_password = strip_tags($_POST['emp_password']);

        $statement = $pdo->prepare("SELECT * FROM tbl_employee WHERE emp_email=?");
        $statement->execute(array($emp_email));
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $emp_status = $row['emp_status'];
            $row_password = $row['emp_password'];
        }

        if ($total == 0) {
            $error_message .= LANG_VALUE_133 . '<br>';
        } else {
            //using MD5 form
            if ($row_password != md5($emp_password)) {
                $error_message .= LANG_VALUE_139 . '<br>';
            } else {
                if ($emp_status == 0) {
                    $error_message .= LANG_VALUE_148 . '<br>';
                } else {
                    $_SESSION['employee'] = $row;
                    header("location: " . BASE_URL . "index.php");
                }
            }
        }
    }
}
?>

<div class="page-banner text-center">
    <div class="inner">
        <h1><?php echo "Employee Login"; ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">


                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <?php
                                if ($error_message != '') {
                                    echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $error_message . "</div>";
                                }
                                if ($success_message != '') {
                                    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
                                }
                                ?>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_94; ?> *</label>
                                    <input type="email" class="form-control" name="emp_email">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_96; ?> *</label>
                                    <input type="password" class="form-control" name="emp_password">
                                </div>
                                <div class="form-group">
                                    <label for=""></label>
                                    <input type="submit" class="btn btn-success" value="<?php echo LANG_VALUE_4; ?>" name="form1">
                                </div>
                                <a href="forget-password.php" style="color:#e4144d;"><?php echo LANG_VALUE_97; ?>?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>