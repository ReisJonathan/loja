<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;


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

?>