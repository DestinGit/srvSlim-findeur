<?php
/**
 * Created by Destin Gando.
 * User: Destin
 * Date: 15/12/2017
 * Time: 16:17
 */
use Firebase\JWT\JWT;
//use Psr\Http\Message\RequestInterface;
use Tuupola\Base62;
use Slim\Http\Response;
use Slim\Http\Request;


$app->add('CorsMiddleware');
//$app->add('JwtAuthentication');

// Routes public
$app->group('/get', function () use ($app) {
    // Utiliser pour la connexion
    $app->post('/user', app\Controller\UserCtrl::class . ":findUserPost2");
    // Pour l'inscription
    $app->post('/add-user', app\Controller\UserCtrl::class . ":addUserPost");

    // Routes pour les services proposés par des freelances
    $app->get('/freelance-list', app\Controller\TextPatternCtrl::class . ':getPersonalBusiness');

    // Routes pour récupérer un freelance
    $app->get('/freelance', app\Controller\TextPatternCtrl::class . ':getOnePersonalBusiness');

    // Routes pour les missions proposées par les entreprises
    $app->get('/missions-list', app\Controller\TextPatternCtrl::class . ':getListOfMissionsToApply');

    // Routes pour récupérer la liste des compétences
    $app->get('/skills-list', app\Controller\CategoryCtrl::class . ':getAllSkills');

    // Routes pour récupérer la liste des régions
    $app->get('/area-list', app\Controller\CategoryCtrl::class . ':getAllsArea');

    // Routes pour récupérer la liste des mobilités
    $app->get('/mobility-list', app\Controller\CategoryCtrl::class . ':getAllsMobilities');

});

$app->group('/secure', function () use ($app) {
    // Route pour poster une annonce ou postuler à une mission
    $app->post('/applytomission',app\Controller\TextPatternCtrl::class . ':applyToAMission');
    $app->post('/registermission', app\Controller\TextPatternCtrl::class . ':persistArticle');

    $app->post('/remove', app\Controller\TextPatternCtrl::class . ':deleteArticle');

    $app->get('/mycandidatures-list', app\Controller\TextPatternCtrl::class . ':getListsOfMyCandidatures');

    $app->get('/myprojects-list',app\Controller\TextPatternCtrl::class . ':getListOfMyProjects');
})->add('JwtAuthentication');







// Les routes déclarées sont préfixées par /user
$app->group("/user", function () use ($app) {

    $app->get('/list', app\Controller\UserCtrl::class . ":getAllUsers");
    $app->get('/details/{login}/{pass}', app\Controller\UserCtrl::class . ":getOneUser");
    $app->get('/find/{name}/{pass}', app\Controller\UserCtrl::class . ":findUser");

    $app->get('/find', app\Controller\UserCtrl::class . ":findUserPost2");
    $app->post('/find', app\Controller\UserCtrl::class . ":findUserPost2");
    $app->post('/add', app\Controller\UserCtrl::class . ":addUserPost");
    $app->get('/add', app\Controller\UserCtrl::class . ":addUserPost");
});

// Routes pour les services proposés par des freelances
//$app->get('/freelance-list', app\Controller\TextPatternCtrl::class . ':getPersonalBusiness');

// Routes pour les missions proposées par les entreprises
$app->get('/missions-list', app\Controller\TextPatternCtrl::class . ':getListOfMissionsToApply');

// Route pour poster une annonce ou postuler à une mission
$app->group('/ar', function () use ($app) {
    $app->post('/add',app\Controller\TextPatternCtrl::class . ':applyToAMission');
    $app->post('/newmission', app\Controller\TextPatternCtrl::class . ':addNewMission');
});












/*$app->post("/token",  function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) use ($container){
    // Here generate and return JWT to the client.
    //$valid_scopes = ["read", "write", "delete"]

    $requested_scopes = $request->getParsedBody() ?: [];

    $now = new DateTime();
    $future = new DateTime("+10 minutes");
    $server = $request->getServerParams();
    $jti = (new Base62)->encode(random_bytes(16));
    $payload = [
        "iat" => $now->getTimeStamp(),
        "scopes" => ["ROLE_USER"],
        "exp" => $future->getTimeStamp(),
        "jti" => $jti,
        "sub" => $server["PHP_AUTH_USER"],
        "user_name" => 'name',
    ];
    $secret = getenv('SECRET_KEY_JWT');
    $token = JWT::encode($payload, $secret, "HS256");
    $data["token"] = $token;
    $data["expires"] = $future->getTimeStamp();
    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});*/
/*
$app->get("/secure",  function (\Slim\Http\Request $request,\Slim\Http\Response $response, $args) {

    $data = ["status" => 1, 'msg' => "This route is secure!"];

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->get("/not-secure",  function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {

    $data = ["status" => 1, 'msg' => "No need of token to access me"];

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->post("/formData",  function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $data = $request->getParsedBody();

    $data = $this->get('jwt');
    $result = ["status" => 1, 'msg' => $data];

    // Request with status response
    return $this->response->withJson($result, 200);
});*/

/*
$app->get('/home', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', ["name" => "Welcome to Trinity Tuts demo Api"]);
});*/
