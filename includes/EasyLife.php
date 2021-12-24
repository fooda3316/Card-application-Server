<?php
class EasyLife{
    private $con;

    function __construct(){
        require_once dirname(__FILE__) . '/Balance.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
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