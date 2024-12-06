<?php 
// OWNER REGISTER PROCESS
if (isset($_POST['registerOwner'])) {
    $result = $oop->registerOwner($_POST['firstname'] ,$_POST['middlename'],$_POST['lastname'],$_POST['email'],$_POST['password'],$_POST['cpassword'],$_POST['restobar'],$_POST['contact_num'],$_POST['address'],$restoPhoto, $random_id, $_POST['gcash_num'], date('mdGis') . '.png');
    if ($result == 1) {
        $filename = date('mdGis') . ".png";
        $tmp_name = $_FILES['gcash_qr']['tmp_name'];
        $folder = "../img/gcash_qr/" . $filename;

        move_uploaded_file($tmp_name, $folder);
        
        $msgAlert = $oop->alert('Registered successfully','warning','check-circle');?>
        <script>function redirect(){window.location = "login.php";} setTimeout(redirect, 2000);</script><?php
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('Email is already used','danger','x-circle');
    }elseif ($result == 20) {
        $msgAlert = $oop->alert('Password didn\'t match','danger','x-circle');
    }elseif ($result == 30) {
        $msgAlert = $oop->alert('Please input correct contact number','danger','x-circle');
    }
    
}
