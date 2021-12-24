<?php
class SellHistory{
    private $con;

    function __construct(){
        require_once dirname(__FILE__) . '/Balance.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }
    //Method to get sell history
    function getSellHistory($userID){
 $stmt = $this->con->prepare("SELECT id, mDate, card_name, card_number,card_value FROM sellhistory where userid=?");
             $stmt->bind_param("i", $userID);
       // echo "user id is ".$userID;
       $stmt->execute();
        $stmt->bind_result($id, $mDate, $card_name, $card_number,$card_value);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['mDate'] = $mDate;
            $temp['name'] = $card_name;
            $temp['serialNumber'] = $card_number;
            $temp['value'] = $card_value;
            array_push($users, $temp);
        }
        return $users;
    }
    //Method to get sell history
    function getWalletInfo($userID){
        $stmt = $this->con->prepare("SELECT id, mDate, balance FROM wallet where userid=?");
         $stmt->bind_param("i", $userID);
         $stmt->execute();
        $stmt->bind_result($id, $mDate, $balance);
        $users = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['mDate'] = $mDate;
            $temp['balance'] = $balance;
            array_push($users, $temp);
        }
        return $users;
    }
}