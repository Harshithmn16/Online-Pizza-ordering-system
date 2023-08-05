<?php
    include '_dbconnect.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['createItem'])) {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $categoryId = $_POST["categoryId"];
        $price = $_POST["price"];

        $result1 = mysqli_query($conn, "SELECT MAX(pizzaId) FROM pizza");
        $row = mysqli_fetch_assoc($result1);

        //incrementing it by 1
        if(is_numeric($row['MAX(pizzaId)'])){
            $new_value = intval($row['MAX(pizzaId)']) + 1;
        }

        $sql = "INSERT INTO `pizza` (`pizzaId`,`pizzaName`, `pizzaPrice`, `pizzaDesc`, `pizzaCategorieId`, `pizzaPubDate`) VALUES ('$new_value','$name', '$price', '$description', '$categoryId', current_timestamp())";   
        try {
            // code that may throw an exception
            $result = mysqli_query($conn, $sql);
        } catch (mysqli_sql_exception $e) {
            // handle the exception
            echo "duplicate pizza" . $e->getMessage();
            echo "<script>toastr.error('Error: " . $e->getMessage() . "')</script>";
        }
        
       // $result = mysqli_query($conn, $sql);
        $pizzaId = $conn->insert_id;
        if ($result){
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
                
                $newName = 'pizza-'.$pizzaId;
                $newfilename=$newName .".jpg";

                $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/DBMSmini/img/';
                $uploadfile = $uploaddir . $newfilename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
                    echo "<script>alert('success');
                            window.location=document.referrer;
                        </script>";
                } else {
                            
                             echo "<script>alert('Pizza Name already Exists')
                            window.location=document.referrer;
                       </script>";
                }

            }
            else{
                echo '<script>alert("Please select an image file to upload.");
                        window.location=document.referrer;
                    </script>';
            }
        }
        else {
            echo "<script>alert('Pizza Name already Exists');
                    window.location=document.referrer;
               </script>";
        }
    }
    if(isset($_POST['removeItem'])) {
        $pizzaId = $_POST["pizzaId"];
        $sql = "DELETE FROM `pizza` WHERE `pizzaId`='$pizzaId'";   
        $result = mysqli_query($conn, $sql);
        $filename = $_SERVER['DOCUMENT_ROOT']."/DBMSmini/img/pizza-".$pizzaId.".jpg";
        if ($result){
            if (file_exists($filename)) {
                unlink($filename);
            }
            echo "<script>alert('Removed');
                window.location=document.referrer;
            </script>";
        }
        else {
            echo "<script>alert('failed');
            window.location=document.referrer;
            </script>";
        }
    }
    if(isset($_POST['updateItem'])) {
        $pizzaId = $_POST["pizzaId"];
        $pizzaName = $_POST["name"];
        $pizzaDesc = $_POST["desc"];
        $pizzaPrice = $_POST["price"];
        $pizzaCategorieId = $_POST["catId"];

        $sql = "UPDATE `pizza` SET `pizzaName`='$pizzaName', `pizzaPrice`='$pizzaPrice', `pizzaDesc`='$pizzaDesc', `pizzaCategorieId`='$pizzaCategorieId' WHERE `pizzaId`='$pizzaId'";   
        $result = mysqli_query($conn, $sql);
        if ($result){
            echo "<script>alert('update');
                window.location=document.referrer;
                </script>";
        }
        else {
            echo "<script>alert('failed');
                window.location=document.referrer;
                </script>";
        }
    }
    if(isset($_POST['updateItemPhoto'])) {
        $pizzaId = $_POST["pizzaId"];
        $check = getimagesize($_FILES["itemimage"]["tmp_name"]);
        if($check !== false) {
            $newName = 'pizza-'.$pizzaId;
            $newfilename=$newName .".jpg";

            $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/DBMSmini/img/';
            $uploadfile = $uploaddir . $newfilename;

            if (move_uploaded_file($_FILES['itemimage']['tmp_name'], $uploadfile)) {
                echo "<script>alert('success');
                        window.location=document.referrer;
                    </script>";
            } else {
                echo "<script>alert('failed');
                        window.location=document.referrer;
                    </script>";
            }
        }
        else{
            echo '<script>alert("Please select an image file to upload.");
            window.location=document.referrer;
                </script>';
        }
    }
}
?>

<!-- discount price -->
<?php
 include '_dbconnect.php';
 if($_SERVER["REQUEST_METHOD"] == "GET"){
$price = 100;
$discount = 0.5;
$discounted_price = $price - ($price * $discount);



$sql = "UPDATE pizza SET pizzaPrice = '$discounted_price' WHERE pizzaId = 3";
$sql = "UPDATE pizza SET pizzaPrice = '$discounted_price' WHERE pizzaId = 4";
$sql = "UPDATE pizza SET pizzaPrice = '$discounted_price' WHERE pizzaId = 5";
$sql = "UPDATE pizza SET pizzaPrice = '$discounted_price' WHERE pizzaId = 6";
$sql = "UPDATE pizza SET pizzaPrice = '$discounted_price' WHERE pizzaId = 7";

if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}


 }
?>
