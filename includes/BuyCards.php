<?php
class BuyCards{
    private $con;
    private $usersCon;
   // private $newCon;

    function __construct(){
        require_once dirname(__FILE__) . '/Balance.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
        $this->usersCon=$db->usersConnect();
    }
   //Method to get serial number
    function getSerialNumber($cardName,$subName,$branch){
            $stmt = $this->con->prepare("SELECT serialnumber FROM cardsinfo  WHERE name =? and sub_name =? and branch=? LIMIT 1");
        $stmt->bind_param('sss', $cardName,$subName,$branch);
        $stmt->execute();
            $stmt->store_result();
          //  $stmt->get_result();
            $stmt->bind_result($serialNumber);
            $stmt->fetch();
            $stmt->close();
            return $serialNumber;
    }


    //Method to get card price
    function getCardBrice($id){
        echo "valus inside method is  : id ".$id;
            $stmt = $this->con->prepare("SELECT price FROM sub_cards WHERE id=? ");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()){
            $stmt->store_result();
         //   $stmt->get_result();
            $stmt->bind_result($brice);
            $stmt->fetch();
            $stmt->close();
            return $brice;}
        return 999999999;
    }
    //Method to save foe records سجلات الخصم
    function saveFoes($name,$dat,$value){
        $stmt = $this->con->prepare("INSERT INTO foes (name,date,value) VALUES ( ?, ?,?)");
        $stmt->bind_param("ssi",$name , $dat, $value);
        $stmt->execute();
    }
//Method to get Balance
    function getBalance($userid){
        $stmt = $this->con->prepare("SELECT balance FROM users WHERE id=? ");
        $stmt->bind_param('i', $userid);
        if ($stmt->execute()){
            $stmt->store_result();
          //  $stmt->get_result();
            $stmt->bind_result($balance);
            $stmt->fetch();
            //$stmt->close();
            return $balance;
        }
        return 0;
    }
    //Method to show if card found
    function isCardFound($card,$subName,$branch){
                    echo " isCardFound name is ".$card." subName is ".$subName." branch is  ".$branch;

        $stmt = $this->con->prepare("SELECT * FROM cardsinfo WHERE name = ? and sub_name=? and branch=?");
        $stmt->bind_param("sss", $card,$subName,$branch);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    //Method to buy card
    function buyCard($cardName,$subName,$branch,$userID,$date,$cardID){
        $cardPrice= $this->getCardBrice($cardID);
        if ($this->isCardFound($cardName,$subName,$branch)) {
            $cardSerialNumber = $this->getSerialNumber($cardName,$subName,$branch);
            $mBalance = new Balance();
            $userBalance = $this->getBalance($userID);

                if ($userBalance >= $cardPrice) {

                    $netBalance = $userBalance - $cardPrice;
                    $user_name = $mBalance->getUserName($userID);
                    $this->saveFoes($user_name, $date, $cardPrice);
                    $this->updateBalance($userID, $netBalance);
                  //   echo "/n user data is ".$userID." ".$cardName." ".$cardSerialNumber." ".$cardPrice." ".$date;
                  $cardFinalName=$subName." ".$branch." ".$cardName;
                    $stmt = $this->con->prepare("INSERT INTO sellhistory (mDate,card_name,card_number,card_value,userid) VALUES (?, ?, ?,?,?)");
                    $stmt->bind_param("sssii", $date, $cardFinalName, $cardSerialNumber, $cardPrice,$userID);
                    if ($stmt->execute()) {
                        echo"first executed";
                        $stmt = $this->con->prepare("DELETE FROM cardsinfo  WHERE serialnumber = ?");
                        $stmt->bind_param("s", $cardSerialNumber);
                        if ($stmt->execute()) {

                            return PURCHASE_COMPLETED;
                        }
                        return PURCHASE_FAILED;
                    }

                    return PURCHASE_FAILED;
                }
                return LESS_BALANCE;
        }
        else return CARD_NOT_FOUND;
    }
    //Method to update Balance of user
    function updateBalance($id,$balance){
        $mBalance=new Balance();
        if($mBalance->getBalance($id)!=-1){
        $userBalance=$mBalance->getBalance($id);
        $net_balance=$balance-$userBalance;
        $stmt = $this->con->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $stmt->bind_param("ii", $balance, $id);
        if ($stmt->execute())
            return true;
        }
        return false;
    }
}