<?php require_once('header.php'); ?>
<?php require_once('employee_header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_registration = $row['banner_registration'];
}
?>

<?php
if (isset($_POST['form1'])) {

    $valid = 1;

    if (empty($_POST['emp_name'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_123 . "<br>";
    }

    if (empty($_POST['emp_email'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_131 . "<br>";
    } else {
        if (filter_var($_POST['emp_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message .= LANG_VALUE_134 . "<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_employee WHERE emp_email=?");
            $statement->execute(array($_POST['emp_email']));
            $total = $statement->rowCount();
            if ($total) {
                $valid = 0;
                $error_message .= LANG_VALUE_147 . "<br>";
            }
        }
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

    if (empty($_POST['emp_password']) || empty($_POST['emp_re_password'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_138 . "<br>";
    }

    if (!empty($_POST['emp_password']) && !empty($_POST['emp_re_password'])) {
        if ($_POST['emp_password'] != $_POST['emp_re_password']) {
            $valid = 0;
            $error_message .= LANG_VALUE_139 . "<br>";
        }
    }

    if ($valid == 1) {

        $token = md5(time());
        $emp_datetime = date('Y-m-d h:i:s');
        $emp_timestamp = time();

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO tbl_employee (
                                        emp_name,
                                        emp_username,
                                        emp_email,
                                        emp_phone,
                                        emp_country,
                                        emp_address,
                                        emp_city,
                                        emp_state,
                                        emp_zip,
                                        emp_password,
                                        emp_token,
                                        emp_datetime,
                                        emp_timestamp,
                                        emp_status
                                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
            strip_tags($_POST['emp_name']),
            strip_tags($_POST['emp_username']),
            strip_tags($_POST['emp_email']),
            strip_tags($_POST['emp_phone']),
            strip_tags($_POST['emp_country']),
            strip_tags($_POST['emp_address']),
            strip_tags($_POST['emp_city']),
            strip_tags($_POST['emp_state']),
            strip_tags($_POST['emp_zip']),
            md5($_POST['emp_password']),
            $token,
            $emp_datetime,
            $emp_timestamp,
            0
        ));

        // Send email for confirmation of the account
        $to = $_POST['emp_email'];

        $subject = LANG_VALUE_150;
        $verify_link = BASE_URL . 'verify.php?email=' . $to . '&token=' . $token;
        $message = '
' . LANG_VALUE_151 . '<br><br>

<a href="' . $verify_link . '">' . $verify_link . '</a>';

        $headers = "From: noreply@" . BASE_URL . "\r\n" .
            "Reply-To: noreply@" . BASE_URL . "\r\n" .
            "X-Mailer: PHP/" . phpversion() . "\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-Type: text/html; charset=ISO-8859-1\r\n";

        // Sending Email
        mail($to, $subject, $message, $headers);
        echo ($to . $subject . $message . $headers);

        unset($_POST['emp_name']);
        unset($_POST['emp_username']);
        unset($_POST['emp_email']);
        unset($_POST['emp_phone']);
        unset($_POST['emp_address']);
        unset($_POST['emp_city']);
        unset($_POST['emp_state']);
        unset($_POST['emp_zip']);

        $success_message = LANG_VALUE_152;
    }
}
?>

<!-- <div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_registration; ?>);">
    <div class="inner">
        <h1><?php echo LANG_VALUE_16; ?></h1>
    </div>
</div> -->

<div class="page">
    <div class="container">
        <h2 class="text-center">Employee Registration</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">



                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8" style="margin-top: 3rem">

                                <?php
                                if ($error_message != '') {
                                    echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $error_message . "</div>";
                                }
                                if ($success_message != '') {
                                    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
                                }
                                ?>

                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo LANG_VALUE_102; ?> *</label>
                                    <input type="text" class="form-control" name="emp_name" value="<?php if (isset($_POST['emp_name'])) {
                                                                                                        echo $_POST['emp_name'];
                                                                                                    } ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo "Username *" ?></label>
                                    <input type="text" class="form-control" name="emp_username" value="<?php if (isset($_POST['emp_username'])) {
                                                                                                            echo $_POST['emp_username'];
                                                                                                        } ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo LANG_VALUE_94; ?> *</label>
                                    <input type="email" class="form-control" name="emp_email" value="<?php if (isset($_POST['emp_email'])) {
                                                                                                            echo $_POST['emp_email'];
                                                                                                        } ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo LANG_VALUE_104; ?> *</label>
                                    <input type="text" class="form-control" name="emp_phone" value="<?php if (isset($_POST['emp_phone'])) {
                                                                                                        echo $_POST['emp_phone'];
                                                                                                    } ?>">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for=""><?php echo LANG_VALUE_105; ?> *</label>
                                    <textarea name="emp_address" class="form-control" cols="30" rows="10" style="height:70px;"><?php if (isset($_POST['emp_address'])) {
                                                                                                                                    echo $_POST['emp_address'];
                                                                                                                                } ?></textarea>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo LANG_VALUE_106; ?> *</label>
                                    <select name="emp_country" class="form-control select2">
                                        <option value="">Select country</option>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                        ?>
                                            <option value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo LANG_VALUE_107; ?> *</label>
                                    <input type="text" class="form-control" name="emp_city" value="<?php if (isset($_POST['emp_city'])) {
                                                                                                        echo $_POST['emp_city'];
                                                                                                    } ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo LANG_VALUE_108; ?> *</label>
                                    <input type="text" class="form-control" name="emp_state" value="<?php if (isset($_POST['emp_state'])) {
                                                                                                        echo $_POST['emp_state'];
                                                                                                    } ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo LANG_VALUE_109; ?> *</label>
                                    <input type="text" class="form-control" name="emp_zip" value="<?php if (isset($_POST['emp_zip'])) {
                                                                                                        echo $_POST['emp_zip'];
                                                                                                    } ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo LANG_VALUE_96; ?> *</label>
                                    <input type="password" class="form-control" name="emp_password">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo LANG_VALUE_98; ?> *</label>
                                    <input type="password" class="form-control" name="emp_re_password">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""></label>
                                    <input type="submit" class="btn btn-danger" value="<?php echo LANG_VALUE_15; ?>" name="form1">
                                    <a href="employee_login.php" class="btn btn-success" name="form1"><?php echo LANG_VALUE_15; ?></a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>