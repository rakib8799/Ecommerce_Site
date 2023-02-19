<?php include('mail.php'); ?>

<?php

// sent mail
// $receiver = $_SESSION['author_email'];
$receiver = "mkrakib328@gmail.com";
$subject = "Paper Resubmission";
$body = '<h5>Dear Sir/Madam, <br />You have successfully re-submitted your paper.Please check your paper status. <br /> <br /> Best Regards, JKKNIU Journal Organization</h5>';
$send_mail = send_mail($receiver, $subject, $body);

// $_SESSION['resubmit_next_page'] = 0; 
?>
    <!-- <script>
        window.alert("Your Paper Has Successfully Re-Submitted");
        window.location = "new_papers.php";
    </script> -->
<?php
?>