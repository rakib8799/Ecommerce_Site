<?php 
  require_once ("fpdf/fpdf.php");
  require_once ("header.php"); 
  require_once ("word.php"); 

  //customer and invoice details
  $customer_info=[];

  //Select Invoice Details From Database
//   $sql="select * from tbl_customer where cust_id='{$_GET["id"]}'";
//   $res=$pdo->query($sql);
//   if($res->num_rows>0){
// 	  $row=$res->fetch_assoc();
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id=?");
                           $statement->execute(array($_GET['payment_id']));
                           $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                           foreach ($result as $row) {
                            $statement1 = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_name=?");
                           $statement1->execute(array($row['customer_name']));
                           $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                           foreach ($result1 as $row1) {
	  $customer_info=[
		"cust_name"=>$row1["cust_name"],
		"cust_address"=>$row1["cust_address"],
		"cust_phone"=>$row1["cust_phone"],
		"cust_email"=>$row1["cust_email"],
		// "cust_city"=>$row1["cust_city"]
	  ];
  };
};

  $payment_info=[];
//   //Select Invoice Details From Database
//   $sql="select * from payment where SID='{$_GET["id"]}'";
//   $res=$pdo->query($sql);
//   if($res->num_rows>0){
// 	  $row=$res->fetch_assoc();
	  
	//   $payment_info=[
	// 	"payment_id"=>$row["payment_id"],
	// 	"payment_date"=>date("d-m-Y",strtotime($row["payment_date"])),
	// 	"paid_amount"=>$row["paid_amount"]
	//   ];
//   }

$statement3 = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id=?");
                           $statement3->execute(array($_GET['payment_id']));
                           $result3 = $statement3->fetchAll(PDO::FETCH_ASSOC);
                           foreach ($result3 as $row3) {
                            $payment_info=[
                                "payment_id"=>$row3["payment_id"],
                                "payment_date"=>date("d-m-Y",strtotime($row3["payment_date"])),
                                // "paid_amount"=>$row3["paid_amount"]
                              ];
                           };
  
  //invoice Products
  $order_info=[];
  $total_info=[];
  $total = 0;
  
  //Select Invoice Product Details From Database
  $statement2 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                           $statement2->execute(array($_GET['payment_id']));
                           $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
                           foreach ($result2 as $row2) {
//   $sql="select * from tbl_order where id='{$_GET["id"]}'";
//   $res=$pdo->query($sql);
//   if($res->num_row2s>0){
	//   while($row2=$res->fetch_assoc()){
        
		   $order_info[]=[
			"product_name"=>$row2["product_name"],
			"unit_price"=>$row2["unit_price"],
			"quantity"=>$row2["quantity"]
		   ];
           $total=$total+($row2["unit_price"] * $row2["quantity"]);
        // $obj=new IndianCurrency($total);
           $total_info=[
            "total"=>$total,
            // "words"=> $obj->get_words()
           ];
	  };
  
  class PDF extends FPDF
  {
    function Header(){
      
      //Display Company Info
      $this->SetFont('Arial','B',14);
      $this->Cell(50,10,"SN COMPUTER",0,1);
      $this->SetFont('Arial','',14);
      $this->Cell(50,7,"H9JV+MW9,",0,1);
      $this->Cell(50,7,"Trishal, Mymensingh",0,1);
      $this->Cell(50,7,"PHN : +8801712916735",0,1);
      
      //Display INVOICE text
      $this->SetY(15);
      $this->SetX(-40);
      $this->SetFont('Arial','B',18);
      $this->Cell(50,10,"INVOICE",0,1);
      
      //Display Horizontal line
      $this->Line(0,48,210,48);
    }
    
    function body($customer_info,$payment_info,$order_info,$total_info){
      
      //Billing Details
      $this->SetY(55);
      $this->SetX(10);
      $this->SetFont('Arial','B',12);
      $this->Cell(50,10,"Bill To: ",0,1);
      $this->SetFont('Arial','',12);
      $this->Cell(50,7,"Name : ".$customer_info["cust_name"],0,1);
      $this->Cell(50,7,"Address : ".$customer_info["cust_address"],0,1);
      $this->Cell(50,7,"Email : ".$customer_info["cust_email"],0,1);
      $this->Cell(50,7,"Contact No. : ".$customer_info["cust_phone"],0,1);
    //   $this->Cell(50,7,"City : ".$customer_info["cust_city"],0,1);
      
      //Display Invoice no
      $this->SetY(55);
      $this->SetX(-60);
      $this->Cell(50,7,"Invoice No : ".$payment_info["payment_id"]);
      
      //Display Invoice date
      $this->SetY(63);
      $this->SetX(-60);
      $this->Cell(50,7,"Invoice Date : ".$payment_info["payment_date"]);
      
      //Display Table headings
      $this->SetY(95);
      $this->SetX(10);
      $this->SetFont('Arial','B',12);
      $this->Cell(120,9,"PRODUCT NAME",1,0);
      $this->Cell(20,9,"PRICE",1,0,"C");
      $this->Cell(10,9,"QTY",1,0,"C");
      $this->Cell(30,9,"TOTAL",1,1,"C");
      $this->SetFont('Arial','',12);
      
      //Display table product rows
      foreach($order_info as $row4){
        $this->Cell(120,9,$row4["product_name"],"LR",0);
        $this->Cell(20,9,$row4["unit_price"],"R",0,"R");
        $this->Cell(10,9,$row4["quantity"],"R",0,"C");
        $this->Cell(30,9,$row4["unit_price"] * $row4["quantity"],"R",1,"R");
      }
      //Display table empty rows
      for($i=0;$i<12-count($order_info);$i++)
      {
        $this->Cell(120,9,"","LR",0);
        $this->Cell(20,9,"","R",0,"R");
        $this->Cell(10,9,"","R",0,"C");
        $this->Cell(30,9,"","R",1,"R");
      }
      //Display table total row
      $this->SetFont('Arial','B',12);
      $this->Cell(150,9,"SUB TOTAL:",1,0,"R");
      $this->Cell(30,9,$total_info["total"],1,1,"R");
      $this->SetFont('Arial','B',12);
      $this->Cell(150,9,"TAX(5%):",1,0,"R");
      $this->Cell(30,9,($total_info["total"]*5)/100,1,1,"R");
      $this->SetFont('Arial','B',12);
      $this->Cell(150,9,"TOTAL:",1,0,"R");
      $this->Cell(30,9,$total_info["total"]+($total_info["total"]*5)/100,1,1,"R");
      
      $obj=new IndianCurrency($total_info["total"]+($total_info["total"]*5)/100);

      //Display amount in words
      $this->SetY(240);
      $this->SetX(10);
      $this->SetFont('Arial','B',12);
      $this->Cell(0,9,"Amount in Words ",0,1);
      $this->SetFont('Arial','',12);
      $this->Cell(0,9,$obj->get_words(),0,1);
      
    }
    function Footer(){
      
      //set footer position
      $this->SetY(-50);
    //   $this->SetFont('Arial','B',12);
    //   $this->Cell(0,10,"for ABC COMPUTERS",0,1,"R");
      $this->Ln(20);
      $this->SetFont('Arial','',12);
      $this->Cell(0,10,"Authorized Signature",0,1,"R");
      $this->SetFont('Arial','',10);
      
      //Display Footer Text
      $this->Cell(0,10,"This is a computer generated invoice",0,1,"C");
      
    }
    
  }
  //Create A4 Page with Portrait 
  $pdf=new PDF("P","mm","A4");
  $pdf->AddPage();
  $pdf->body($customer_info,$payment_info,$order_info,$total_info);
  ob_end_clean();
  $pdf->Output();
?>