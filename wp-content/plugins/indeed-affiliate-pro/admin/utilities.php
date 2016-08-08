<?php 
function uap_create_ranks_graphic($ranks_arr, $current){
	/*
	 * @param array
	 * @return string
	 */
	if (is_array($ranks_arr)){
		$new_arr = uap_reorder_ranks($ranks_arr);//reorder ranks by order attr
		$output = '';
		$padding = 7;
		foreach ($new_arr as $k=>$v){
			$class = 'uap-rank-item';
			if ($v->id==$current){
				$current_printed = TRUE;
				$class .= ' uap-current-rank';
			}
			$output .= '<div class="'.$class.'" style="padding: ' . $padding . 'px 5px;">' . $v->label . '</div>';
			$padding += 7;
		}
		if (empty($current_printed)){
			$output .= '<div class="uap-rank-item uap-current-rank" style="padding: ' . $padding . 'px 5px;">' . __('Current Rank', 'uap') . '</div>';
		}
		$output = '<div class="rank-graphic-representation">' . $output . '</div>';
		return $output;
	}
	return '';
}

function uap_return_errors(){
	/*
	 * @param none
	 * @return string
	 */
	$output = '';
	global $uap_error_register;
	if (!empty($uap_error_register)){
		$output = '<div class="uap-wrapp-the-errors">';
		foreach ($uap_error_register as $key=>$err){
			$output .= __('Field ', 'uap') . $key . ': ' . $err;
		}
		$output .= '</div>';
	}
	return $output;
}
