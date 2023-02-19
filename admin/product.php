<?php require_once('header.php'); ?>
<html>

<body>
	<section class="content-header">
		<div class="content-header-left">
			<h1>View Products</h1>
		</div>
		<div class="content-header-right">
			<a href="product-add.php" class="btn btn-primary btn-sm">Add Product</a>
			<!-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#form_coupon"><span class="glyphicon glyphicon-plus"></span> Generate Coupon</button> -->
		</div>
	</section>

	<section class="content">
		<div class="modal fade" id="form_coupon" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<form action="save_coupon.php" method="POST">
					<div class="modal-content">
						<div class="modal-body">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="form-group">
									<label>Coupon Code</label>
									<input type="text" class="form-control" name="coupon" id="coupon" readonly="readonly" required="required" />
									<br />
									<button id="generate" class="btn btn-success" type="button"><span class="glyphicon glyphicon-random"></span> Generate</button>
								</div>
								<div class="form-group">
									<label>Discount</label>
									<input type="number" class="form-control" name="discount" min="10" required="required" />
								</div>
							</div>
						</div>
						<div style="clear:both;"></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
							<button name="save" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-body table-responsive">
						<table id="example1" class="table table-bordered table-hover table-striped">
							<thead class="thead-dark">
								<tr>
									<th width="10">#</th>
									<th>Photo</th>
									<th width="160">Product Name</th>
									<th width="60">Old Price</th>
									<th width="60">Buy Price</th>
									<th width="60">(C) Price</th>
									<th width="60">Discount Price</th>
									<th width="60">Quantity</th>
									<th>Featured?</th>
									<th>Active?</th>
									<th>Category</th>
									<th width="80">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 0;
								$statement = $pdo->prepare("SELECT
														
														t1.p_id,
														t1.p_name,
														t1.p_old_price,
														t1.p_buy_price,
														t1.p_current_price,
														t1.p_discount_price,
														t1.p_qty,
														t1.p_featured_photo,
														t1.p_is_featured,
														t1.p_is_active,
														t1.ecat_id,

														t2.ecat_id,
														t2.ecat_name,

														t3.mcat_id,
														t3.mcat_name,

														t4.tcat_id,
														t4.tcat_name

							                           	FROM tbl_product t1
							                           	JOIN tbl_end_category t2
							                           	ON t1.ecat_id = t2.ecat_id
							                           	JOIN tbl_mid_category t3
							                           	ON t2.mcat_id = t3.mcat_id
							                           	JOIN tbl_top_category t4
							                           	ON t3.tcat_id = t4.tcat_id
							                           	ORDER BY t1.p_id DESC
							                           	");
								$statement->execute();
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result as $row) {
									$i++;
								?>
									<tr>
										<td><?php echo $i; ?></td>
										<td style="width:82px;"><img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>" style="width:80px;"></td>
										<td><?php echo $row['p_name']; ?></td>
										<td>&#2547;<?php echo $row['p_old_price']; ?></td>
										<td>&#2547;<?php echo $row['p_buy_price']; ?></td>
										<td>&#2547;<?php echo $row['p_current_price']; ?></td>
										<td>&#2547;<?php echo $row['p_discount_price']; ?></td>
										<td><?php echo $row['p_qty']; ?></td>
										<td>
											<?php if ($row['p_is_featured'] == 1) {
												echo '<span class="badge badge-success" style="background-color:green;">Yes</span>';
											} else {
												echo '<span class="badge badge-success" style="background-color:red;">No</span>';
											} ?>
										</td>
										<td>
											<?php if ($row['p_is_active'] == 1) {
												echo '<span class="badge badge-success" style="background-color:green;">Yes</span>';
											} else {
												echo '<span class="badge badge-danger" style="background-color:red;">No</span>';
											} ?>
										</td>
										<td><?php echo $row['tcat_name']; ?><br><?php echo $row['mcat_name']; ?><br><?php echo $row['ecat_name']; ?></td>
										<td>
											<a href="product-edit.php?id=<?php echo $row['p_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
											<a href="#" class="btn btn-danger btn-xs" data-href="product-delete.php?id=<?php echo $row['p_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>
										</td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>


	<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure want to delete this item?</p>
					<p style="color:red;">Be careful! This product will be deleted from the order table, payment table, size table, color table and rating table also.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<a class="btn btn-danger btn-ok">Delete</a>
				</div>
			</div>
		</div>
	</div>
	<script src="assets/js/jquery-3.2.1.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		// 	function generate_coupon(){
		// 		const coupon = document.getElementById("coupon");
		// 		let xhttp = new XMLHttpRequest();
		// xhttp.open("GET", "get_coupon.php", true);
		// xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// xhttp.onreadystatechange = function() {
		//    if (this.readyState == 4 && this.status == 200) {

		//       // Response
		//       const response = this.responseText; 
		// 	  coupon.value = response;
		//    }
		// };
		// xhttp.send();
		// }
		// 	console.log("HEllo");
		// 	const generate = document.getElementById("generate");
		// console.log(generate);
		// $(document).ready(function(){
		// 	$('#generate').on('click', function(){
		// 		$.get("get_coupon.php", function(data){
		// 			$('#coupon').val(data);
		// 		});
		// 	});
		// });
		$(document).ready(function() {
			$('#generate').on('click', function() {
				$.get("get_coupon.php", function(data) {
					$('#coupon').val(data);
				});
			});
		});
	</script>
</body>

</html>

<?php require_once('footer.php'); ?>