

<?php
    include '_dbconnect.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['createCategory'])) {
        $name = $_POST["name"];
        $desc = $_POST["desc"];

        //fetching max value from table 
        $result1 = mysqli_query($conn, "SELECT MAX(categorieId) FROM categories");
        $row = mysqli_fetch_assoc($result1);


        //incrementing it by 1
        if(is_numeric($row['MAX(categorieId)'])){
            $new_value = intval($row['MAX(categorieId)']) + 1;
        }


        $sql = "INSERT INTO `categories` (`categorieId`,`categorieName`, `categorieDesc`, `categorieCreateDate`) VALUES ('$new_value','$name', '$desc', current_timestamp())";   
        try {
            // code that may throw an exception
            $result = mysqli_query($conn, $sql);
        } catch (mysqli_sql_exception $e) {
            // handle the exception
            // echo "Pizza Name already Exists" . $e->getMessage();
            echo "<script>toastr.error('Error: " . $e->getMessage() . "')</script>";
        }


        // $result = mysqli_query($conn, $sql);
        // $catId = $conn->insert_id;
        if($result) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
                
                $newfilename = "card-".$catId.".jpg";

                $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/DBMSmini/img/';
                $uploadfile = $uploaddir . $newfilename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
                    echo "<script>alert('success');
                            window.location=document.referrer;
                        </script>";
                } else {
                    echo "<script>alert('failed to upload image');
                            window.location=document.referrer;
                        </script>";
                }

            }
            else{
                echo '<script>alert("Please select an image file to upload.");
                    </script>';
            }
        }
        else {
            echo "<script>alert('Category Name already Exists');
                    window.location=document.referrer;
               </script>";
        }
    }
    if(isset($_POST['removeCategory'])) {
        $catId = $_POST["catId"];
        $filename = $_SERVER['DOCUMENT_ROOT']."/DBMSmini/img/card-".$catId.".jpg";
        $sql = "DELETE FROM `categories` WHERE `categorieId`='$catId'";   
        $result = mysqli_query($conn, $sql);
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
    if(isset($_POST['updateCategory'])) {
        $catId = $_POST["catId"];
        $catName = $_POST["name"];
        $catDesc = $_POST["desc"];

        $sql = "UPDATE `categories` SET `categorieName`='$catName', `categorieDesc`='$catDesc' WHERE `categorieId`='$catId'";   
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
    if(isset($_POST['updateCatPhoto'])) {
        $catId = $_POST["catId"];
        $check = getimagesize($_FILES["catimage"]["tmp_name"]);
        if($check !== false) {
            $newName = 'card-'.$catId;
            $newfilename=$newName .".jpg";

            $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/DBMSmini/img/';
            $uploadfile = $uploaddir . $newfilename;

            if (move_uploaded_file($_FILES['catimage']['tmp_name'], $uploadfile)) {
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