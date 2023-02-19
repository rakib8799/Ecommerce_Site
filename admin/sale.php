<!-- select product, year(order_date),month(order_date),sum(sale)
          from sales
          group by product, year(order_date),month(order_date)
          order by product, year(order_date),month(order_date); -->




          <?php require_once('header.php'); ?>

<?php
?>


<!--center-->
<div class="col-sm-8">
    <div class="row">
      <div class="col-xs-12">
        <h3 class="text-center">Sales Report</h3>
		<hr >
		<form name="bwdatesdata" action="sale_report_process.php" method="post" action="">
  <table width="100%" height="117"  border="0">
<tr>
    <th width="27%" height="63" scope="row">From Date :</th>
    <td width="73%">
<input type="date" name="fdate" class="form-control" id="fdate">
    	</td>
  </tr>

  <tr>
    <th width="27%" height="63" scope="row">To Date :</th>
    <td width="73%">
    	<input type="date" name="tdate" class="form-control" id="tdate"></td>
  </tr>
  <tr>
    <th width="27%" height="63" scope="row">Request Type :</th>
    <td width="73%">
         <input type="radio" name="requesttype" value="mtwise" checked="true">Month wise
          <input type="radio" name="requesttype" value="yrwise">Year wise</td>
  </tr>
<tr>
    <th width="27%" height="63" scope="row"></th>
    <td width="73%">
    	<button class="btn-primary btn" type="submit" name="submit">Submit</button>
  </tr>
 
</table>
     </form>
 
      </div>
    </div>
    <hr>
      <div class="row">
      <div class="col-xs-12">
      	 <?php
      	 if(isset($_POST['submit']))
{ 
$fdate=$_POST['fdate'];
$tdate=$_POST['tdate'];
$rtype=$_POST['requesttype'];

?>
<?php if($rtype=='mtwise'){
$month1=strtotime($fdate);
$month2=strtotime($tdate);
$m1=date("F",$month1);
$m2=date("F",$month2);
$y1=date("Y",$month1);
$y2=date("Y",$month2);
    ?>
        <h4 class="header-title m-t-0 m-b-30">Sales Report Month Wise</h4>
<h4 align="center" style="color:blue">Sales Report  from <?php echo $m1."-".$y1;?> to <?php echo $m2."-".$y2;?></h4>
		<hr >
		<div class="row">
                            <table class="table table-bordered" width="100%"  border="0" style="padding-left:40px">
                                <thead>
                                   <tr>
<th>S.N</th>
<th>Month / Year </th>
<th>Expenses</th>
<th>Revenue</th>
<th>Net Profit</th>
<th>Net Profit Margin(%)</th>
<!-- <th>Expense</th> -->
</tr>
                                </thead>
                                <?php
                                // $sql = "SELECT * from tbl_order 
                                // where date(tbl_order.order_date) between '$fdate' and '$tdate'";
                                $sql1 = "SELECT month(order_date) as lmonth,year(order_date) as lyear,
                                sum(tbl_product.p_current_price * tbl_order.quantity) as total_sell_price,sum(tbl_product.p_buy_price * tbl_order.quantity) as total_buy_price from tbl_order 
                                join tbl_product on tbl_product.p_id=tbl_order.product_id 
                                where date(tbl_order.order_date) between '$fdate' and '$tdate' 
                                group by lmonth,lyear";
                                 $statement = $pdo->query($sql1);
                                //  $statement->execute();
                                 $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                 $cnt=1;
                                 $ftotal1=0;
                                 $ftotal2=0;
                                 $ftotal3=0;
                                 foreach ($result as $row) {
                                    // print_r($row);
                                    ?>
                                    <tbody>
                                    <tr>
                                    <td><?php echo $cnt;?></td>
                                    <td><?php  echo $row['lmonth']."/".$row['lyear'];?></td>
                                    <td><?php  echo $total1=$row['total_buy_price'];?></td>
              <td><?php  echo $total2=$row['total_sell_price'];?></td>
              <td><?php  echo $total3 = $total2-$total1;?></td>
              <td><?php  echo number_format(($total3/$total2)*100,2) ."%";?></td>
                    </tr>
                    <?php
$ftotal1+=$total1;
$ftotal2+=$total2;
$ftotal3+=$total3;
$cnt++;
                                 }

?>
<tr>
                  <td colspan="2" align="center">Total </td>
              <td><?php  echo $ftotal1;?></td>
              <td><?php  echo $ftotal2;?></td>
              <td><?php  echo $ftotal3;?></td>
              <td><?php  echo number_format(($ftotal3/$ftotal2)*100,2) ."%";?></td>
                 
                </tr>             
                                </tbody>
                            </table>
                            <?php }
                            
                            else {
                                $year1=strtotime($fdate);
                                $year2=strtotime($tdate);
                                $y1=date("Y",$year1);
                                $y2=date("Y",$year2);
                                ?>
                                                       <h4 class="header-title m-t-0 m-b-30">Sales Report Year Wise</h4>
                                <h4 align="center" style="color:blue">Sales Report  from <?php echo $y1;?> to <?php echo $y2;?></h4>
                                        <hr >
                                        <div class="row">
                                                            <table class="table table-bordered" width="100%"  border="0" style="padding-left:40px">
                                                                <thead>
                                                                   <tr>
                                <th>S.N</th>
                                <th>Year </th>
                                <th>Expenses</th>
<th>Revenue</th>
<th>Net Profit</th>
<th>Net Profit Margin(%)</th>
                                </tr>
                                                                </thead>
                                                                <?php
                                // $sql = "SELECT * from tbl_order 
                                // where date(tbl_order.order_date) between '$fdate' and '$tdate'";
                                $sql1 = "SELECT year(order_date) as lyear,
                                sum(tbl_product.p_current_price * tbl_order.quantity) as total_sell_price,sum(tbl_product.p_buy_price * tbl_order.quantity) as total_buy_price from tbl_order 
                                join tbl_product on tbl_product.p_id=tbl_order.product_id 
                                where date(tbl_order.order_date) between '$fdate' and '$tdate' 
                                group by lyear";
                                 $statement1 = $pdo->query($sql1);
                                //  $statement1->execute();
                                 $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                 $cnt=1;
                                 $ftotal1=0;
                                 $ftotal2=0;
                                 $ftotal3=0;
                                 foreach ($result1 as $row1) {
                                    // print_r($row1);
                                    ?>
                                    <tbody>
                                    <tr>
                    <td><?php echo $cnt;?></td>
                  <td><?php  echo $row1['lyear'];?></td>
                  <td><?php  echo $total1=$row1['total_buy_price'];?></td>
              <td><?php  echo $total2=$row1['total_sell_price'];?></td>
              <td><?php  echo $total3 = $total2-$total1;?></td>
              <td><?php  echo number_format(($total3/$total2)*100,2) ."%";?></td>
             
                    </tr>
                    <?php
$ftotal1+=$total1;
$ftotal2+=$total2;
$ftotal3+=$total3;
$cnt++;
                                 }
                                ?>
                                <tr>
                                                  <td colspan="2" align="center">Total </td>
                                                  <td><?php  echo $ftotal1;?></td>
              <td><?php  echo $ftotal2;?></td>
              <td><?php  echo $ftotal3;?></td>
              <td><?php  echo number_format(($ftotal3/$ftotal2)*100,2) ."%";?></td>
                                                 
                                                </tr>             
                                                                </tbody>
                                                            </table>  
                                                            <?php

                                }}?>
                                                        </div>
                                 
                                      </div>
                                    </div>  
                                   
                                  </div><!--/center-->

<?php require_once('footer.php'); ?>