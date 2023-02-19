<?php require_once('header.php'); ?>
<?php
// if(isset($_POST['save'])){
function save_coupon($coupon_code, $discount, $pdo, $product_id)
{
	$status = "Valid";
	$statement = $pdo->prepare("SELECT * FROM tbl_coupon WHERE coupon_code = ?");
	$statement->execute(array($coupon_code));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	// print_r($result);
	$row = $statement->rowCount();

	if ($row > 0) {
		echo "<script>alert('Coupon is already in use')</script>";
		echo "<script>window.location = 'index.php'</script>";
	} else {
		// $coupon_code = $result['coupon_code'];
		// $discount = $result['discount'];
		// if (!empty($coupon_code) && !empty($discount)) {
		$statement = $pdo->prepare("INSERT INTO tbl_coupon(
				product_id,
					coupon_code,
					discount,
					status
				) VALUES (?,?,?,?)");
		$statement->execute(array(
			$product_id,
			$coupon_code,
			$discount,
			$status
		));
		echo "<script>alert('Coupon is Saved!')</script>";
		echo "<script>window.location = 'index.php'</script>";
		// }
	}
}
// }
?>
<?php require_once('footer.php'); ?>

