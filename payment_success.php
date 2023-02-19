<?php require_once('header.php'); ?>

<?php
if (isset($_POST['form1'])) {
    $payment_id = $_GET['payment_id'];
    $payment_date = date('Y-m-d H:i:s');
    //  $final_total = $_POST['final_total'];
    $item_amount = $_POST['final_total'];
    // echo $payment_date;

    $msg = "You have to pay " . $item_amount . "tk.";


    $statement = $pdo->prepare("INSERT INTO tbl_payment (   
        customer_id,
        customer_name,
        customer_email,
        payment_date,
        txnid, 
        paid_amount,
        card_number,
        card_cvv,
        card_month,
        card_year,
        bank_transaction_info,
        payment_method,
        payment_status,
        shipping_status,
        payment_id
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $statement->execute(array(
        $_SESSION['customer']['cust_id'],
        $_SESSION['customer']['cust_name'],
        $_SESSION['customer']['cust_email'],
        $payment_date,
        '',
        $item_amount,
        '',
        '',
        '',
        '',
        '',
        'Cash On Delivery',
        'Pending',
        'Pending',
        $payment_id
    ));


    // $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
    // $statement1->execute(array($row['payment_id']));
    // $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
    // foreach ($result1 as $row1) {
    //      echo '<b>Product:</b> '.$row1['product_name'];
    //      echo '<br>(<b>Size:</b> '.$row1['size'];
    //      echo ', <b>Color:</b> '.$row1['color'].')';
    //      echo '<br>(<b>Quantity:</b> '.$row1['quantity'];
    //      echo ', <b>Unit Price:</b> '.$row1['unit_price'].')';
    //      echo '<br><br>';
    // }



    $i = 0;
    foreach ($_SESSION['cart_p_id'] as $key => $value) {
        $i++;
        $arr_cart_p_id[$i] = $value;
    }

    $i = 0;
    foreach ($_SESSION['cart_p_name'] as $key => $value) {
        $i++;
        $arr_cart_p_name[$i] = $value;
    }

    $i = 0;
    foreach ($_SESSION['cart_size_name'] as $key => $value) {
        $i++;
        $arr_cart_size_name[$i] = $value;
    }

    $i = 0;
    foreach ($_SESSION['cart_color_name'] as $key => $value) {
        $i++;
        $arr_cart_color_name[$i] = $value;
    }

    $i = 0;
    foreach ($_SESSION['cart_p_qty'] as $key => $value) {
        $i++;
        $arr_cart_p_qty[$i] = $value;
    }

    $i = 0;
    foreach ($_SESSION['cart_p_current_price'] as $key => $value) {
        $i++;
        $arr_cart_p_current_price[$i] = $value;
    }


    $i = 0;
    $statement1 = $pdo->prepare("SELECT * FROM tbl_product");
    $statement1->execute();
    $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result1 as $row1) {
        $i++;
        $arr_p_id[$i] = $row1['p_id'];
        $arr_p_qty[$i] = $row1['p_qty'];
    }


    for ($i = 1; $i <= count($arr_cart_p_name); $i++) {
        if (isset($_SESSION["data"]) && !empty($_SESSION["data"]["discount"]) && $_SESSION["data"]["product_id"] === $arr_cart_p_id[$i]) {
            $arr_cart_p_current_price[$i] = $arr_cart_p_current_price[$i] - ($arr_cart_p_current_price[$i] * $_SESSION["data"]["discount"]) / 100;
        }
        $statement2 = $pdo->prepare("INSERT INTO tbl_order (
						product_id,
						product_name,
						size, 
						color,
						quantity, 
						unit_price, 
						payment_id,
                        order_date
						) 
						VALUES (?,?,?,?,?,?,?,?)");
        $sql = $statement2->execute(array(
            $arr_cart_p_id[$i],
            $arr_cart_p_name[$i],
            $arr_cart_size_name[$i],
            $arr_cart_color_name[$i],
            $arr_cart_p_qty[$i],
            $arr_cart_p_current_price[$i],
            $payment_id,
            $payment_date
        ));

        // Update the stock
        for ($j = 1; $j <= count($arr_p_id); $j++) {
            if ($arr_p_id[$j] == $arr_cart_p_id[$i]) {
                $current_qty = $arr_p_qty[$j];
                break;
            }
        }
        $final_quantity = $current_qty - $arr_cart_p_qty[$i];
        $statement3 = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
        $statement3->execute(array($final_quantity, $arr_cart_p_id[$i]));
    }




    unset($_SESSION['cart_p_id']);
    unset($_SESSION['cart_size_id']);
    unset($_SESSION['cart_size_name']);
    unset($_SESSION['cart_color_id']);
    unset($_SESSION['cart_color_name']);
    unset($_SESSION['cart_p_qty']);
    unset($_SESSION['cart_p_current_price']);
    unset($_SESSION['cart_p_name']);
    unset($_SESSION['cart_p_featured_photo']);
    unset($_SESSION['data']);



    // $product_name = "";
    // $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
    //                     $statement1->execute(array($payment_id));
    //                         $result = $statement1->fetchAll(PDO::FETCH_ASSOC);
    //                         foreach ($result as $row) { 
    //                             $product_name = $row['product_name'];

    //                             echo $product_name;
    //                         }



    // $statement2 = $pdo->prepare("UPDATE tbl_order SET product_name=? WHERE payment_id=?");
    // $statement2->execute(array($product_name,$payment_id));

    // $success_message = 'User Information is updated successfully.';

?>
    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>
                    <h3 style="margin-top:20px;"><?php echo $msg; ?></h3>
                    <a href="dashboard.php" class="btn btn-success">Pay on delivery</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
?>

    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>
                    <h3 style="margin-top:20px;"><?php echo LANG_VALUE_121; ?></h3>
                    <a href="dashboard.php" class="btn btn-success"><?php echo LANG_VALUE_91; ?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<?php require_once('footer.php'); ?>