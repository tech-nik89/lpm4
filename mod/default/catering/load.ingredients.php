<?php
	$productid = (int) $_GET['productid'];
	
	require_once('mod/default/catering/catering.function.php');
	$ingredients = getIngredients($productid);
	
	$smarty->assign('ingredients', $ingredients);
	$smarty->display('../mod/default/catering/show.ingredients.tpl');
?>
