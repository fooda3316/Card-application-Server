<?php


require_once '../ImageUploadApi/CardsFileHandler.php';
require_once '../includes/DbConnect.php';

if (isset($_POST['categoryId']) && strlen($_POST['imageName']) > 0 && strlen($_POST['price']) > 0 && $_FILES['cardPhoto']['error'] === UPLOAD_ERR_OK) {
    $response =array();
    $file = $_FILES['cardPhoto']['tmp_name'];
    $categoryId = $_POST['categoryId'];
    $imageName = $_POST['imageName'];
    $title = $_POST['title'];
    $price = $_POST['price'];
   
     $fileHandler=new CardsFileHandler();
    

    $result= $fileHandler->saveFileData($file,getFileExtension($_FILES['userPhoto']['name']),$imageName,$categoryId,$title,$price);


    if ($result==REQUEST_HAS_SENT) {
        $response['error'] = false;
        $response['message'] = 'Your card has been added successfully';
    }elseif ($result == REQUEST_HAS_NOT_SENT) {
        $response['error'] = true;
        $response['message'] = 'Some error occurred please try again !!!';
    }


} else {
    $response['error'] = true;
    $response['message'] = 'Required parameters are not available';
}
echo json_encode($response);
function getFileExtension($file)
{
    $path_parts = pathinfo($file);
    return $path_parts['extension'];
}
