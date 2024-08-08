<?php 
// ADD BRANCH
if (isset($_POST['addBranch'])) {
    $result = $oop->addBranch($_POST['branch'] ,$_POST['location'], $_SESSION['owner_id']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Added successfully','warning','check-circle');
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }elseif ($result == 20) {
        $msgAlert = $oop->alert('Branch already exist','danger','x-circle');
    }
}

// UPDATE BRANCH
if (isset($_POST['updateBranch'])) {
    $result = $oop->updateBranch($_POST['branch'] ,$_POST['location'], $_POST['id']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Saved successfully','warning','check-circle');?>
        <script>function redirect(){window.location = "?";} setTimeout(redirect, 2000);</script><?php
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// DELETE BRANCH
if (isset($_POST['deleteBranch'])) {
    $result = $oop->deleteBranch($_POST['id']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Deleted successfully','warning','check-circle');
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// ADD PROUDCT TO MENU
if (isset($_POST['addProduct'])) {
    $result = $oop->addProduct($_POST['product_name'] ,$_POST['product_type'], $_POST['price'], $productPhoto, $_SESSION['owner_id']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Added successfully','warning','check-circle');
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// UPDATE PRODUCT
if (isset($_POST['updateProduct'])) {
    $result = $oop->updateProduct($_POST['product_name'] ,$_POST['product_type'],$_POST['price'],$_POST['oldProductPhoto'],$productPhoto, $_POST['id']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Saved successfully','warning','check-circle');?>
        <script>function redirect(){window.location = "?";} setTimeout(redirect, 2000);</script><?php
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// DELETE PRODUCT
if (isset($_POST['deleteProduct'])) {
    $result = $oop->deleteProduct($_POST['id']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Deleted successfully','warning','check-circle');
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// ADD PROUDCT TO DETAILS
if (isset($_POST['addDetails'])) {
    $result = $oop->addDetails($_POST['details'], $_SESSION['owner_id']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Added successfully','warning','check-circle');
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// UPDATE DETAILS
if (isset($_POST['updateDetails'])) {
    $result = $oop->updateDetails($_POST['details'], $_POST['id']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Saved successfully','warning','check-circle');?>
        <script>function redirect(){window.location = "?";} setTimeout(redirect, 2000);</script><?php
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// DELETE DETAILS
if (isset($_POST['deleteDetails'])) {
    $result = $oop->deleteDetails($_POST['id']);
    if ($result == 1) {
        $msgAlert = $oop->alert('Deleted successfully','warning','check-circle');
    }elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}
