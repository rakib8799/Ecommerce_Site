<?php
require_once("fpdf/fpdf.php");
require_once("header.php");
require_once("word.php");

//customer and invoice details


//Select Invoice Details From Database
//   $sql="select * from tbl_customer where cust_id='{$_GET["id"]}'";
//   $res=$pdo->query($sql);
//   if($res->num_rows>0){
// 	  $row=$res->fetch_assoc();



if (isset($_POST['submit'])) {
  $month_wise_info = [];
  $total_info = [];
  $date_info = [];
  $fdate = $_POST['fdate'];
  $tdate = $_POST['tdate'];
  $rtype = $_POST['requesttype'];
  if ($rtype == 'mtwise') {
    $month1 = strtotime($fdate);
    $month2 = strtotime($tdate);
    $m1 = date("F", $month1);
    $m2 = date("F", $month2);
    $y1 = date("Y", $month1);
    $y2 = date("Y", $month2);
    $date_info = [
      "date" => $m1 . "-" . $y1 . " to " . $m2 . "-" . $y2
    ];

    $sql1 = "SELECT month(order_date) as lmonth,year(order_date) as lyear,
                                sum(tbl_product.p_current_price * tbl_order.quantity) as total_sell_price,sum(tbl_product.p_buy_price * tbl_order.quantity) as total_buy_price,sum(tbl_product.p_discount_price * tbl_order.quantity) as total_discount_price from tbl_order 
                                join tbl_product on tbl_product.p_id=tbl_order.product_id 
                                where date(tbl_order.order_date) between '$fdate' and '$tdate' 
                                group by lmonth,lyear";
    $statement = $pdo->query($sql1);
    //  $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $cnt = 1;
    $ftotal1 = 0;
    $ftotal2 = 0;
    $ftotal3 = 0;
    $ftotal4 = 0;
    foreach ($result as $row) {
      $ldate = $row["lmonth"] . "/" . $row['lyear'];
      $total1 = $row["total_buy_price"];
      $total2 = $row["total_sell_price"];
      $total3 = $row["total_discount_price"];
      $total4 = ($total2 - $total1) - $total3;
      $profit_margin = number_format(($total4 / $total2) * 100, 2);
      // print_r($row);
      $month_wise_info[] = [
        "ldate" => $ldate,
        "total_buy_price" => $total1,
        "total_sell_price" => $total2,
        "total_discount_price" => $total3,
        "total_profit" => $total4,
        "profit_margin" => $profit_margin
      ];

      $ftotal1 += $total1;
      $ftotal2 += $total2;
      $ftotal3 += $total3;
      $ftotal4 += $total4;
      $cnt++;
    }
    $total_info = [
      "total_expense" => $ftotal1,
      "total_revenue" => $ftotal2,
      "total_discount" => $ftotal3,
      "total_profit" => $ftotal4,
      "total_profit_margin" => number_format(($ftotal4 / $ftotal2) * 100, 2)
    ];
    // print_r($month_wise_info);
    // print_r($total_info);
  } else {
    $month1 = strtotime($fdate);
    $month2 = strtotime($tdate);
    $y1 = date("Y", $month1);
    $y2 = date("Y", $month2);
    $date_info = [
      "date" => $y1 . " to " . $y2
    ];

    $sql1 = "SELECT year(order_date) as lyear,
                                sum(tbl_product.p_current_price * tbl_order.quantity) as total_sell_price,sum(tbl_product.p_buy_price * tbl_order.quantity) as total_buy_price,sum(tbl_product.p_discount_price * tbl_order.quantity) as total_discount_price from tbl_order 
                                join tbl_product on tbl_product.p_id=tbl_order.product_id 
                                where date(tbl_order.order_date) between '$fdate' and '$tdate' 
                                group by lyear";
    $statement = $pdo->query($sql1);
    //  $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $cnt = 1;
    $ftotal1 = 0;
    $ftotal2 = 0;
    $ftotal3 = 0;
    $ftotal4 = 0;
    foreach ($result as $row) {
      $ldate = $row['lyear'];
      $total1 = $row["total_buy_price"];
      $total2 = $row["total_sell_price"];
      $total3 = $row["total_discount_price"];
      $total4 = ($total2 - $total1) - $total3;
      $profit_margin = number_format(($total4 / $total2) * 100, 2);
      // print_r($row);
      $month_wise_info[] = [
        "ldate" => $ldate,
        "total_buy_price" => $total1,
        "total_sell_price" => $total2,
        "total_discount_price" => $total3,
        "total_profit" => $total4,
        "profit_margin" => $profit_margin
      ];

      $ftotal1 += $total1;
      $ftotal2 += $total2;
      $ftotal3 += $total3;
      $ftotal4 += $total4;
      $cnt++;
    }
    $total_info = [
      "total_expense" => $ftotal1,
      "total_revenue" => $ftotal2,
      "total_discount" => $ftotal3,
      "total_profit" => $ftotal4,
      "total_profit_margin" => number_format(($ftotal4 / $ftotal2) * 100, 2)
    ];
    // print_r($month_wise_info);
    // print_r($total_info);
  }
}



class PDF extends FPDF
{
  function Header()
  {

    //Display Company Info
    $this->SetFont('Arial', 'B', 14);
    $this->Cell(50, 10, "SN COMPUTER", 0, 1);
    $this->SetFont('Arial', '', 14);
    $this->Cell(50, 7, "H9JV+MW9,", 0, 1);
    $this->Cell(50, 7, "Trishal, Mymensingh", 0, 1);
    $this->Cell(50, 7, "PHN : +8801712916735", 0, 1);

    //Display INVOICE text
    $this->SetY(15);
    $this->SetX(-60);
    $this->SetFont('Arial', 'B', 18);
    $this->Cell(50, 10, "SALES REPORT", 0, 1);

    //Display Horizontal line
    $this->Line(0, 48, 210, 48);
  }

  function body($month_wise_info, $total_info, $date_info)
  {

    //Billing Details
    $this->SetY(55);
    $this->SetX(10);
    $this->SetFont('Arial', 'B', 16);
    $this->Cell(50, 10, "Sales Report from " . $date_info["date"], 0, 1);
    //   $this->SetFont('Arial','',12);
    //   $this->Cell(50,7,"Name : ".$customer_info["cust_name"],0,1);
    //   $this->Cell(50,7,"Address : ".$customer_info["cust_address"],0,1);
    //   $this->Cell(50,7,"Email : ".$customer_info["cust_email"],0,1);
    //   $this->Cell(50,7,"Contact No. : ".$customer_info["cust_phone"],0,1);
    //   $this->Cell(50,7,"City : ".$customer_info["cust_city"],0,1);

    //Display Invoice no
    //   $this->SetY(55);
    //   $this->SetX(-60);
    //   $this->Cell(50,7,"Invoice No : ".$payment_info["payment_id"]);

    //   //Display Invoice date
    //   $this->SetY(63);
    //   $this->SetX(-60);
    //   $this->Cell(50,7,"Invoice Date : ".$payment_info["payment_date"]);

    //Display Table headings
    $this->SetY(75);
    $this->SetX(10);
    $this->SetFont('Arial', 'B', 12);
    $this->Cell(30, 9, "MONTH/YEAR", 1, 0);
    $this->Cell(30, 9, "EXPENSES", 1, 0, "C");
    $this->Cell(30, 9, "REVENUE", 1, 0, "C");
    $this->Cell(30, 9, "DISCOUNT", 1, 0, "C");
    $this->Cell(30, 9, "PROFIT", 1, 0, "C");
    $this->Cell(30, 9, "MARGIN", 1, 1, "C");
    $this->SetFont('Arial', '', 12);

    //Display table product rows
    foreach ($month_wise_info as $row4) {
      $this->Cell(30, 9, $row4["ldate"], "LR", 0);
      $this->Cell(30, 9, $row4["total_buy_price"], "R", 0, "C");
      $this->Cell(30, 9, $row4["total_sell_price"], "R", 0, "C");
      $this->Cell(30, 9, $row4["total_discount_price"], "R", 0, "C");
      $this->Cell(30, 9, $row4["total_profit"], "R", 0, "C");
      $this->Cell(30, 9, $row4["profit_margin"] . "%", "R", 1, "C");
    }
    //Display table empty rows
    for ($i = 0; $i < 12 - count($month_wise_info); $i++) {
      $this->Cell(30, 9, "", "LR", 0);
      $this->Cell(30, 9, "", "R", 0, "C");
      $this->Cell(30, 9, "", "R", 0, "C");
      $this->Cell(30, 9, "", "R", 0, "C");
      $this->Cell(30, 9, "", "R", 0, "C");
      $this->Cell(30, 9, "", "R", 1, "C");
    }
    //Display table total row
    $this->SetFont('Arial', 'B', 12);
    $this->Cell(60, 9, "TOTAL=" . $total_info["total_expense"], 1, 0, "R");
    //   $this->Cell(40,9,$total_info["total_expense"],"R",0,"R");
    $this->SetFont('Arial', 'B', 12);
    $this->Cell(30, 9, "TOTAL=" . $total_info["total_revenue"], 1, 0, "C");
    //   $this->Cell(40,9,($total_info["total_revenue"],"R",0,"C");
    $this->SetFont('Arial', 'B', 12);
    $this->Cell(30, 9, "TOTAL=" . $total_info["total_discount"], 1, 0, "C");
    $this->SetFont('Arial', 'B', 12);
    $this->Cell(30, 9, "TOTAL=" . $total_info["total_profit"], 1, 0, "C");
    //   $this->Cell(40,9,$total_info["total_profit"]);
    $this->SetFont('Arial', 'B', 12);
    $this->Cell(30, 9, $total_info["total_profit_margin"] . "%", 1, 1, "C");


    //   $this->Cell(30,9,$total_info["total_profit_margin"]);

    //   $obj=new IndianCurrency($month_wise_info["total"]+($month_wise_info["total"]*5)/100);

    //Display amount in words
    //   $this->SetY(240);
    //   $this->SetX(10);
    //   $this->SetFont('Arial','B',12);
    //   $this->Cell(0,9,"Amount in Words ",0,1);
    //   $this->SetFont('Arial','',12);
    //   $this->Cell(0,9,$obj->get_words(),0,1);

  }
  function Footer()
  {

    //set footer position
    $this->SetY(-50);
    //   $this->SetFont('Arial','B',12);
    //   $this->Cell(0,10,"for ABC COMPUTERS",0,1,"R");
    $this->Ln(20);
    $this->SetFont('Arial', '', 12);
    $this->Cell(0, 10, "Authorized Signature", 0, 1, "R");
    $this->SetFont('Arial', '', 10);

    //Display Footer Text
    $this->Cell(0, 10, "This is a computer generated report", 0, 1, "C");
  }
}
//Create A4 Page with Portrait 
$pdf = new PDF("P", "mm", "A4");
$pdf->AddPage();
$pdf->body($month_wise_info, $total_info, $date_info);
ob_end_clean();
$pdf->Output();
