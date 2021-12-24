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



//$app = new \Slim\App;
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);
$app->get('/getAppVersion', function (Request $request, Response $response) {
    

              $responseData['version'] = '1.2';
    $response->getBody()->write(json_encode($responseData));
});
//Get facebook  
$app->get('/facebook',function (Request $request, Response $response) {
    $responseData['facebook'] = 'https://www.facebook.com/Suda-Card-866291140395378/';
    $response->getBody()->write(json_encode($responseData));


});
//Get twitter 
$app->get('/twitter',function (Request $request, Response $response) {
    $responseData['twitter'] = 'https://twitter.com/AhmedSuda355?s=09';
    $response->getBody()->write(json_encode($responseData));


});
//Get youtube 
$app->get('/youtube',function (Request $request, Response $response) {
    $responseData['youtube'] = 'https://youtube.com/channel/UCQcMgkuKej4-2U9X1FQTKng';
    $response->getBody()->write(json_encode($responseData));


});
//Get inst 
$app->get('/inst',function (Request $request, Response $response) {
    $responseData['inst'] = 'https://www.instagram.com/suda.card?r=nametag';
    $response->getBody()->write(json_encode($responseData));


});
//Get whatsApp 
$app->get('/whatsApp',function (Request $request, Response $response) {
    $responseData['whatsApp'] = 'https://wa.me/249969025818';
    $response->getBody()->write(json_encode($responseData));


});

//Method to get particular user
$app->get('/ParticularUser/{id}', function (Request $request, Response $response) {
    $UserID = $request->getAttribute('id');
    $db = new Users();
    $users = $db->getParticularUser($UserID);
    $response->getBody()->write(json_encode(array("users" => $users)));
});
//Method to get particular Cards
$app->get('/ParticularCards/{categoryId}', function (Request $request, Response $response) {
    $categoryId = $request->getAttribute('categoryId');
    $db = new Users();
    $cards = $db->getParticularCards($categoryId);
    $response->getBody()->write(json_encode(array("cards" => $cards)));
});
//Method to get sub Cards
$app->post('/subCards', function (Request $request, Response $response) {
    if (isTheseParametersAvailable(array('name', 'branch'))) {
        $requestData = $request->getParsedBody();
        $name= $requestData['name'] ;
        $branch= $requestData['branch'] ;

    $db = new Users();
    $cards = $db->getSubCards($name,$branch);
    }
    $response->getBody()->write(json_encode(array("cards" => $cards)));
});
//getting pages
$app->get('/pages', function (Request $request, Response $response) {
    $db = new DbOperation();
    $project = $db->getPages();
    $response->getBody()->write(json_encode(array("pages" => $project)));
});
//getting banks
$app->get('/banks', function (Request $request, Response $response) {
    $db = new DbOperation();
    $banks = $db->getBanks();
    $response->getBody()->write(json_encode(array("banks" => $banks)));
});
//Method to get wallet info
$app->get('/getWalletInfo/{userId}', function (Request $request, Response $response) {
    //  $requestData = $request->getParsedBody();
    $requestData = $request->getQueryParams();
    //$userId=$requestData['userId'] ;
    $userId = $request->getAttribute('userId');
    $db = new SellHistory();
    $projects = $db->getWalletInfo($userId);
    $response->getBody()->write(json_encode(array("walletInfos" => $projects)));

});
$app->get('/getBranches/{name}', function (Request $request, Response $response){

    $requestData = $request->getQueryParams();
    $name = $request->getAttribute('name');
    $db=new DbOperation();
    $branches = $db->getBranches($name);
    $response->getBody()->write(json_encode(array("branches" => $branches)));

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

//getting all cards
$app->get('/allCards', function (Request $request, Response $response) {
    $db = new DbOperation();
    $projects = $db->getAllCards();
    $response->getBody()->write(json_encode(array("cards" => $projects)));
});
//Method to get Sell History
$app->get('/getSellHistory/{userId}', function (Request $request, Response $response) {
    $userID = $request->getAttribute('userId');
    $db = new SellHistory();
    // echo "user id :".$this->$userID;
    $histories = $db->getSellHistory($userID);
    $response->getBody()->write(json_encode(array("histories" => $histories)));

});
//user login route
$app->post('/login',function (Request $request, Response $response) {
    if (isTheseParametersAvailable(array('email', 'password'))) {
        $requestData = $request->getParsedBody();
        $email = $requestData['email']?? false;
        $password = $requestData['password']?? false;
        $db = new DbOperation();
        $responseData = array();
        if ($db->userLogin($email, $password)) {
            $responseData['error'] = false;
            $responseData['user'] = $db->getUserByEmail($email);
        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'Invalid email or password';
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
//request Card
$app->post('/requestCard', function (Request $request, Response $response) {
    //   $image=$requestData['name'];
    //     $value=$requestData['subName'];
    //     echo "name ".$image." subName ".$value;
    if (isTheseParametersAvailable(array('name','subName', 'branch','date'))) {
        //  $requestData = $request->getQueryParams();
        $requestData = $request->getParsedBody();

        $name = $requestData['name'] ;
        $subName = $requestData['subName'] ;
        $branch = $requestData['branch'] ;

                $date = $requestData['date'] ;


        $db = new Cards();
        $responseData = array();

        $result = $db->requestCard($name,$subName,$branch,$date);

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


//is  user login exist
$app->post('/isUserExist',function (Request $request, Response $response) {
    if (isTheseParametersAvailable(array('email'))) {
        $requestData = $request->getParsedBody();
        $email = $requestData['email']?? false;
        $db = new DbOperation();
        $responseData = array();
        if ($db->isUserExist($email)) {
            $responseData['error'] = false;
            $responseData['message'] = 'email is  exist';
            $responseData['user'] = $db->getUserByEmail($email);
        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'email is not exist';
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
//updating a password of the user user
$app->put('/updatePassword', function (Request $request, Response $response) {
       
        $requestData = $request->getParsedBody();
        $id=$requestData['id'];
        $password=$requestData['password'];

        $db = new DbOperation();
        $responseData = array();
        if ($db->updatePassword($id,$password)) {
            $responseData['error'] = false;
            $responseData['message'] = ' password has updated  successfully  ';
        }


        else {
            $responseData['error'] = true;
            $responseData['message'] = 'Not updated';
        }


        $response->getBody()->write(json_encode($responseData));
    
        
});
$app->post('/registerUser', function (Request $request, Response $response) {
    if (isTheseParametersAvailable(array('name', 'email', 'image'))) {
        $requestData = $request->getParsedBody();

        $name= $requestData['name'] ;
        $email = $requestData['email'] ;
        $image= $requestData['image'] ;
        $db = new DbOperation();
        $responseData = array();

        $result = $db->createUser($name,$email, $image);

        if ($result == USER_CREATED) {
            $responseData['error'] = false;
            $responseData['message'] = 'Registered successfully';
            $responseData['user'] = $db->getUserByEmail($email);
        } elseif ($result == USER_CREATION_FAILED) {
            $responseData['error'] = true;
            $responseData['message'] = 'Some error occurred';
        } elseif ($result == USER_EXIST) {
            $responseData['error'] = false;
            $responseData['message'] = 'This email already ';
            $responseData['user'] = $db->getUserByEmail($email);
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


