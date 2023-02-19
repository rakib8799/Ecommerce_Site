<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_cart = $row['banner_cart'];
}
?>

<?php
$error_message = '';
if (isset($_POST['form1'])) {

    $i = 0;
    $statement = $pdo->prepare("SELECT * FROM tbl_product");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $i++;
        $table_product_id[$i] = $row['p_id'];
        $table_quantity[$i] = $row['p_qty'];
    }

    $i = 0;
    foreach ($_POST['product_id'] as $val) {
        $i++;
        $arr1[$i] = $val;
    }
    $i = 0;
    foreach ($_POST['quantity'] as $val) {
        $i++;
        $arr2[$i] = $val;
    }
    $i = 0;
    foreach ($_POST['product_name'] as $val) {
        $i++;
        $arr3[$i] = $val;
    }

    $allow_update = 1;
    for ($i = 1; $i <= count($arr1); $i++) {
        for ($j = 1; $j <= count($table_product_id); $j++) {
            if ($arr1[$i] == $table_product_id[$j]) {
                $temp_index = $j;
                break;
            }
        }
        if ($table_quantity[$temp_index] < $arr2[$i]) {
            $allow_update = 0;
            $error_message .= '"' . $arr2[$i] . '" items are not available for "' . $arr3[$i] . '"\n';
        } else {
            $_SESSION['cart_p_qty'][$i] = $arr2[$i];
        }
    }
    $error_message .= '\nOther items quantity are updated successfully!';
?>

    <?php if ($allow_update == 0) : ?>
        <script>
            alert('<?php echo $error_message; ?>');
        </script>
    <?php else : ?>
        <script>
            alert('All Items Quantity Update is Successful!');
        </script>
    <?php endif; ?>
<?php

}
?>

<html>

<body>
    <div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_cart; ?>)">
        <div class="overlay"></div>
        <div class="page-banner-inner">
            <h1><?php echo LANG_VALUE_18; ?></h1>
        </div>
    </div>

    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <?php if (!isset($_SESSION['cart_p_id'])) : ?>
                        <?php echo '<h2 class="text-center">Cart is Empty!!</h2></br>'; ?>
                        <?php echo '<h4 class="text-center">Add products to the cart in order to view it here.</h4>'; ?>
                    <?php else : ?>
                        <form action="" method="post">
                            <?php $csrf->echoInputField(); ?>
                            <div class="cart">
                                <table class="table table-responsive table-hover table-bordered">
                                    <tr>
                                        <th><?php echo '#' ?></th>
                                        <th><?php echo LANG_VALUE_8; ?></th>
                                        <th><?php echo LANG_VALUE_47; ?></th>
                                        <th><?php echo LANG_VALUE_157; ?></th>
                                        <th><?php echo LANG_VALUE_158; ?></th>
                                        <th><?php echo LANG_VALUE_159; ?></th>
                                        <th><?php echo LANG_VALUE_55; ?></th>
                                        <th class="text-right"><?php echo LANG_VALUE_82; ?></th>
                                        <th class="text-center" style="width: 100px;"><?php echo LANG_VALUE_83; ?></th>
                                    </tr>
                                    <?php
                                    $table_total_price = 0;

                                    $i = 0;
                                    foreach ($_SESSION['cart_p_id'] as $key => $value) {
                                        $i++;
                                        $arr_cart_p_id[$i] = $value;
                                    }

                                    $i = 0;
                                    foreach ($_SESSION['cart_size_id'] as $key => $value) {
                                        $i++;
                                        $arr_cart_size_id[$i] = $value;
                                    }

                                    $i = 0;
                                    foreach ($_SESSION['cart_size_name'] as $key => $value) {
                                        $i++;
                                        $arr_cart_size_name[$i] = $value;
                                    }

                                    $i = 0;
                                    foreach ($_SESSION['cart_color_id'] as $key => $value) {
                                        $i++;
                                        $arr_cart_color_id[$i] = $value;
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
                                    foreach ($_SESSION['cart_p_name'] as $key => $value) {
                                        $i++;
                                        $arr_cart_p_name[$i] = $value;
                                    }

                                    $i = 0;
                                    foreach ($_SESSION['cart_p_featured_photo'] as $key => $value) {
                                        $i++;
                                        $arr_cart_p_featured_photo[$i] = $value;
                                    }
                                    ?>
                                    <?php for ($i = 1; $i <= count($arr_cart_p_id); $i++) : ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td>
                                                <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" alt="">
                                            </td>
                                            <td><?php echo $arr_cart_p_name[$i]; ?></td>
                                            <td><?php echo $arr_cart_size_name[$i]; ?></td>
                                            <td><?php echo $arr_cart_color_name[$i]; ?></td>
                                            <td><?php echo "&#2547;" ?><?php echo $arr_cart_p_current_price[$i]; ?></td>
                                            <td>
                                                <input type="hidden" name="product_id[]" value="<?php echo $arr_cart_p_id[$i]; ?>">
                                                <input type="hidden" name="product_name[]" value="<?php echo $arr_cart_p_name[$i]; ?>">
                                                <input type="number" class="input-text qty text" step="1" min="1" max="" name="quantity[]" value="<?php echo $arr_cart_p_qty[$i]; ?>" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                                            </td>
                                            <td class="text-right">
                                                <?php
                                                if (isset($_SESSION["data"]) && !empty($_SESSION["data"]["discount"]) && $_SESSION["data"]["product_id"] === $arr_cart_p_id[$i]) {

                                                    $row_total_price = $arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i] - ($arr_cart_p_current_price[$i] * $_SESSION["data"]["discount"] * $arr_cart_p_qty[$i]) / 100;
                                                    $table_total_price = $table_total_price + $row_total_price;
                                                ?>
                                                    <?php echo "&#2547;"; ?><?php echo $arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i]; ?>
                                                    <?php echo " - &#2547;"; ?><?php echo ($arr_cart_p_current_price[$i] * $_SESSION["data"]["discount"] * $arr_cart_p_qty[$i]) / 100; ?>
                                                    <?php echo " = &#2547;"; ?><?php echo $row_total_price; ?>
                                                <?php
                                                } else {

                                                    $row_total_price = $arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i];
                                                    $table_total_price = $table_total_price + $row_total_price;
                                                ?>
                                                    <?php echo "&#2547;"; ?><?php echo $row_total_price; ?>

                                                <?php
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <a onclick="return confirmDelete();" href="cart-item-delete.php?id=<?php echo $arr_cart_p_id[$i]; ?>&size=<?php echo $arr_cart_size_id[$i]; ?>&color=<?php echo $arr_cart_color_id[$i]; ?>" class="trash"><i class="fa fa-trash" style="color:red;"></i></a>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                    <tr>
                                        <th colspan="7" class="total-text">Total</th>
                                        <th class="total-amount" id="total"><?php echo "&#2547;" ?><?php echo $table_total_price; ?></th>
                                        <th></th>
                                    </tr>
                                </table>
                            </div>
                            <div class="form-group col-md-6">
                                <h4 class="text-warning">*Optional</h4>
                                <label>Coupon Code</label>
                                <input class="form-control" placeholder="Apply your Coupon code here to get discount" type="text" id="coupon" name="coupon" />
                                <input type="hidden" value="<?php echo $table_total_price; ?>" id="price" name="price" />
                                <!-- <script type="text/javascript"> -->

                                <?php
                                if (isset($_POST['activate'])) {

                                    $count = 0;

                                    $coupon_code = $_POST['coupon'];
                                    // $price = $_POST['price'];
                                    // echo $price;
                                    if (!empty($coupon_code)) {

                                        // echo $price;

                                        // $statement1 = $pdo->prepare("SELECT * from tbl_product");
                                        // // $statement1 = $pdo->query($sql);
                                        // $statement1->execute();
                                        // $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                        // // $row1 = $statement1->rowCount();
                                        // $product_id = "";
                                        // $arr = [];
                                        // $count = 0;
                                        // foreach ($result1 as $row1) {
                                        //     $product_id = $row1['p_id'];
                                        // echo $product_id;


                                        // $statement = $pdo->query($sql);
                                        for ($i = 1; $i <= count($arr_cart_p_id); $i++) {
                                            $statement = $pdo->prepare("SELECT * from tbl_coupon 
                                            WHERE product_id = ? AND coupon_code = ? AND status='Valid'");
                                            // echo $arr_cart_p_id[$i];
                                            $statement->execute(array($arr_cart_p_id[$i], $coupon_code));
                                            $result = $statement->fetchALL(PDO::FETCH_ASSOC);
                                            $row = $statement->rowCount();
                                            // $count++;
                                            // array_push($arr,);
                                            // echo $arr_cart_p_id[$i];

                                            if ($row > 0) {
                                                $count = 1;
                                                // print_r($result[0]);
                                                // echo $result[0]["discount"];
                                                // echo "<b class='text-success'>Congratulations! You have got " . $result[0]["discount"] . "% Off</b>";
                                                $_SESSION['data'] = [
                                                    "product_id" => $arr_cart_p_id[$i],
                                                    "coupon_code" => $coupon_code,
                                                    "discount" => $result[0]['discount'],
                                                    // "price" => $result[0]['discount'],
                                                ];
                                                break;
                                            }
                                            // foreach ($result as $row) {
                                            // };

                                            // $statement = $pdo->prepare("SELECT * FROM tbl_coupon WHERE coupon_code = ? AND status = 'Valid'");
                                            // $statement->execute(array($coupon_code));
                                            // $result = $statement->fetch(PDO::FETCH_BOTH);
                                            // $row = $statement->rowCount();
                                            // // $fetch = mysqli_fetch_array($query);
                                            // // echo $result['discount'];
                                            // $array = [];
                                            // if ($row > 0) {
                                            //     // $discount = $result['discount'] / 100;
                                            //     // // echo $discount;
                                            //     // $discount_val = $discount * $price;
                                            //     // $total = $price - $discount_val;
                                            //     // // echo $total;
                                            //     // // $array['discount'] = $result['discount'];
                                            //     // // $array['price'] = $price - $total;
                                            //     // $data = ["discount" => $discount, "total" => $total];
                                            //     // $data=json_encode($array);
                                            //     echo "<b class='text-success'>Congratulations! You have got " . $result['discount'] . "% Off</b>";
                                            //     // print_r($data);
                                            //     // echo $data['total'];
                                            //     // $_SESSION['data'] = $data;
                                            //     // exit();
                                            //     // print_r($_SESSION['data']);

                                            // } 
                                            else {
                                                $count = 2;
                                                // echo "<b class='text-danger'>Invalid Coupon Code!</b>";
                                                // break;
                                                // unset($_SESSION['data']);
                                                continue;
                                            }
                                        }
                                    }
                                    // }
                                    // }
                                    else {
                                        $coupon = 3;
                                        // echo "<b class='text-danger'>Coupon Code can't be empty!</b>";
                                    }
                                    if ($count == 1) {
                                        echo "<b class='text-success'>Congratulations! You have got " . $result[0]["discount"] . "% Off</b><br>";
                                        echo "<b class='text-warning'>Please reload the page to get updated price</b>";
                                    } else if ($count == 2) {
                                        echo "<b class='text-danger'>Invalid Coupon Code!</b>";
                                    } else {
                                        echo "<b class='text-danger'>Coupon Code can't be empty!</b>";
                                    }
                                }
                                // echo "<pre>";
                                // print_r($_SESSION["data"]["total"]);

                                ?>
                                <!-- </script> -->
                                <!-- <div id="result"></div>
				<br style="clear:both;"/> -->
                                <br>
                                <button type="submit" class="btn btn-primary" name="activate" id="activate">Activate Code</button>
                            </div>

                            <div class="cart-buttons">
                                <ul>
                                    <li><input type="submit" value="<?php echo LANG_VALUE_20; ?>" class="btn btn-primary" name="form1"></li>
                                    <li><a href="index.php" class="btn btn-primary"><?php echo LANG_VALUE_85; ?></a></li>
                                    <li><a href="checkout.php" class="btn btn-primary"><?php echo LANG_VALUE_23; ?></a></li>
                                </ul>
                            </div>
                        </form>
                    <?php endif; ?>



                </div>
            </div>
        </div>
    </div>

    <!-- <script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script> -->
    <script type="text/javascript">
        // $(document).ready(function(){
        // 	$('#activate').on('click', function(){
        // 		var coupon = $('#coupon').val();
        // 		var price = $('#price').val();
        // 		if(coupon == ""){
        // 			alert("Please enter a coupon code!");
        // 		}else{

        // 			$.post('get_discount.php', {coupon: coupon, price: price}, function(data){
        // 				if(data == "error"){
        // 					alert("Invalid Coupon Code!");
        // 					$('#total').val(price);
        // 					$('#result').html('');
        // 				}else{
        // 					var json = JSON.parse(data);
        // 					$('#result').html("<h4 class='pull-right text-danger'>"+json.discount+"% Off</h4>");
        // 					$('#total').val(json.price);
        // 				}
        // 			},"json");
        // 		}
        // 	});
        // });
        // function nothing(){
        //     console.log("ami");
        //     // even.preventDefault();
        // }
    </script>
</body>

</html>


<?php require_once('footer.php'); ?>