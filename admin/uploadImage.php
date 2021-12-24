<?php
require_once '../ImageUploadApi/FileHandler.php';
if (isset($_POST['desc']) && strlen($_POST['desc']) > 0 && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $response =array();

    $upload = new FileHandler();
    $file = $_FILES['image']['tmp_name'];
    $desc = $_POST['desc'];
    $type = $_POST['type'];


    if ($upload->saveFile($file, getFileExtension($_FILES['image']['name']), $desc,$type)) {

        $response['error'] = false;
        $response['message'] = 'File Uploaded Successfullly';
    }

} else {
    $response['error'] = true;
    $response['message'] = 'Required parameters are not available';
}
echo json_encode($response);
function getFileExtension($file){
    $path_parts = pathinfo($file);
    return $path_parts['extension'];
}
