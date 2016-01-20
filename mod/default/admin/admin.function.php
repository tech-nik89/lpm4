<?php
	function updateElement($element) {
		global $menu;
		if (@$_POST['delete_'.$element['menuid']] == '1') {
			$menu->removeElement($element['menuid']);
		}
		else {
			if (isset($_POST['order_'.$element['menuid']]) && @$element['order'] != @$_POST['order_'.$element['menuid']]) {
				$menu->setElementOrder($element['menuid'], $_POST['order_'.$element['menuid']]);
			}
		}
		if (count($element['children']) > 0) {
			foreach ($element['children'] as $child) {
				updateElement($child);
			}
		}
	}
?>