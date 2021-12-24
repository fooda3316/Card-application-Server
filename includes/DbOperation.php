<?php
class DbOperation{
    private $con;
    function __construct(){
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }
     //Method get admin ime
    function getAdminIme($ime){
    
        $stmt = $this->con->prepare("SELECT id FROM imes WHERE ime = ? ");
        $stmt->bind_param("s", $ime);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    //Method for user login
    function userLogin($email, $pass){
        $password = md5($pass);
        echo " md5 pass in db method is".$password;
        $stmt = $this->con->prepare("SELECT id FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $pass);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    //Method to send a message to another user
    function sendMessage($from, $to, $title, $message){
        $stmt = $this->con->prepare("INSERT INTO messages (from_users_id, to_users_id, title, message) VALUES (?, ?, ?, ?);");
        $stmt->bind_param("iiss", $from, $to, $title, $message);
        if ($stmt->execute())
            return true;
        return false;
    }

    //Method to update password of user
    function updatePassword($id,  $pass){ 
        echo " pass in db method is".$pass;
        $password = md5($pass);
                echo " md5 pass in db method is".$password;
        $stmt = $this->con->prepare("UPDATE users SET  password = ? WHERE id = ?");
        $stmt->bind_param("si",  $pass, $id);
        if ($stmt->execute())
            return true;
        return false;
    }
    //Method to delete user
    function deleteUser($usrId){

        $stmt = $this->con->prepare("DELETE FROM users  WHERE id = ?");
        $stmt->bind_param("i", $usrId);
        if ($stmt->execute())
            return true;
        return false;
    }


    //Method to get ID
function getId($email){
    $stmt = $this->con->prepare("SELECT id FROM Users WHERE email=? ");
    $stmt->bind_param('s', $email);
    $stmt->execute();
  $stmt->store_result();
      $stmt->get_result();
    $stmt->bind_result($id);
    $stmt->fetch();

    $stmt->close();
    return $id;
}
    //Method to get  a particular project
    function getProject($cate){
        $stmt = $this->con->prepare("SELECT id, name, price,image FROM projects WHERE projects.category = ? ORDER BY projects.name DESC;");
        $stmt->bind_param("s", $cate);
        $stmt->execute();
        $stmt->bind_result($id,$name,$price,$image);

        $messages = array();

        while ($stmt->fetch()) {
            $temp = array();

            $temp['id'] = $id;
            $temp['name'] = $name;
            $temp['price'] = $price;
            $temp['image'] = $image;


            array_push($messages, $temp);
        }

        return $messages;
    }
    //Method to get user by email
    function getUserByEmail($email){
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
    //Method to get Particular Category
    function getParticularCategory($category){
        $stmt = $this->con->prepare("SELECT id, name, price,image FROM projects WHERE category=?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $stmt->bind_result($id, $name, $price,$image);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['name'] = $name;
            $temp['price'] = $price;
           // $temp['category'] = $category;
            $temp['image'] = $image;
            array_push($users, $temp);
        }
        return $users;
    }
    //Method to get all cards
    function getAllCards(){
        $stmt = $this->con->prepare("SELECT id, categoryId,title,price,image  FROM cards");
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
    //Method to get all categories
    function getCategories(){
        $stmt = $this->con->prepare("SELECT id,title,image  FROM categories");
        $stmt->execute();
        $stmt->bind_result($id, $title,$image);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['title'] = $title;
            $temp['image'] = $image;
            array_push($users, $temp);

        }
        return $users;
    }
    //Method to get all pages
    function getPages(){
        $stmt = $this->con->prepare("SELECT image,name,uri  FROM main_pager");
        $stmt->execute();
        $stmt->bind_result($image,$name,$uri);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['image'] = $image;
            $temp['name'] = $name;
            $temp['uri'] = $uri;
            array_push($users, $temp);

        }
        return $users;
    }
     //Method to get all Imes 
    function getImes(){
        $stmt = $this->con->prepare("SELECT ime,name  FROM imes");
        $stmt->execute();
        $stmt->bind_result($ime,$name);
        $imes = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['ime'] = $ime;
            $temp['name'] = $name;
            
            array_push($imes, $temp);

        }
        return $imes;
    }
     //Method to get All Ime Requests
    function getAllImeRequests(){
        $stmt = $this->con->prepare("SELECT ime,name  FROM imesrq");
        $stmt->execute();
        $stmt->bind_result($ime,$name);
        $imes = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['ime'] = $ime;
            $temp['name'] = $name;
            
            array_push($imes, $temp);

        }
        return $imes;
    }
    //Method to get all Banks
    function getBanks(){
        $stmt = $this->con->prepare("SELECT bankName,name,account,image  FROM banks");
        $stmt->execute();
        $stmt->bind_result($bankName,$name,$account,$image);
        $banks= array();
        while($stmt->fetch()){
            $temp = array();
            $temp['bankName'] = $bankName;
              $temp['name'] = $name;
            $temp['account'] = $account;
            $temp['image'] = $image;

            
            array_push($banks, $temp);

        }
        return $banks;
    }
//Method to create a new user 
    function createUser($name, $email, $image){
        if (!$this->isUserExist($email)) {
            $stmt = $this->con->prepare("INSERT INTO users (name, email,  image,balance) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sss",$name , $email,  $image);
            if ($stmt->execute()) {

                return USER_CREATED;
            }


            return USER_CREATION_FAILED;


        }
        return USER_EXIST;
    }
    //Method to check if email already exist
    function isUserExist($email){
        $stmt = $this->con->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
   
//Method to get getBranches
    function getBranches($name){
       
        $stmt = $this->con->prepare("SELECT  image ,branch FROM branches WHERE name=?");
        $stmt->bind_param("s", $name);
     
       if($stmt->execute()){
         //   echo "execute";  
       } 
        $stmt->bind_result( $image,$branch);
        $branches= array();
        while($stmt->fetch()){
            $temp = array();

            $temp['image'] = $image;
            $temp['branch'] = $branch;

            

            array_push($branches, $temp);
        }
        return $branches;
    }

}