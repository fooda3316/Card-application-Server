<?php
class Admins{
private $con;
/**
* @var mysqli|null
*/
private $newCon;
/**
* @var mysqli|null
*/
// private $newCon;

function __construct(){
require_once dirname(__FILE__) . '/Balance.php';
require_once dirname(__FILE__) . '/DbConnect.php';
$db = new DbConnect();
$this->con = $db->connect();
}
//Method to get all Admins
function getAllAdmins(){
$stmt = $this->con->prepare("SELECT id, name,balance,image ,date FROM admins");
$stmt->execute();
$stmt->bind_result($id, $name, $balance, $image,$date);
$users = array();
while($stmt->fetch()){
$temp = array();
$temp['id'] = $id;
$temp['name'] = $name;
$temp['balance'] = $balance;

$temp['image'] = $image;
$temp['date'] = $date;
array_push($users, $temp);

}
return $users;
   }
    //Method to delete admin
    function deleteAdmin($adminId){

        $stmt = $this->con->prepare("DELETE FROM admins  WHERE id = ?");
        $stmt->bind_param("i", $adminId);
        if ($stmt->execute())
            return true;
        return false;
    }
  //  Method to delete ime
    function deleteIme($ime){

        $stmt = $this->con->prepare("DELETE FROM imes  WHERE ime = ?");
        $stmt->bind_param("s", $ime);
        if ($stmt->execute())
            return true;
        return false;
    }
    //  Method to delete page
    function deletePage($name){

        $stmt = $this->con->prepare("DELETE FROM main_pager  WHERE name = ?");
        $stmt->bind_param("s", $name);
        if ($stmt->execute())
            return true;
        return false;
    }
    //Method to get all users 
    function getAllUsers(){
        $stmt = $this->con->prepare("SELECT  name, email, password,image,phone,balance FROM users");
        $stmt->execute();
        $stmt->bind_result( $name, $email, $password,$image,$phone,$balance);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
           // $temp['id'] = $id;
            $temp['name'] = $name;
            $temp['email'] = $email;
            $temp['password'] = $password;
            $temp['image'] = $image;
            $temp['phone'] = $phone;
            $temp['balance'] = $balance;
            array_push($users, $temp);
        }
        return $users;
    }
    //Method to get all cards
    function getAllCards(){
        $stmt = $this->con->prepare("SELECT id, title, price,image,sub_name,branch FROM sub_cards");
        $stmt->execute();
       // $final_title=$title." $";
        $stmt->bind_result($id, $title , $price,$image,$subName,$branch);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['categoryId'] = $categoryId;
            $temp['title'] = $title;
            $temp['price'] = $price;
            $temp['image'] = $image;
            $temp['subName'] = $subName;
            $temp['branch'] = $branch;


            array_push($users, $temp);
        }
        return $users;
    }
    //Method to get all Admin cards
    function getAllAdminCards(){
        $stmt = $this->con->prepare("SELECT id, name,serialnumber, value  FROM cardsinfo");
        $stmt->execute();
        $stmt->bind_result($id, $name, $serialnumber, $value);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['name'] = $name;
            $temp['serialnumber'] = $serialnumber;
            $temp['value'] = $value;

            array_push($users, $temp);
        }
        return $users;
    }
}