<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app = new Slim();


$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();
	$page->setTpl("index");

});

$app->get('/admin', function() {

	User::verifyLogin();

	$pageAdmin = new PageAdmin();
	$pageAdmin->setTpl("index");
	
});

$app->get('/admin/login', function() {

	$pageAdmin = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$pageAdmin->setTpl("login");

});

$app->post('/admin/login', function() {

	User::login( $_POST["login"], $_POST["password"] );
	header("Location: /admin");
	exit;

});

$app->get('/admin/logout', function() {

	User::logout();	
	header("Location: /admin/login");
	exit;

});

$app->get("/admin/users", function() {

	User::verifyLogin();

	$users = User::listAll();

	$pageAdmin = new PageAdmin();
	$pageAdmin->setTpl("users", array(
		"users"=>$users
	));

});

$app->get("/admin/users/create", function() {

	User::verifyLogin();

	$pageAdmin = new PageAdmin();
	$pageAdmin->setTpl("users-create");

});

$app->post("/admin/users/create", function() { //cria usuario

	User::verifyLogin();

	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
	$user->setData($_POST);
	$user->save();
	header("Location: /admin/users");
	exit;
	//var_dump( $user ); // mostra o LINDO acontecimento da criacao dinamica dos getters and setters ja que os inpus tem o mesmo nome que os campos no banco

});

$app->get("/admin/users/:iduser/delete", function( $iduser ) { // delete (nao foi usado o metodo delete pq o rain tpl nao aceita este metodo entao deve passado pela url o delete)

	User::verifyLogin();

	$user = new User();
	$user->get( (int)$iduser );
	$user->delete();

	header("Location: /admin/users");
	exit;

});

$app->get("/admin/users/:iduser", function( $iduser ) { // traz os dados do usuario selecionado vvia url para o template update user

	User::verifyLogin();

	$user = new User();
	$user->get((int)$iduser);
	
	$pageAdmin = new PageAdmin();
	$pageAdmin->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

$app->post("/admin/users/:iduser", function( $iduser ) { // chama o update apos mudar alguma inf

	User::verifyLogin();

	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
	$user->get((int)$iduser);
	$user->setData($_POST);
	$user->update();

	header("Location: /admin/users");
	exit;
	
});

$app->get("/admin/forgot", function() {

	$pageAdmin = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$pageAdmin->setTpl("forgot");

});

$app->post("/admin/forgot", function() {

	$user = User::getForgot( $_POST["email"] );

	header("Location: /admin/forgot/sent");
	exit;

});

$app->get("/admin/forgot/sent", function() {

	$pageAdmin = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$pageAdmin->setTpl("forgot-sent");

});

$app->get("/admin/forgot/reset", function() {

	$user = User::validForgotDecrypt( $_GET["code"] );

	$pageAdmin = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$pageAdmin->setTpl("forgot-reset", array(
		"name" => $user["desperson"],
		"code" => $_GET["code"]
	));

});

$app->post("/admin/forgot/reset", function() {

	$forgot = User::validForgotDecrypt( $_POST["code"] );

	User::setForgotUsed( $forgot["idrecovery"] );

	$user = new User();
	$user->get( (int)$forgot["iduser"] );

	$password = password_hash( $_POST["password"], PASSWORD_DEFAULT, [
		"cost" => 12
	] );

	$user->setPassword( $password );

	$pageAdmin = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$pageAdmin->setTpl("forgot-reset-success");

});

$app->get("/admin/categories", function() {

	User::verifyLogin();

	$categories = Category::listAll();

	$pageAdmin = new PageAdmin();
	$pageAdmin->setTpl("categories", array(
		"categories" => $categories
	));

});

$app->get("/admin/categories/create", function() {

	User::verifyLogin();

	$pageAdmin = new PageAdmin();
	$pageAdmin->setTpl("categories-create");

});

$app->post("/admin/categories/create", function() {

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);
	$category->save();

	header('Location: /admin/categories');
	exit;

});

$app->get("/admin/categories/:idcategory/delete", function( $idcategory ) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);
	$category->delete();

	header('Location: /admin/categories');
	exit;

});

$app->get("/admin/categories/:idcategory", function( $idcategory ) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$pageAdmin = new PageAdmin();
	$pageAdmin->setTpl("categories-update", array(
		"category"=>$category->getValues()
	));

});

$app->post("/admin/categories/:idcategory", function( $idcategory ) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;

});



$app->run();

 ?>