<?php
class Cards{
private $con;
private $newCon;
function __construct(){
require_once dirname(__FILE__) . '/DbConnect.php';
$db = new DbConnect();
$this->con = $db->connect();
}

//Method to update card price
    function updateCardPrice($image,$ime,$newBblance){
       
        if($this->isAdminImePresent($ime)){
   $stmt = $this->con->prepare("UPDATE sub_cards SET price = ? WHERE image = ?");
        $stmt->bind_param("is",$newBblance,$image);
        if ($stmt->execute()){
           
            return true;
        }

        return false;
            
        }
        return false;
    }
     //Method to determine if Ime Present
    function isAdminImePresent($ime){
         echo"\n ime in method is ".$ime;
          $stmt = $this->con->prepare("SELECT * FROM imes WHERE ime = ? ");
        $stmt->bind_param("s", $ime);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    //Method to add IME
    function addIme($ime,$name){
            $stmt = $this->con->prepare("INSERT INTO imes (ime,name) VALUES (?,?)");
            $stmt->bind_param("ss",$ime,$name);
            if ($stmt->execute()) {
                if($this->deleteIme($ime)){
                   return CARD_ADDED; 
                }
              return CARD_NOT_ADDED;  
            }


            return CARD_NOT_ADDED;



    }
    function deleteIme($ime){
      $stmt = $this->con->prepare("DELETE FROM imesrq  WHERE ime = ?"); 
      $stmt->bind_param("s", $ime);
      if ($stmt->execute()) {
          return true;
      }
      return false;
    }
    //Method to add Request IME
    function addAdminIme($ime,$name){
            $stmt = $this->con->prepare("INSERT INTO imesrq (ime,name) VALUES (?,?)");
            $stmt->bind_param("ss",$ime,$name);
            if ($stmt->execute()) {
                return CARD_ADDED;
            }


            return CARD_NOT_ADDED;



    }
    //Method to add Admin Cards
    function addAdminCards($name,$subName,$branch, $serialnumber){
            $stmt = $this->con->prepare("INSERT INTO cardsinfo (name, sub_name,branch,serialnumber) VALUES (?, ?, ?,?)");
            $stmt->bind_param("ssss",$name ,$subName,$branch, $serialnumber);
            if ($stmt->execute()) {
                return CARD_ADDED;
            }


            return CARD_NOT_ADDED;



    }
    //Method to request Card
    function requestCard($name,$subName,$branch, $date){
            $stmt = $this->con->prepare("INSERT INTO uncards (name, sub_name,branch , date) VALUES (?, ?, ?,?)");
            $stmt->bind_param("ssss",$name ,$subName,$branch,  $date);
            if ($stmt->execute()) {
                return CARD_ADDED;
            }


            return CARD_NOT_ADDED;



    }
    //Method to get all Requested Cards
    function getRequestedCards(){
        $stmt = $this->con->prepare("SELECT id, name,sub_name,branch,date  FROM uncards");
        $stmt->execute();
        $stmt->bind_result($id,$name,$subName,$branch,$date);
        $requests = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['title'] = $name;
            $temp['subName'] = $subName;
            $temp['branch'] = $branch;
            $temp['date'] = $date;

            array_push($requests, $temp);

        }
        return $requests;
    }
     //Method to delete Request
    function deleteRequest($id){

        $stmt = $this->con->prepare("DELETE FROM uncards  WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute())
            return true;
        return false;
    }
}