<?php
require_once '../ImageUploadApi/FileHandler.php';
require_once '../includes/Balance.php';

if (isset($_POST['userId']) && strlen($_POST['imageName']) > 0 && $_FILES['userPhoto']['error'] === UPLOAD_ERR_OK) {
    $response =array();
    $balance=new Balance();
    $file = $_FILES['userPhoto']['tmp_name'];
    $userId = $_POST['userId'];
        $operation = $_POST['operation'];

    $imageName = $_POST['imageName'];
    $fileHandler=new FileHandler();

    $result= $fileHandler->saveFileData($file,getFileExtension($_FILES['userPhoto']['name']),$imageName,$userId,$operation);


    if ($result==REQUEST_HAS_SENT) {
        $name =$balance->getUserName($userId);
         $response['error'] = false;
        $response['message'] = 'Dea '.$name.' your Charge request has been sent';
     }elseif ($result == REQUEST_HAS_NOT_SENT) {
        $response['error'] = true;
        $response['message'] = 'Some error occurred please try again !!!';
     }
     elseif ($result==REQUEST_HAS_ALREADY_SAVED) {
         $response['error'] = true;
         $response['message'] = 'Sorry you can not send another request before finishing your first one  !!!';
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
