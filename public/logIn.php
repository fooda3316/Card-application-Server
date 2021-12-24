<?php
require_once '../includes/LogInHelper.php';
require_once '../includes/Balance.php';
require_once '../includes/DbOperation.php';

if (isset($_POST['name'])&&isset($_POST['last'])&&isset($_POST['email'])
    &&isset($_POST['password'])  &&isset($_POST['image']) > 0
    &&isset($_POST['phone'])&& $_FILES['userPhoto']['error'] === UPLOAD_ERR_OK) {
    $response =array();
    $balance=new Balance();
    $file = $_FILES['userPhoto']['tmp_name'];
    $name = $_POST['name'];
    $last = $_POST['last'];
    $email = $_POST['email'];
    $imageName = $_POST['image'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];


    $logInHelper= new LogInHelper();


    $result= $logInHelper->registerUser($file,getFileExtension($_FILES['userPhoto']['name']),
        $name,$last,$email,$password,$imageName,$phone);


    if ($result==USER_CREATED) {
        $mLogInHelper= new LogInHelper();
        $response['error'] = false;
        $response['message'] = 'User created successfully...';
        $db=new DbOperation();
        $response['user'] = $logInHelper->getUserByEmail($email);

    }elseif ($result == USER_CREATION_FAILED) {
        $response['error'] = true;
        $response['message'] = 'Some error occurred please try again !!!';
    }
    elseif ($result==USER_EXIST) {
        $response['error'] = true;
        $response['message'] = 'Sorry this user is already exist !!!';
    }


}
   else { $response['error'] = true;
   $response['message'] = 'Required parameters are not available';}


    echo json_encode($response);

function getFileExtension($file)
{
    $path_parts = pathinfo($file);
    return $path_parts['extension'];
}
