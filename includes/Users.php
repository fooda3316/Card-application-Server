<?php
class Users{
    private $con;

    /**
     * @var mysqli|null
     */

    function __construct()
    {
        require_once dirname(__FILE__) . '/Balance.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }
    //Method to get Particular user
    function getParticularUser($userID){
        $stmt = $this->con->prepare("SELECT  name, email,image,phone,balance FROM users WHERE id=?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result( $name,$email,$image,$phone, $balance);
        $users = array();
        while($stmt->fetch()){
            $temp = array();

            $temp['name'] = $name;
            $temp['email'] = $email;
            $temp['image'] = $image;
            $temp['phone'] = $phone;
            $temp['balance'] = $balance;

            array_push($users, $temp);
        }
        return $users;
    }
    //Method to get Particular card
    function getParticularCards($categoryId){
        $stmt = $this->con->prepare("SELECT  title, price,image FROM cards WHERE categoryId=?");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $stmt->bind_result( $title,$price,$image);
        $cards= array();
        while($stmt->fetch()){
            $temp = array();

            $temp['title'] = $title;
            $temp['price'] = $price;
            $temp['image'] = $image;
            

            array_push($cards, $temp);
        }
        return $cards;
    }
    //Method to get Particular sub card
    function getSubCards($sub_name,$branch){
       
        $stmt = $this->con->prepare("SELECT id, title, price,image,sub_name,branch FROM sub_cards WHERE sub_name=? and branch=?");
        $stmt->bind_param("ss", $sub_name,$branch);
       // echo "name ".$sub_name." branch ".$branch;
       if($stmt->execute()){
         //   echo "execute";  
       } 
        $stmt->bind_result( $id,$title,$price,$image,$subName,$branch);
        $cards= array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['title'] = $title;
            $temp['price'] = $price;
            $temp['image'] = $image;
            $temp['subName'] = $subName;
            $temp['branch'] = $branch;

            

            array_push($cards, $temp);
        }
        return $cards;

    }
}