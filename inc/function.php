<?php 
    // START SESSION
    session_start();
    // DATABASE CONNECTION
    class Connection{
        public $host = "127.0.0.1";
         public $user = "u510162695_birms_db";
         public $password = "1Birms_db";
         public $db_name = "u510162695_birms_db";
       // public $user = "root";
        //public $password = "";
       // public $db_name = "birms_db";
        public $conn;


        public function __construct(){
            $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->db_name);
        }
    }

    // OTHER OPERATIONS HERE!!!
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y');

    // OWNER RANDOM ID
    $random_id = substr(str_shuffle(str_repeat("0123456789", 5)), 0, 5);

    // RANDOM PASS
    $randomPass = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYX", 5)), 0, 10);
    // PASSWORD HASH RANDOM PASS
    $hashed = password_hash($randomPass, PASSWORD_DEFAULT);

    // UPLOAD PRODUCT PHOTO
    @$oldProductPhoto = $_POST['oldProductPhoto'];
    @$productPhoto = $_FILES['productPhoto']['name'];
    if ($productPhoto != '') {
        move_uploaded_file($_FILES['productPhoto']['tmp_name'],'../img/product/'.$productPhoto);
    }else {
        $productPhoto = $oldProductPhoto;
    }

    // UPLOAD RESTOBAR PHOTO
    @$restoPhoto = $_FILES['restoPhoto']['name'];
    if ($restoPhoto != '') {
        move_uploaded_file($_FILES['restoPhoto']['tmp_name'],'../img/photos/'.$restoPhoto);
    }

    $msgAlert = "";

    // OTHER OPERATIONS ENDS HERE!!!

    // CLASS DATAOPERATION EXTEND TO DB !!!
    class dataOperation extends Connection{

        // ADMIN SIDE ALERT !!!
        public function alert($msg, $bg, $icon){
            $msgAlert = "
            <span class='badge bg-$bg text-center text-light rounded p-2 mb-2' id='hideThis'>
            <i class='align-middle' data-feather='$icon'></i>   
                $msg!
            </span>
            <script>
                function hideThis(){
                    document.getElementById('hideThis').style.visibility='hidden';
                    document.getElementById('hideThis').style.marginTop='-40px';
                    document.getElementById('hideThis').style.transition = 'all 0.3s';
                } 
                setTimeout(hideThis, 3000);
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.href);
                }
            </script>
            ";
              return $msgAlert;
        }

        // ADMIN LOGIN PROCESS HERE!!!
        public function loginAdmin($username, $password){
            $sql = mysqli_query($this->conn, "SELECT * FROM admin WHERE username='$username'");
            $row = mysqli_fetch_assoc($sql);
            if (mysqli_num_rows($sql) > 0) {
                $hashed_password = $row['password'];
                if (!password_verify($password, $hashed_password)) {
                    return 20;
                }else {
                    $this->id = $row['id'];
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $this->id;
                    return 1;
                }
            }else{
                return 10;
            }
        }


        // OWNER LOGIN PROCESS
        public function loginOwner($email, $password){
            $sql = mysqli_query($this->conn, "SELECT * FROM owner WHERE email='$email'");
            $row = mysqli_fetch_assoc($sql);
            if (mysqli_num_rows($sql) > 0) {
                $hashed_password = $row['password'];
                if (!password_verify($password, $hashed_password)) {
                    return 20;
                }elseif ($row['status'] == 0) {
                    return 30;
                }elseif ($row['status'] == 2) {
                    return 40;
                }else {
                    $this->id = $row['owner_id'];
                    $_SESSION['login'] = true;
                    $_SESSION['owner_id'] = $this->id;
                    return 1;
                }
            }else{
                return 10;
            }
        }

        // OWNER REGISTER PROCESS
        public function registerOwner($fname, $mname, $lname, $email, $password, $cpassword, $restobar, $contact_num, $address, 
        $restoPhoto, $random_id, $gcash_num, $gcash_qr){
        
        // Check if the email already exists
        $sql = mysqli_query($this->conn, "SELECT * FROM owner WHERE email='$email'");
        if (mysqli_num_rows($sql) > 0) {
            return 10; // Email already exists
        } elseif ($password != $cpassword) {
            return 20; // Passwords do not match
        } elseif (!is_numeric($contact_num)) {
            return 30; // Contact number is not numeric
        } else {
            // Hash the password
            $hashpassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Prepare the query
            $query = "INSERT INTO owner (owner_id, firstname, middlename, lastname, email, password, contact_num, address, gcash_num, gcash_qr) 
                      VALUES ('$random_id', '$fname', '$mname', '$lname', '$email', '$hashpassword', '$contact_num', '$address', '$gcash_num', '$gcash_qr')";
            
            // Execute the query and check for errors
            if (!mysqli_query($this->conn, $query)) {
                die('Error: ' . mysqli_error($this->conn)); // Display error message
            }
    
            // Insert into restobar table
            $querys = "INSERT INTO restobar (owner_id, resto_name, resto_photo) VALUES ('$random_id', '$restobar', '$restoPhoto')";
            if (!mysqli_query($this->conn, $querys)) {
                die('Error: ' . mysqli_error($this->conn)); // Display error message
            }
            
            return 1; // Success
        }
    }
    

        // DISPLAY OWNER DETAILS
        public function displayOwnerDets($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM owner WHERE owner_id = '$id'");
            $array = array();
            $row = mysqli_fetch_assoc($sql);
            $array[] = $row;
            return $array;
        }

        // DISPLAY VERIFIED OWNER
        public function displayOwner(){
            $sql = mysqli_query($this->conn, "SELECT * FROM owner WHERE status = 1");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // DISPLAY COUNT OF VERIFIED OWNER
        public function displayVOCnt(){
            $sql = mysqli_query($this->conn, "SELECT COUNT(*) AS owner FROM owner WHERE status=1");
            $array = array();
            $cnt = mysqli_fetch_assoc($sql);
            $array[] = $cnt;
            return $array;
        }

        // DISPLAY COUNT OF PRODCUTS
        public function displayPRCnt(){
            $sql = mysqli_query($this->conn, "SELECT COUNT(*) AS product FROM menu");
            $array = array();
            $cnt = mysqli_fetch_assoc($sql);
            $array[] = $cnt;
            return $array;
        }

         // DISPLAY COUNT OF RESTOBAR
         public function displayResCnt(){
            $sql = mysqli_query($this->conn, "SELECT COUNT(*) AS resto FROM owner INNER JOIN restobar ON owner.owner_id = restobar.owner_id WHERE owner.status = 1");
            $array = array();
            $cnt = mysqli_fetch_assoc($sql);
            $array[] = $cnt;
            return $array;
        }

         // DISPLAY VERIFIED CUSTOMER
         public function displayVerifiedCustomer(){
            $sql = mysqli_query($this->conn, "SELECT * FROM customer WHERE status = 1");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }


        // DISPLAY VERIFIED CUSTOMER
        public function displayCustomer(){
            $sql = mysqli_query($this->conn, "SELECT * FROM customer WHERE status = 1");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // DISPLAY PENDING OWNER
        public function displayOwnerPending(){
            $sql = mysqli_query($this->conn, "SELECT * FROM owner WHERE status = 0");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // DISPLAY DECLINED OWNER
        public function displayOwnerDeclined(){
            $sql = mysqli_query($this->conn, "SELECT * FROM owner WHERE status = 2");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // DISPLAY COUNT OF PENDING OWNER
        public function displayPOCnt(){
            $sql = mysqli_query($this->conn, "SELECT COUNT(*) AS pending FROM owner WHERE status=0");
            $array = array();
            $cnt = mysqli_fetch_assoc($sql);
            $array[] = $cnt;
            return $array;
        }

        // ACCEPT OWNER
        public function approveOwner($id){
            try {
                $query = "UPDATE owner SET status=1 WHERE id='$id'";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

        // DECLINE OWNER
        public function declineOwner($id){
            try {
                $query = "UPDATE owner SET status=2 WHERE id='$id'";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

         // DELETE OWNER
         public function deleteOwner($id){
            try {
                $query = "DELETE FROM owner WHERE id='$id'";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

  

        // DISPLAY OWNER RESTOBAR DETAILS
        public function displayRestoDets($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM restobar WHERE owner_id = '$id'");
            $array = array();
            $row = mysqli_fetch_assoc($sql);
            $array[] = $row;
            return $array;
        }

        // DISPLAY VERIFIED OWNER BRANCHES
        public function displayBranches($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM branches WHERE owner_id='$id'");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // DISPLAY COUNT OWNER BRANCHES
        public function displayBrCnt($id){
            $sql = mysqli_query($this->conn, "SELECT COUNT(*) as branches FROM branches WHERE owner_id='$id'");
            $array = array();
            $row = mysqli_fetch_assoc($sql);
                $array[] = $row;
            
            return $array;
        }

        // DISPLAY VERIFIED OWNER BRANCHES UPDATE
        public function displayBranchesByID($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM branches WHERE id='$id'");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // ADD BRANCH
        public function addBranch($branch, $location, $owner_id){
            try {
                $dups = "SELECT * FROM branches WHERE branch = '$branch' AND owner_id='$owner_id'";
                $res = mysqli_query($this->conn, $dups);
                if (mysqli_num_rows($res) > 0) {
                    return 20;
                }else{
                    $query = "INSERT INTO branches (owner_id, branch, location) VALUES ('$owner_id','$branch','$location')";
                    mysqli_query($this->conn, $query);
                    return 1;
                }
            } catch (\Throwable $e) {
                return 10;
            }
        }

        // UPDATE BRANCH
        public function updateBranch($branch, $location, $id){
            try {
                $query = "UPDATE branches SET branch='$branch',location='$location' WHERE id='$id'";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

        // DELETE BRANCH
        public function deleteBranch($id){
            try {
                $query = "DELETE FROM branches WHERE id='$id'";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

         // ADD PRODUCT
         public function addProduct($product_name, $product_type,  $price, $product_photo, $owner_id){
            try {
                $query = "INSERT INTO menu (owner_id, product_name, product_type, price, product_photo) VALUES ('$owner_id','$product_name','$product_type','$price','$product_photo')";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

        // DISPLAY VERIFIED OWNER PRODUCT
        public function displayMenu($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM menu WHERE owner_id='$id'");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // DISPLAY COUNT OF OWNER PRODUCT
        public function displayMenuCnt($id){
            $sql = mysqli_query($this->conn, "SELECT COUNT(*) as total_menu FROM menu WHERE owner_id='$id'");
            $array = array();
           $row = mysqli_fetch_assoc($sql);
                $array[] = $row;
            
            return $array;
        }

        // DISPLAY VERIFIED OWNER PRODUCT BY ID
        public function displayMenuByID($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM menu WHERE id='$id'");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // UPDATE PRODUCT
        public function updateProduct($product_name, $product_type, $price, $oldProductPhoto, $productPhoto, $id){
            try {
                $query = "UPDATE menu SET product_name='$product_name',product_type='$product_type',price='$price',product_photo='$productPhoto' WHERE id='$id'";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

        // DELETE PRODUCT
        public function deleteProduct($id){
            try {
                $query = "DELETE FROM menu WHERE id='$id'";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }
        
        // DISPLAY VERIFIED RESTOBAR
        public function displayRestobar(){
            $sql = mysqli_query($this->conn, "SELECT * FROM owner INNER JOIN restobar ON owner.owner_id = restobar.owner_id 
            WHERE owner.status = 1");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

         // DISPLAY VERIFIED RESTOBAR
         public function displaySelectedRestobar($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM owner INNER JOIN restobar ON owner.owner_id = restobar.owner_id WHERE owner.owner_id = '$id'");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

         // DISPLAY FOODS
         public function displayFoods($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM menu WHERE product_type='Food' AND owner_id = '$id'");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // DISPLAY DRINKS
        public function displayDrinks($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM menu WHERE product_type='Drinks' AND owner_id = '$id'");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // DISPLAY VERIFIED OWNER BRANCHES UPDATE
        public function displayReservedTable($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM book INNER JOIN tables ON book.table_id=tables.id WHERE tables.owner_id='$id'");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // ADD DETAILS
        public function addDetails($details, $owner_id){
            try {
                $query = "INSERT INTO restobar_details (details, owner_id) VALUES ('$details','$owner_id')";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

        // UPDATE DETAILS
        public function updateDetails($details, $id){
            try {
                $query = "UPDATE restobar_details SET details='$details' WHERE id='$id'";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

         // DISPLAY DETAILS
         public function displayDetails($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM restobar_details WHERE owner_id='$id'");
            $array = array();
            while($row = mysqli_fetch_assoc($sql)){
                $array[] = $row;
            }
            return $array;
        }

        // DELETE DETAILS
        public function deleteDetails($id){
            try {
                $query = "DELETE FROM restobar_details WHERE id='$id'";
                mysqli_query($this->conn, $query);
                return 1;
            } catch (\Throwable $e) {
                return 10;
            }
        }

        // DISPLAY DETAILS BY ID
        public function displayDetailsByID($id){
            $sql = mysqli_query($this->conn, "SELECT * FROM restobar_details WHERE id='$id'");
            $array = array();
            $row = mysqli_fetch_assoc($sql);
                $array[] = $row;
            
            return $array;
        }
        public function placeOrder($user_id, $product_id, $quantity) {
            // Example database insertion logic
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)");
            return $stmt->execute([$user_id, $product_id, $quantity]);
        }
    // In your class file
public function getOrderCount($user_id) {
    // Prepare the SQL query to count the number of orders
    $query = "SELECT COUNT(*) AS order_count FROM orders WHERE user_id = ?";
    
    // Prepare and execute the query
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Return the order count
    return $row['order_count'];
}


    
    }
    $oop = new dataOperation; 
