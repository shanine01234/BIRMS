<?php 
// ORGANIZER LOGIN PROCESS
if (isset($_POST['loginOwner'])) {
    $result = $oop->loginOwner($_POST['owner_email'] ,$_POST['password']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Login successfully','warning','check-circle');?>
        <script>function redirect(){window.location = "index.php";} setTimeout(redirect, 2000);</script><?php
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('Email not found','danger','x-circle');
    }elseif ($result == 20) {
        $msgAlert = $oop->alert('Incorrect password','danger','x-circle');
    }elseif ($result == 30) {
        $msgAlert = $oop->alert('Please wait for your account to be approved by the admin','danger','x-circle');
    }elseif ($result == 40) {
        $msgAlert = $oop->alert('Your account request has been declined by the admin','danger','x-circle');
    }
    
}