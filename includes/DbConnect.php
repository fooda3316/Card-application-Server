<?php

class DbConnect
{
    //Variable to store database link
    private $con;
    private $user_con;

    //Class constructor
    function __construct(){

    }

    //This method will connect to the database
    function connect(){
        //Including the constants.php file to get the database constants
        include_once dirname(__FILE__) . '/Constants.php';

        //connecting to mysql database
        $this->con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        //Checking if any error occured while connecting
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            return null;
        }

        //finally returning the connection link
        return $this->con;
    }
    function usersConnect(){
        //Including the constants.php file to get the database constants
        include_once dirname(__FILE__) . '/Constants.php';

        //connecting to mysql database
         $this->user_con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, USERS_DB_NAME);
                // $this->con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);


        //Checking if any error occured while connecting
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
           // echo"conection not Success";
            return null;
        }

        //finally returning the connection link
       // echo"conection Success";
        return $this->user_con;
    }

}