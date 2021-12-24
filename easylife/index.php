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
require_once '../includes/EasyLife.php';
require_once '../includes/Cards.php';



//$app = new \Slim\App;
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);
$app->get('/getBranches/{name}', function (Request $request, Response $response){

    $requestData = $request->getQueryParams();
    $name = $request->getAttribute('name');
    $db=new DbOperation();
    $branches = $db->getBranches($name);
    $response->getBody()->write(json_encode(array("branches" => $branches)));

});
//Method to get sub Cards
$app->post('/subCards', function (Request $request, Response $response) {
    if (isTheseParametersAvailable(array('name', 'branch'))) {
        $requestData = $request->getParsedBody();
        $name= $requestData['name'] ;
        $branch= $requestData['branch'] ;

    $db = new EasyLife();
    $cards = $db->getSubCards($name,$branch);
    }
    $response->getBody()->write(json_encode(array("cards" => $cards)));
    $response->getBody()->write(json_encode(array("header" => array("cards" => $projects))));
});

//Method to buy card
$app->post('/buyCard', function (Request $request, Response $response) {
    
    if (isTheseParametersAvailable(array('subName','branch','cardName','userId','date','cardId'))) {
        $requestData = $request->getParsedBody();
    
        
        $cardName = $requestData['cardName'] ;
        $subName = $requestData['subName'] ;
        $branch = $requestData['branch'] ;
        $userId = $requestData['userId'] ;
        $date = $requestData['date'] ;
       $cardID = $requestData['cardId'] ;
        $dbCard = new BuyCards();
        $responseData = array();
// echo " main  name is ".$cardName." subName is ".$subName." branch is  ".$branch." id ".$userId." date ".$date." card image is ".$cardImage;

        $result = $dbCard->buyCard($cardName,$subName,$branch,$userId,$date,$cardID);
        if ($result==CARD_NOT_FOUND){
            $responseData['error'] = true;
            $responseData['message'] = 'نأسف البطاقة التي طلبتها غير موجودة الرجاء المحاولة لاحقا';
        }
        elseif ($result == PURCHASE_COMPLETED) {
            $responseData['error'] = false;
            $responseData['message'] = 'تمت عملية الشراء بنجاح';
        } elseif ($result == PURCHASE_FAILED) {
            $responseData['error'] = true;
            $responseData['message'] = 'Some error occurred please try again !!!';
        }
        elseif ($result == LESS_BALANCE) {
            $responseData['error'] = true;
            $responseData['message'] = 'عفواً رصيدك غير كافي لشراء هذة البطاقة';
       }

        $response->getBody()->write(json_encode($responseData));

    }
});





//function to check parameters
function isTheseParametersAvailable($required_fields){
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
$app->get('/', function () {
    echo 'Welcome to my slim app';
});
//Testing method
$app->get('/getUser/{userEmail}', function (Request $request, Response $response) {
    $db=new DbOperation();
    $userEmail = $request->getAttribute('userEmail');
    $responseData['user'] = $db->getId($userEmail);
    $response->getBody()->write(json_encode(array("histories" => $responseData)));

});
$app->get('/conectTest', function () {
    $db_conec=new DbConnect();
    if ($db_conec->usersConnect()){
        die( json_encode(array("status" => "Success", "message" => "db conected")));

    }
    else{
        die( json_encode(array("status" => "fiaul", "message" => "db did not conected")));
    }
});




try {
    $app->run();
} catch (Throwable $e) {
}


