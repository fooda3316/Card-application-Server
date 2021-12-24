<?php

/**
 * Created by PhpStorm.
 * User: Belal
 * Date: 10/5/2017
 * Time: 11:30 AM
 */
class LogInHelper{

    private $con;
    /**
     * @var mysqli|null
     */
    private $newCon;

    public function __construct(){
     //   require_once dirname(__FILE__) . '../includes/DbConnect.php';
        require_once '../includes/DbConnect.php';
        require_once '../includes/Constants.php';
        require_once '../includes/Balance.php';

        $db=new  DbConnect();
        $this->con=$db->connect();

    }


    public function saveFile($file, $extension, $desc,$type){
        //echo "saveFile ".$desc." ".$extension;
       // $name = round(microtime(true) * 1000) . '.' . $extension;
        $name = $desc. '.' . $extension;

    if ($type=="auth")  {$fileDest = dirname(__FILE__) . UPLOAD_AUT_PATH . $name;}
    else {
        $fileDest = dirname(__FILE__) . UPLOAD_IMPROVEMENT_PATH . $name;
    }
      if ( move_uploaded_file($file, $fileDest)){
          return true;
      }

        return false;
    }
    public function saveFileData($file, $extension, $imageName,$userID){
     $balance=new Balance();
        $image = $imageName. '.' . $extension;
        $userName=$balance->getUserName($userID);

            $fileDest = dirname(__FILE__) . UPLOAD_IMPROVEMENT_PATH . $image;
        $stmt = $this->con->prepare("INSERT INTO unfinishedrq (userId,name,image) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userID, $userName, $image);
        if ($balance->isRequestSent($userID)) {
            return REQUEST_HAS_SAVED;
        } else {
            if ( move_uploaded_file($file, $fileDest)&&$stmt->execute()){
                return REQUEST_HAS_SENT;
            }
            return REQUEST_HAS_NOT_SENT;
        }

      /*  if ( move_uploaded_file($file, $fileDest)){
            return REQUEST_HAS_SENT;
        }

        return REQUEST_HAS_NOT_SENT;*/
    }

    public function registerUser($file, $extension, $name,$last,$email,$password,$image,$phone){
        $imageName  = $image. '.' . $extension;
        $fileDest = dirname(__FILE__) . UPLOAD_AUT_PATH . $imageName;
        $password = md5($password);
        $full_name=$name." ".$last;
        $uploadImage=move_uploaded_file($file, $fileDest);
        if (!$this->isUserExist($email)) {
        $stmt = $this->con->prepare("INSERT INTO users (name, email, password, image,phone,balance) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("sssss",$full_name , $email, $password, $imageName,$phone);
        if ($uploadImage&&$stmt->execute()) {
            $id=  $this->getId($email);
            if ($this->creatingManyTables($id)) {
                return USER_CREATED;
            }
            return USER_CREATION_FAILED;
        }
        return USER_CREATION_FAILED;
    }
return USER_EXIST;
        }
    //Method to get ID
    function getId($email){
        $stmt = $this->con->prepare("SELECT id FROM users WHERE email=? ");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->get_result();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();
        return $id;
    }
    //Method to get user by email
    function getUserByEmail($email){
      //  echo "registered user email is:".$email;
        $stmt = $this->con->prepare("SELECT id, name, email,image , phone FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $name, $email,$image,$phone);
        $stmt->fetch();
        $user = array();
        $user['id'] = $id;
        $user['name'] = $name;
        $user['email'] = $email;
        $user['image'] = $image;
        $user['phone'] = $phone;

        return $user;
    }
    //Method to check if email already exist
    function isUserExist($email){
        $stmt = $this->con->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    //Method for creating many tables
    function creatingManyTables($userID){
        $db = new DbConnect();
        $this->newCon = $db->usersConnect();
        $sell_stmt = $this->newCon->prepare("CREATE TABLE sell_history_".$userID." (
   id int PRIMARY KEY AUTO_INCREMENT not null ,
   mDate text  not null,
   card_name text  not null,
   card_number text  not null,
       card_value int  not null
)");
        $wallet_stmt = $this->newCon->prepare("CREATE TABLE wallet_".$userID."  (
   id int PRIMARY KEY AUTO_INCREMENT not null ,mDate text  not null,image text  not null,balance int  not null
)");
        if ($sell_stmt->execute()&&$wallet_stmt->execute()){
            return true;
        }
        return false;
    }
}
