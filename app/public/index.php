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

/**
 * /api/users       GET     Busca todos os usuários.
 * /api/registry    POST    Registra um novo usuário.
 * /api/user/<:id>  PUT     UPDATE usuario.
 * /api/login       POST    Login do usuário.
 * /api/logout      GET     Logout do usuário.
 */

/**
 * / somente para retornar versão do app
 */
$app->get('/', function (Request $request, Response $response, $args){
    $data = array('status' => 'success', 'version' => '1.0');
    $payload = json_encode($data);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(200);
});

/**
 * /api/users
 */
$app->get('/api/users', function (Request $request, Response $response, $args) {
    $payload = json_encode(array('status' => 'success', 'message' => 'pong'));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(200);
});

/**
 * /api/registry
 */
$app->post('/api/registry', function (Request $request, Response $response, $args) {
    $post_data = json_decode(
        $request->getBody()->getContents()
    );
    $rsp = array('error' => true, 'message' => 'email or passwod is empty.');
    if (isset($post_data->email) && isset($post_data->passwd)) {
        $email = $post_data->email;
        $password = generate_password($post_data->passwd);
        //TODO usar o entity doctrine para registrar em banco
        $rsp['error'] = false;
        $rsp['status'] = 'success';
        $rsp['message'] = 'Email registred with success.';
        $payload = json_encode($rsp);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(201);
    }
    $rsp['error'] = true;
    $rsp['status'] = 'fail';
    $rsp['message'] = 'email or password registred with success.';
    $payload = json_encode($rsp);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
});

$app->put('/api/user/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $rsp = array('status' => 'success', 'message' => 'User modifing ID: '.$id);
    $payload = json_encode($rsp);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')
                    ->withStatus(201);    
});

/**
 * /api/login
 */
$app->post('/api/login', function (Request $request, Response $response, $args) {
    $post_data = json_decode(
        $request->getBody()->getContents()
    );
    $rsp = array('error' => true, 'message' => 'email or passwod is empty.');
    if (isset($post_data->email) && isset($post_data->passwd)) {
        $email = $post_data->email;
        $password = generate_password($post_data->passwd);
        //TODO usar o entity doctrine para validar password e usuario em banco
        //TODO retornar sessão de login para usuario
        $rsp['error'] = false;
        $rsp['status'] = 'success';
        $rsp['message'] = 'Email registred with success.';
        $payload = json_encode($rsp);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(201);
    }
    $rsp['error'] = true;
    $rsp['status'] = 'fail';
    $rsp['message'] = 'email or password registred with success.';
    $payload = json_encode($rsp);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
});

/**
 * /api/logout TODO
 */

function generate_password($password) {
    $hash_passwd = hash("sha256", $password);
    return $hash_passwd;
};

$app->run();
