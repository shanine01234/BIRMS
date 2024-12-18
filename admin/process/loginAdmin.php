<?php 
// ADMIN LOGIN PROCESS
if (isset($_POST['loginAdmin'])) {
    $result = $oop->loginAdmin($_POST['username'] ,$_POST['password']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Login successfully','warning','check-circle');?>
        <script>function redirect(){window.location = "index.php?page=dashboard";} setTimeout(redirect, 2000);</script><?php
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('Username does\'t exist','danger','x-circle');
    }
    elseif ($result == 20) {
        $msgAlert = $oop->alert('Incorrect password','danger','x-circle');
    }
}