<?php
require_once '../ImageUploadApi/PagesFileHandler.php';
require_once '../includes/Constants.php';

if (isset($_POST['name'])&&isset($_POST['uri'])&& $_FILES['pageImage']['error'] === UPLOAD_ERR_OK) {
    $response =array();
    $file = $_FILES['pageImage']['tmp_name'];
    $name = $_POST['name'];
    $uri = $_POST['uri'];
//echo"image is ".$image." name is ".$name." uri is ".$uri;

    $mHandler= new PagesFileHandler();


    $result= $mHandler->savePage($file,getFileExtension($_FILES['pageImage']['name']),
        $name,$uri);


        if ($result==PAGE_ADDED) {
        $response['error'] = false;
        $response['message'] = 'تم إضافة صفحتك بنجاح';
    }elseif ($result == PAGE_NOT_ADDED) {
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
