<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;

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

?>