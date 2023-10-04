<?php
$target_dir = "uploads/";
$csv_file = basename($_FILES["fileToUpload"]["name"]);
$return_page = $_POST["return_page"];
$target_file = $target_dir . $csv_file ;

$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
$msg = "";
session_start();

// Allow certain file formats
if ($fileType != "xls" && $fileType != "xlsx" ) {
//if ($fileType != "csv") {
    $msg = "Sorry, alleen XLS(X) files zijn toegestaan. ";
    //$msg = "Sorry, alleen CSV files zijn toegestaan. ";
    echo '<h1>'.$msg.'</h1>';
    exit();
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $msg =  "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        echo '<h1>'.$msg.'</h1>';
        $_SESSION["csv_file"] = $csv_file ;
        header('Location: ' . $return_page . '?mc=1');
        exit();
    } else {
        $msg =  "Sorry, there was an error uploading your file.";
        echo '<h1>'.$msg.'</h1>';
        header('Location: ' . $return_page . '?mc=2');
        $_SESSION["csv_file"] = "";
        exit();
    }
}
?>