<?php

	function getCategories($visible = false) {
		global $db;
		
		$showvisible = $visible ? '1' : '`visible` = 1';
		return $db->selectList('catering_categories', '*', $showvisible, '`rank` ASC ');
	}
	
	function getIngredients($productid) {
		global $db;
		$ingredientssql = $db->query("SELECT * FROM `".MYSQL_TABLE_PREFIX."catering_products_ingredients` AS pi LEFT JOIN `".MYSQL_TABLE_PREFIX."catering_ingredients` as i on `pi`.`ingredientid` = `i`.`ingredientid` WHERE `pi`.`productid`=".$productid." AND `available` > 0 ORDER BY `name` ASC");
	
		$ingredients = array();
		while($ingredient = mysql_fetch_assoc($ingredientssql)) {
			$ingredients[] = $ingredient;
		}
		
		return $ingredients;
	}
	
	function getProduct($productid) {
		global $db;
		
		return $db->selectOneRow('catering_products', '*', '`productid`='.(int)$productid);
	}
	
	function addOrderToSession($productid, $ingredients) {
		$product = getProduct($productid);
		if($product == null) {
			return;
		}
		
		if($product['price'] == 0 && (count($ingredients) == 0 || @trim($ingredients[0]) == '')) {
			global $lang;
			echo $lang->get('without_ingredients_not_addable');
			return;
		}
		
		// Check if the same product is already in the cart
		$allreadythere = -1;
		if(isset($_SESSION['catering'])) {
			for($i = 0; $i < count($_SESSION['catering']['order']['products']); $i++) {
				// Check if the product is the same
				if($_SESSION['catering']['order']['products'][$i]['productid'] == $productid) {
					// If it has ingredients
					if(count($ingredients) > 0 && $ingredients[0] != "") {
						// Compare the number of ingredients
						if(count($_SESSION['catering']['order']['products'][$i]['ingredients']) == count($ingredients)) {
							$allreadythere = $i;
							foreach($ingredients AS $ingredient) {
								if(!in_array($ingredient, $_SESSION['catering']['order']['products'][$i]['ingredients'])) {
									$allreadythere = -1;
								}
							}
							if($allreadythere != -1) {
								break 1;
							}
						}
					}
					else {
						$allreadythere = $i;
					}
				}
			}
		}
		
		if($allreadythere != -1) {
			updateOrderQuantityInSession($allreadythere, $_SESSION['catering']['order']['products'][$allreadythere]['quantity']+1);
		} else {
			$newproduct = array();
			$newproduct['productid'] = $productid;
			if(count($ingredients) != 0 && $ingredients[0] != "") {
				$newproduct['ingredients'] = $ingredients;
			}
			$newproduct['quantity'] = 1;
			
			$_SESSION['catering']['order']['products'][] = $newproduct;
		}
	}
	
	function updateOrderQuantityInSession($index, $quantity) {
		global $notify;
		global $lang;
		
		$quantity = (int) $quantity;
		if($quantity <= 0) {
			unset($_SESSION['catering']['order']['products'][$index]);
			sort($_SESSION['catering']['order']['products']);
		} else {
			if(!checkAvailability($quantity, 
									$_SESSION['catering']['order']['products'][$index]['productid'], 
									@$_SESSION['catering']['order']['products'][$index]['ingredients'])) {
				echo $lang->get('amount_not_available');
			} else {
				$_SESSION['catering']['order']['products'][$index]['quantity'] = $quantity;
			}
		}
	}
	
	function checkAvailability($quantity, $productid, $ingredientids) {
		global $db;
		
		$product = $db->selectOneRow("catering_products", "*", "`productid`=".$productid);
		if($product['amount'] != -1 && $product['amount'] < $quantity) {
			return false;
		}
		if($ingredientids != null && count($ingredientids) > 0 && $ingredientids[0] != "") {
			foreach($ingredientids as $ingredientid) {
			
			$ingredient = $db->selectOneRow("catering_ingredients", "*", "`ingredientid`=".$ingredientid);

			if($ingredient['available'] != 1) {
				return false;
				}
			}
			
		}
		return true;
	}
	
	function getOrderFromSession() {
		global $db;
		if(!isset($_SESSION['catering']['order']['products'])) {
			return;
		}
		
		$price = 0;
		$allproducts = array();

		foreach($_SESSION['catering']['order']['products'] as $product) {
			$product_DB = $db->selectOneRow("catering_products", '*', "`productid`=".$product['productid']);
			
			$tmp = array();
			$tmp = $product_DB;
			
			if(isset($product['ingredients'])) {
				foreach($product['ingredients'] as $ingredientid) {
					$ingredient_DB = $db->selectOneRow("catering_ingredients", '*', "`ingredientid`=".$ingredientid);
			
					$tmp['ingredients'][] = $ingredient_DB;
					@$tmp['price'] += $ingredient_DB['price'];
				}
			}
			$tmp['quantity'] = $product['quantity'];
			$tmp['quantityprice'] = $tmp['price'] * $tmp['quantity'];
		
			$price += $tmp['quantityprice'];
			$allproducts['products'][] = $tmp;
		}
		$allproducts['price'] = $price;
		
		return $allproducts;
	}
	
	function submitOrderToDB($iAmSeller = false) {
		global $db;
		
		$orderid = createOrder($iAmSeller);
		
		foreach(@$_SESSION['catering']['order']['products'] as $product) {
			if(checkAvailability($product['quantity'], $product['productid'], @$product['ingredients'])) {
				$product_DB = $db->selectOneRow("catering_products", '*', "`productid`=".$product['productid']);

				$db->insert('catering_items', 
						array('orderid', 'productid', 'amount', 'state', 'price'),
						array($orderid, $product['productid'],  $product['quantity'], 0, $product_DB['price']));
				$itemid = mysql_insert_id();	
				if(isset($product['ingredients'])) {
					foreach($product['ingredients'] as $ingredientid) {
						$ingredient_DB = $db->selectOneRow("catering_ingredients", '*', "`ingredientid`=".$ingredientid);	
						$db->insert('catering_items_ingredients',
									array('itemid', 'ingredientid', 'price'),
									array($itemid, (int) $ingredientid, $ingredient_DB['price']));
					}
				}
			} 
		}
		unset($_SESSION['catering']);
	}
	
	function getOrder($orderid) {
		global $db;
		return $db->selectOneRow('catering_orders', '*', '`orderid`='.(int)$orderid);;
	}
	
	function createOrder($iAmSeller = false) {
		global $db;
		global $login;
		$isold = $iAmSeller ? 1 : 0;
		
		$db->insert('catering_orders', 
					array('ordererid', 'date', 'isold'),
					array("'".$login->currentUserID()."'", time(), $isold));
		return mysql_insert_id();
	}
		
?>