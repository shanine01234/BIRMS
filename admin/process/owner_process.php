<?php 
// APPROVE OWNER
if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    $result = $oop->approveCustomer($id);
    if ($result == 1) {
        $msgAlert = $oop->alert('Accepted successfully','warning','check-circle');
    }
    elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// DECLINE OWNER
if (isset($_POST['decline'])) {
    $id = $_POST['id'];
    $result = $oop->declineCustomer($id);
    if ($result == 1) {
        $msgAlert = $oop->alert('Declined successfully','warning','check-circle');
    }
    elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// DELETE CRITERIA
if (isset($_POST['deleteCustomer'])) {
    $id = $_POST['id'];
    $result = $oop->deleteCustomer($id);
    if ($result == 1) {
        $msgAlert = $oop->alert('Deleted successfully','warning','check-circle');
    }
     elseif ($result == 10) {
         $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}
?>
