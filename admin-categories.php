<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

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

$app->get("/category/:idcategory", function( $idcategory ) {

	$category = new Category();

	$category->get( (int)$idcategory );

	$page = new Page();
	$page->setTpl("category", array(
		'category'=>$category->getValues(),
		'products'=>[]
	));

});

?>