<?php
class Balance{
private $con;
private $newCon;
function __construct(){
require_once dirname(__FILE__) . '/DbConnect.php';
$db = new DbConnect();
$this->con = $db->connect();
}
//Method to get Balance
    function getBalance($id){
            $stmt = $this->con->prepare("SELECT balance FROM users WHERE id=? ");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()){
        $stmt->store_result();
       // $stmt->get_result();
        $stmt->bind_result($balance);
        $stmt->fetch();
        $stmt->close();
        return $balance;
        }
        return -1;
    }
    //Method to get user name
    function getUserName($id){
        $stmt = $this->con->prepare("SELECT name FROM users WHERE id=? ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
       // $stmt->get_result();
        $stmt->bind_result($name);
        $stmt->fetch();
        $stmt->close();
        return $name;
    }
    //method to remove request from request history
    function removeRequest($userID){
        $stmt = $this->con->prepare("DELETE FROM unfinishedrq  WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
    }
    //Method to  update Balance operation of user
    function saveUBOperation($mDate,$image,$balance,$userid){
        $db = new DbConnect();
        
        $stmt = $this->con->prepare("INSERT INTO wallet (mDate,image,balance,userid) VALUES (?, ?, ?,?)");
        $stmt->bind_param("ssii",$mDate , $image, $balance,$userid);
        $stmt->execute();
    }
    //Method to get confirm image
    function getConfirmImage($requestID){
        $stmt = $this->con->prepare("SELECT image FROM unfinishedrq WHERE id=? ");
        $stmt->bind_param('i', $requestID);
        $stmt->execute();
        $stmt->store_result();
       // $stmt->get_result();
        $stmt->bind_result($confirmImage);
        $stmt->fetch();
        $stmt->close();
        return $confirmImage;
    }
    //Method to save update operation
    function saveUpdateOperation($name,$balance,$image,$dat){
        $stmt = $this->con->prepare("INSERT INTO admins (name	,balance,image,date) VALUES ( ?, ?,?,?)");
        $stmt->bind_param("siss",$name ,$balance , $image,$dat);
        $stmt->execute();

    }
    //Method to update Balance of user
    function updateBalance($balance,$id,$requestID,$adminName,$ime,$date){
        if($this->isImePresent($ime)){
      
        $confirmImage=$this->getConfirmImage($requestID);
        $net_balance=$balance+$this->getBalance($id);
        $stmt = $this->con->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $stmt->bind_param("ii", $net_balance, $id);
        if ($stmt->execute()){
            echo "UPDATE users done";
            $this->saveUpdateOperation($adminName,$balance,$confirmImage,$date);
            $this->removeRequest($requestID);
            $this->saveUBOperation($date,$confirmImage,$balance,$id);
            return true;
        }

        return false;
            
        }
        return false;
    }
    //Method to determine if Ime Present
    function isImePresent($ime){
          $stmt = $this->con->prepare("SELECT * FROM imes WHERE ime = ? ");
        $stmt->bind_param("s", $ime);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    //Method to show if is  request has sent
    function isRequestSent($userID){
        $stmt = $this->con->prepare("SELECT * FROM unfinishedrq WHERE userId = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    //Method to send user charge wallet request
    function saveChargeRequest($userID,  $image){

        $name = $this->getUserName($userID);

        $stmt = $this->con->prepare("INSERT INTO unfinishedrq (userId,name,image) VALUES (?, ?, ?)");
        if ($this->isRequestSent($userID)) {
           return REQUEST_HAS_SAVED;
        } else {
            $stmt->bind_param("iss", $userID, $name, $image);
            if ($stmt->execute()) {
                return REQUEST_HAS_SENT;
            }

            return REQUEST_HAS_NOT_SENT;

        }
    }
    //Method to send user charge wallet request image and data
    function saveChargeRequestData($userID,   $image,$extension,$file){
        //$extension= getFileExtension($file);
        $imageName = $image. '.' . $extension;
        $name = $this->getUserName($userID);
        $fileDest = dirname(__FILE__) . UPLOAD_IMPROVEMENT_PATH . $imageName;
        $stmt = $this->con->prepare("INSERT INTO unfinishedrq (userId,name,image) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userID, $name, $imageName);
        if ($this->isRequestSent($userID)) {
            return REQUEST_HAS_SAVED;
        } else {
            if ( move_uploaded_file($file, $fileDest)){
                    return REQUEST_HAS_SENT;
                }
                return REQUEST_HAS_NOT_SENT;
            }


          //  return REQUEST_HAS_NOT_SENT;

        }

    //Method to get unfinished requests
    function getUnfinishedRequests(){
        $stmt = $this->con->prepare("SELECT id, userId, name,image,operation FROM unfinishedrq");
        $stmt->execute();
        $stmt->bind_result($id, $userId, $name,$image,$operation);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['userId'] = $userId;
            $temp['name'] = $name;
            $temp['image'] = $image;
            $temp['operation'] = $operation;

            array_push($users, $temp);
        }
        return $users;
    }
    //Method to get name and balance
    function getNameBalance($userID){
        $stmt = $this->con->prepare("SELECT id, categoryId,title, price,image FROM cards");
        $stmt->execute();
        $stmt->bind_result($id, $categoryId, $title, $price,$image);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['categoryId'] = $categoryId;
            $temp['title'] = $title;
            $temp['price'] = $price;
            $temp['image'] = $image;

            array_push($users, $temp);
        }
        return $users;
    }
    function getFileExtension($file)
    {
        $path_parts = pathinfo($file);
        return $path_parts['extension'];
    }
}