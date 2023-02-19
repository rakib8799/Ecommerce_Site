<?php 
	require_once 'header.php';
 ?>
<?php
	$coupon_code = $_POST['coupon'];
	$price = $_POST['price'];
 
	$statement = $pdo->prepare("SELECT * FROM tbl_coupon WHERE coupon_code = ? AND status = 'Valid'");
									$statement->execute(array($coupon_code));
									$result = $statement->fetch(PDO::FETCH_BOTH);	
                                    $row=$statement->rowCount();
	// $fetch = mysqli_fetch_array($query);
	$array = [];
	if($row > 0){
		$discount = $result['discount'] / 100;
		$total = $discount * $price;
		$array['discount'] = $result['discount'];
		$array['price'] = $price - $total;
 
		echo json_encode($array);
 
	}else{
		echo "error";
	}
?>

<?php
	require_once 'header.php';
?>