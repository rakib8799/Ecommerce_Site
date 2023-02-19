<?php
if(isset($_POST['form1'])){
    $final_total = $_POST['final_total'];

    echo "You have to pay ".$final_total."tk.";
}
?>