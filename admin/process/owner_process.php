<?php 
// APPROVE OWNER
if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    $result = $oop->approveOwner($id);
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
    $result = $oop->declineOwner($id);
    if ($result == 1) {
        $msgAlert = $oop->alert('Declined successfully','warning','check-circle');
    }
    elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}

// DELETE owner
if (isset($_POST['deleteOwner'])) {
    $id = $_POST['id'];
    $result = $oop->deleteOwner($id);
    if ($result == 1) {
        $msgAlert = $oop->alert('Deleted successfully','warning','check-circle');
    }
     elseif ($result == 10) {
         $msgAlert = $oop->alert('An error occured','danger','x-circle');
    }
}


