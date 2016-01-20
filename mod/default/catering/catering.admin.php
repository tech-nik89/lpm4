<?php
	/* Catering Administration */
	
	$breadcrumbs->addElement($lang->get('admin'), makeURL($mod, array('mode' => 'admin')));
	@$action = $_GET['action'];
	$menu->addSubElement($mod, $lang->get('categories'), 'admin', array('action' => 'categories'));
	$menu->addSubElement($mod, $lang->get('products'), 'admin', array('action' => 'products'));
	$menu->addSubElement($mod, $lang->get('ingredients'), 'admin', array('action' => 'ingredients'));
	
	switch ($action) {
		case 'products':
			$smarty->assign('path', $template_dir.'/products.admin.tpl');
			$breadcrumbs->addElement($lang->get('products'), makeURL($mod, array('mode' => 'admin', 'action' => 'products')));
			@$amount = $_POST['InfiniteAmountCheckBox'] == 1 ? -1 : (int)$_POST['AmountTextBox'];
			
			if (isset($_POST['NewProductSubmitButton'])) {
				@$db->insert('catering_products',
					array('categoryid', 'name', 'description', 'price', 'amount', 'visible', 'configurable', 'sellerid'),
					array((int)$_POST['CategorySelect'], 
						"'".$_POST['NameTextBox']."'", 
						"'".$_POST['DescriptionTextArea']."'",
						(int)$_POST['PriceTextBox'],
						$amount,
						(int)$_POST['VisibleCheckBox'],
						(int)$_POST['ConfigurableCheckBox'],
						(int)$_POST['SellerTextBox']
					)
				);
				if (@(int)$_POST['ConfigurableCheckBox'] == 1) {
					$productid = mysql_insert_id();
					$ingredients = $db->selectList('catering_ingredients');
					foreach ($ingredients as $i => $ingredient) {
						if (@(int)$_POST['Ingredient_'.$ingredient['ingredientid']] == 1) {
							$db->insert('catering_products_ingredients',
								array('productid', 'ingredientid'),
								array($productid, $ingredient['ingredientid'])
							);
						}
					}
				}
			}
			
			if (isset($_POST['EditProductSubmitButton'])) {
				@$db->update('catering_products',
					"`categoryid`=".(int)$_POST['CategorySelect'].",
					`name`='".secureMySQL($_POST['NameTextBox'])."',
					`description`='".secureMySQL($_POST['DescriptionTextArea'])."',
					`price`=".(int)$_POST['PriceTextBox'].",
					`amount`=".$amount.",
					`visible`=".(int)$_POST['VisibleCheckBox'].",
					`configurable`=".(int)$_POST['ConfigurableCheckBox'].",
					`sellerid`=".(int)$_POST['SellerTextBox'],
					"`productid`=".(int)$_POST['productid']
				);
				if (@(int)$_POST['ConfigurableCheckBox'] == 1) {
					$productid = (int)$_POST['productid'];
					$db->delete('catering_products_ingredients', '`productid`='.$productid);
					$ingredients = $db->selectList('catering_ingredients');
					foreach ($ingredients as $i => $ingredient) {
						if (@(int)$_POST['Ingredient_'.$ingredient['ingredientid']] == 1) {
							$db->insert('catering_products_ingredients',
								array('productid', 'ingredientid'),
								array($productid, $ingredient['ingredientid'])
							);
						}
					}
				}
			}
			
			if (isset($_POST['DeleteProductSubmitButton'])) {
				$db->delete('catering_products', "`productid`=".(int)$_POST['productid']);
				$db->delete('catering_products_ingredients', "`productid`=".(int)$_POST['productid']);
			}
			
			$products = $db->selectList('catering_products', '*', '1', '`name` ASC');
			foreach ($products as $i => $product) {
				$products[$i]['url'] = 'ajax_request.php?mod=catering&amp;file=edit.products.ajax&amp;productid='.$product['productid'];
			}
			$smarty->assign('products', $products);
			
			break;
		case 'ingredients':
			$breadcrumbs->addElement($lang->get('ingredients'), makeURL($mod, array('mode' => 'admin', 'action' => 'ingredients')));
			$smarty->assign('path', $template_dir.'/ingredients.admin.tpl');
			
			if (isset($_POST['NewIngredientSubmitButton'])) {
				@$db->insert('catering_ingredients',
					array('available', 'name', 'description', 'price'),
					array((int)$_POST['AvailableCheckBox'], "'".$_POST['NameTextBox']."'", "'".$_POST['DescriptionTextArea']."'", (int)$_POST['PriceTextBox'])
				);
			}
			
			if (isset($_POST['EditIngredientSubmitButton'])) {
				@$db->update('catering_ingredients',
					"`available`=".(int)$_POST['AvailableCheckBox'].",
					`name`='".secureMySQL($_POST['NameTextBox'])."',
					`description`='".secureMySQL($_POST['DescriptionTextArea'])."',
					`price`=".(int)$_POST['PriceTextBox'],
					"`ingredientid`=".(int)$_POST['ingredientid']
				);
			}
			
			if (isset($_POST['DeleteIngredientSubmitButton'])) {
				$db->delete('catering_ingredients', "`ingredientid`=".(int)$_POST['ingredientid']);
			}
			
			$ingredients = $db->selectList('catering_ingredients', '*', '1', '`name` ASC');
			foreach ($ingredients as $i => $ingredient) {
				$ingredients[$i]['url'] = 'ajax_request.php?mod=catering&amp;file=edit.ingredients.ajax&amp;ingredientid='.$ingredient['ingredientid'];
				$ingredients[$i]['available'] = intToYesNo($ingredient['available']);
			}
			$smarty->assign('ingredients', $ingredients);
			break;
		case 'categories':
		default:
			$breadcrumbs->addElement($lang->get('categories'), makeURL($mod, array('mode' => 'admin', 'action' => 'categories')));
			$smarty->assign('path', $template_dir.'/categories.admin.tpl');
			
			if (isset($_POST['NewCategorySubmitButton'])) {
				@$db->insert('catering_categories',
					array('rank', 'name', 'visible'),
					array((int)$_POST['OrderTextBox'], "'".$_POST['NameTextBox']."'", (int)$_POST['VisibleCheckBox'])
				);
			}
			
			if (isset($_POST['EditCategorySubmitButton'])) {
				@$db->update('catering_categories',
					"`rank`=".(int)$_POST['OrderTextBox'].",
					`name`='".secureMySQL($_POST['NameTextBox'])."',
					`visible`=".(int)$_POST['VisibleCheckBox'],
					"`categoryid`=".(int)$_POST['categoryid']
				);
			}
			
			if (isset($_POST['DeleteCategorySubmitButton'])) {
				$db->delete('catering_categories', "`categoryid`=".(int)$_POST['categoryid']);
			}
			
			$categories = $db->selectList('catering_categories', '*', '1', '`rank` ASC');
			foreach ($categories as $i => $category) {
				$categories[$i]['url'] = 'ajax_request.php?mod=catering&amp;file=edit.categories.ajax&amp;categoryid='.$category['categoryid'];
				$categories[$i]['visible'] = intToYesNo($category['visible']);
			}
			$smarty->assign('categories', $categories);
			break;
	}
	
	$url['AddCategory'] = 'ajax_request.php?mod='.$mod.'&amp;file=add.categories.ajax';
	$url['AddIngredient'] = 'ajax_request.php?mod='.$mod.'&amp;file=add.ingredients.ajax';
	$url['AddProduct'] = 'ajax_request.php?mod='.$mod.'&amp;file=add.products.ajax';
	$smarty->assign('url', $url);
?>