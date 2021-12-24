<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';
require_once '../includes/DbOperation.php';
require_once '../includes/DbConnect.php';
require_once '../includes/Balance.php';
require_once '../includes/BuyCards.php';
require_once '../includes/SellHistory.php';
require_once '../includes/Users.php';
require_once '../includes/Cards.php';
require_once '../includes/Admins.php';


//$app = new \Slim\App;
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);
//Method get AdminI me
$app->post('/getAdminIme',function (Request $request, Response $response) {
    if (isTheseParametersAvailable(array('ime'))) {
        $requestData = $request->getParsedBody();
        $ime = $requestData['ime']?? false;
      
        $db = new DbOperation();
        $responseData = array();
        if ($db->getAdminIme($ime )) {
            $responseData['error'] = false;
            $responseData['ime'] = true;

            $responseData['user'] = $db->getUserByEmail($email);
        } else {
            $responseData['error'] = true;
           $responseData['ime'] = false;

      
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
//Method to add ime
$app->post('/addIme', function (Request $request, Response $response) {
      
    if (isTheseParametersAvailable(array('ime','name'))) {
        //  $requestData = $request->getQueryParams();
        $requestData = $request->getParsedBody();
        $ime=$requestData['ime'];
         $name=$requestData['name'];
        $db = new Cards();
        $responseData = array();

        $result = $db->addIme($ime,$name);

        if ($result == CARD_ADDED) {
            $responseData['error'] = false;
            $responseData['message'] = 'The Ime has been added successfully';
        } else  {
            $responseData['error'] = true;
            $responseData['message'] = 'Some error occurred The card has not been added successfully';
        }

        $response->getBody()->write(json_encode($responseData));

    }
}); 
//Method to add ime
$app->post('/addAdminIme', function (Request $request, Response $response) {
      
    if (isTheseParametersAvailable(array('ime','name'))) {
        //  $requestData = $request->getQueryParams();
        $requestData = $request->getParsedBody();
        $ime=$requestData['ime'];
         $name=$requestData['name'];
        $db = new Cards();
        $responseData = array();

        $result = $db->addAdminIme($ime,$name);

        if ($result == CARD_ADDED) {
            $responseData['error'] = false;
            $responseData['message'] = 'The Ime has been added successfully';
        } else  {
            $responseData['error'] = true;
            $responseData['message'] = 'Some error occurred The card has not been added successfully';
        }

        $response->getBody()->write(json_encode($responseData));

    }
});
//Get verification number
$app->get('/getVerificationNumber',function (Request $request, Response $response) {
    $responseData['pass'] = '1a10k';
    $response->getBody()->write(json_encode($responseData));


});
//Method to get all requested Cards
$app->get('/requestedCards', function (Request $request, Response $response) {
    $db =   new Cards();
    $requests = $db->getRequestedCards();
    $response->getBody()->write(json_encode(array("requests" => $requests)));
});
//Get verification number
$app->get('/getManagerKey',function (Request $request, Response $response) {
    $responseData['pass'] = '7tn309fdmx';
    $response->getBody()->write(json_encode($responseData));


});
$app->get('/managerKey/{ime}', function (Request $request, Response $response) {
    
    $ime = $request->getAttribute('ime');
          if($ime=='351564342113925')   {
              $responseData['pass'] = '7tn309fdmx';
              $responseData['message'] = 'allowed';}
          else   if($ime=='353975072426485')   {
              $responseData['pass'] = '7tn309fdmx';
              $responseData['message'] = 'allowed';}
               else   if($ime=='358549085724809')   {
              $responseData['pass'] = '7tn309fdmx';
              $responseData['message'] = 'allowed';}
        else{
                $responseData['message'] = 'not allowed';  
              }
    $response->getBody()->write(json_encode($responseData));
});
// //Get facebook 
// $app->get('/facebook',function (Request $request, Response $response) {
//     $responseData['facebook'] = 'https://www.facebook.com/Suda-Card-866291140395378/';
//     $response->getBody()->write(json_encode($responseData));


// });
// //Get twitter 
// $app->get('/twitter',function (Request $request, Response $response) {
//     $responseData['twitter'] = 'https://www.facebook.com/Suda-Card-866291140395378/';
//     $response->getBody()->write(json_encode($responseData));


// });
// //Get youtube 
// $app->get('/youtube',function (Request $request, Response $response) {
//     $responseData['youtube'] = 'https://www.facebook.com/Suda-Card-866291140395378/';
//     $response->getBody()->write(json_encode($responseData));


// });
// //Get inst 
// $app->get('/inst',function (Request $request, Response $response) {
//     $responseData['inst'] = 'https://www.facebook.com/Suda-Card-866291140395378/';
//     $response->getBody()->write(json_encode($responseData));


// });
// //Get whatsApp 
// $app->get('/whatsApp',function (Request $request, Response $response) {
//     $responseData['whatsApp'] = 'https://www.facebook.com/Suda-Card-866291140395378/';
//     $response->getBody()->write(json_encode($responseData));


// });
//getting pages
$app->get('/pages', function (Request $request, Response $response) {
    $db = new DbOperation();
    $project = $db->getPages();
    $response->getBody()->write(json_encode(array("pages" => $project)));
});
//getting imes 
$app->get('/imes', function (Request $request, Response $response) {
    $db = new DbOperation();
    $ime = $db->getImes();
    $response->getBody()->write(json_encode(array("imes" => $ime)));
});
//getting imes getAllImeRequests
$app->get('/getAllImeRequests', function (Request $request, Response $response) {
    $db = new DbOperation();
    $ime = $db->getAllImeRequests();
    $response->getBody()->write(json_encode(array("imes" => $ime)));
});
//Delete page
$app->post('/deletePage', function (Request $request, Response $response){
    $db=new Admins();
    if (isTheseParametersAvailable(array('name'))) {
        $requestData = $request->getParsedBody();
        //  $requestData = $request->getQueryParams();
        $name=$requestData['name'];
        //  $responseData = array();
        if ($db->deletePage($name)){
            $responseData['error'] = false;
            $responseData['message'] = 'page has deleted successfully';
        }
        else{
            $responseData['error'] = true;
            $responseData['message'] = 'page has not deleted successfully';
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
//Delete Request
$app->post('/deleteRequest', function (Request $request, Response $response){
    $db=new Cards();
    if (isTheseParametersAvailable(array('id'))) {
        $requestData = $request->getParsedBody();
        $id=$requestData['id'];
        //  $responseData = array();
        if ($db->deleteRequest($id)){
            $responseData['error'] = false;
            $responseData['message'] = 'Request has deleted successfully';
        }
        else{
            $responseData['error'] = true;
            $responseData['message'] = 'Request has not deleted successfully';
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
//Delete Ime
$app->post('/deleteIme', function (Request $request, Response $response){
    $db=new Admins();
    if (isTheseParametersAvailable(array('ime'))) {
        $requestData = $request->getParsedBody();
        //  $requestData = $request->getQueryParams();
        $ime=$requestData['ime'];
        //  $responseData = array();
        if ($db->deleteIme($ime)){
            $responseData['error'] = false;
            $responseData['message'] = 'ime has deleted successfully';
        }
        else{
            $responseData['error'] = true;
            $responseData['message'] = 'ime has not deleted successfully';
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
//Method to get unfinished requests
$app->get('/getUnfinishedRequests', function (Request $request, Response $response) {

    $db = new Balance();
    $projects = $db->getUnfinishedRequests();
    $response->getBody()->write(json_encode(array("unfinishedRQS" => $projects)));

});
//Method to get sub Cards
$app->post('/subCards', function (Request $request, Response $response) {
    if (!isTheseParametersAvailable(array('name', 'branch'))) {
        $requestData = $request->getParsedBody();
        $name= $requestData['name'] ;
        $branch= $requestData['branch'] ;

    $db = new Users();
    $cards = $db->getSubCards($name,$branch);
    }
    $response->getBody()->write(json_encode(array("cards" => $cards)));
});
//updating the Balance
$app->put('/updateBalance', function (Request $request, Response $response) {
    $requestData = $request->getParsedBody();
        $balance = $requestData['balance'];
        $id=$requestData['id'];
        $requestID=$requestData['requestID'];
        $adminName=$requestData['adminName'];
        $date=$requestData['date'];
        $ime=$requestData['ime'];
        $dbBalance = new Balance();
        //  echo"info ".$balance." ID is ".$id." requestID ".$requestID." adminName ".$adminName."  date ".$date;
    if (!isTheseParametersAvailable(array('balance','id','requestID','adminName','ime','date'))) {
        $requestData = $request->getParsedBody();
        $balance = $requestData['balance'];
        $id=$requestData['id'];
        $requestID=$requestData['requestID'];
        $adminName=$requestData['adminName'];
        $date=$requestData['date'];
        $ime=$requestData['ime'];
        $dbBalance = new Balance();
         echo"info ".$balance." ID is ".$id." requestID ".$requestID." adminName ".$adminName."  date ".$date;
        $responseData = array();
        $userName=$dbBalance->getUserName($id);
        if ($dbBalance->updateBalance($balance,$id,$requestID,$adminName,$ime,$date)) {
            if ($balance!=null){
               // echo "\n balance is :".$balance;
            }
            else{
               // echo "balance is null";

            }
            $responseData['error'] = false;
            $responseData['message'] = $balance.' Inserted successfully to '.$userName;
        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'Not updated';
        }


        $response->getBody()->write(json_encode($responseData));
    }
});

//Method to addAdminCards
$app->post('/addAdminCards', function (Request $request, Response $response) {
      $image=$requestData['name'];
        $value=$requestData['subName'];
        echo "name ".$image." subName ".$value;
    if (isTheseParametersAvailable(array('name','subName', 'branch','serialNumber'))) {
        //  $requestData = $request->getQueryParams();
        $requestData = $request->getParsedBody();

        $name = $requestData['name'] ;
        $subName = $requestData['subName'] ;
        $branch = $requestData['branch'] ;

        $serialnumber = $requestData['serialNumber'] ;

        $db = new Cards();
        $responseData = array();

        $result = $db->addAdminCards($name,$subName,$branch, $serialnumber,$value);

        if ($result == CARD_ADDED) {
            $responseData['error'] = false;
            $responseData['message'] = 'The card has been added successfully';
        } else  {
            $responseData['error'] = true;
            $responseData['message'] = 'Some error occurred The card has not been added successfully';
        }

        $response->getBody()->write(json_encode($responseData));

    }
});

//Method to get all All Cards
$app->get('/getAllAdminCards', function (Request $request, Response $response) {
    $db = new Admins();
    $projects = $db->getAllAdminCards();
    $response->getBody()->write(json_encode(array("adminCards" => $projects)));
});
//Method to get all All Cards
$app->get('/getAllCards', function (Request $request, Response $response) {
    $db = new Admins();
    $projects = $db->getAllCards();
    $response->withHeader(
        'Content-type',
        'application/json; charset=utf-8'
    );
    $response->getBody()->write(json_encode(array("cards" => $projects)));
});
//Method to get all All Users
$app->get('/getAllUsers', function (Request $request, Response $response) {
    $db = new Admins();
    $projects = $db->getAllUsers();
    $response->getBody()->write(json_encode(array("users" => $projects)));
});
//Method to get all Admins
$app->get('/getAllAdmins', function (Request $request, Response $response) {
    $db = new Admins();

    $projects = $db->getAllAdmins();
    $response->getBody()->write(json_encode(array("admins" => $projects)));
});
//Delete admin
$app->post('/deleteAdmin', function (Request $request, Response $response){
    // $id=$args['id'];
    $db=new Admins();
    if (isTheseParametersAvailable(array('id'))) {
        $requestData = $request->getParsedBody();
        //  $requestData = $request->getQueryParams();
        $id=$requestData['id'];
        echo "\n id is of user is " .$id;
        //  $responseData = array();
        if ($db->deleteAdmin($id)){
            $responseData['error'] = false;
            $responseData['message'] = 'user has deleted successfully';
        }
        else{
            $responseData['error'] = true;
            $responseData['message'] = 'user has not deleted successfully';
        }
        $response->getBody()->write(json_encode($responseData));
        // $response->getBody()->write($responseData);
//    return $response
//        ->withHeader('ContentType','application/json')
//        ->withStatus(200);
    }
});
//updating the Balance
$app->put('/updateCardValue', function (Request $request, Response $response) {
  
    if (!isTheseParametersAvailable(array('image','ime','value'))) {
        $requestData = $request->getParsedBody();
        //  $requestData = $request->getQueryParams();
        $image=$requestData['image'];
        $value=$requestData['value'];
        $ime=$requestData['ime'];

        $dbBalance = new Cards();
        $responseData = array();
        if ($dbBalance->updateCardPrice($image,$ime,$value)){
           
            $responseData['error'] = false;
            $responseData['message'] = ' Updating has been done successfully \n  value is: '.$value;
        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'Not updated';
        }

        $response->getBody()->write(json_encode($responseData));
    }
});

$app->get('/', function () {
echo 'Welcome to my slim app';
});
//function to check parameters
function isTheseParametersAvailable($required_fields)
{
    $error = false;
    $error_fields = "";
    $request_params = $_REQUEST;

    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        $response = array();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echo json_encode($response);
        return false;
    }
    return true;
}

try {
    $app->run();
} catch (Throwable $e) {
}


