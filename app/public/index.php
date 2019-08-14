<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__.'/../../vendor/autoload.php';

$app = AppFactory::create();

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thown from it will not be handled by the middleware
 */
$app->addRoutingMiddleware();

/**
 * @param bool $displayErrorDatails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * Which can be replaced by a callable of your choice.
 *
 * NOTE: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args){
    $data = array('status' => 'success', 'message' => 'pong');
    $payload = json_encode($data);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(200);
});

$app->post('/api/users', function (Request $request, Response $response, $args) {
    $_input = (object) $request->getParams();
    $_email = $_input->email;
    $_passwd = $_input->passwd;
    $rsp = array();
    var_dump($_input);
    exit;
    if(!empty($_email && !empty($_passwd))){
        $rsp['error'] = false;
        $rsp['message'] = "Email: ".$_email." | Password: ".$_passwd;
    } else {
        $rsp['error'] = false;
        $rsp['message'] = "You have not posted any data";
    }
    $payload = json_encode($rsp);
    $response->getBody()->write($payload);
    return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
});

$app->run();
