<?php
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 300);
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

class Ajax_Export_Pending_Payments {
    public function __construct()
    {
        $this->export();
        wp_redirect($_SERVER['HTTP_REFERER']);
    }

    public function export($username = array())
    {
        require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
        require_once(dirname(dirname(dirname(__FILE__))).'/class/PHPExcel.php');

        $user_class = getInstance('Class_User');
        $payza = getInstance('Class_Payza');

        $fields = $_POST['pay_user'];
        $list = $_POST['list'];

        if(!empty($fields) || $list === 'all'):
        	
        	if($list === 'all'):
        		//Get all users
        		$users = $payza->get_all_user_via_paid_unpaid_id(); 
        	else:
        		$users = array();
        		foreach ($fields as $key => $value) {
        			//Get the selected users
        			$user = $payza->get_user_via_paid_unpaid_id($value); 
	                $users[] = $user[0];
        		}
        	endif;

        	$objPHPExcel = new PHPExcel();
	        $sheet = $objPHPExcel->getActiveSheet();

	        //List of titles
	        $title_list = array(
	        	'Vendor Name', 
	        	'Company Name', 
	        	'Mr./Ms.', 
	        	'First Name', 
	        	'M.I', 
	        	'Last Name', 
	        	'Main Phone', 
	        	'Fax', 
	        	'Alt. Phone', 
	        	'E-Mail', 
	        	'Address 1', 
	        	'Address 2', 
	        	'Address 3', 
	        	'Address 4', 
	        	'Address 5',
                'Request ID',
	        	'Payment Method',
	        	'Requested Date',
	        	'Amount',
                'Transaction No.',
                'Date of Payment'
	        );
	        if(!empty($users)):
	            $row_num = 1;
	            foreach ($users as $listkey => $list) {
	                if($row_num !== 1):
	                	//Build values
                        $values = array(
                        	'A' => sprintf('%s %s', $list['f_name'], $list['l_name']),
                        	'B' => '',
                        	'C' => '',
                        	'D' => $list['f_name'],
                        	'E' => '',
                        	'F' => $list['l_name'],
                        	'G' => $list['phone_no'], 
                        	'H' => '',
                        	'I' => '',
                        	'J' => $list['email'],
                        	'K' => $list['address'],
                        	'L' => $list['district'],
                        	'M' => $list['state'],
                        	'N' => $list['country'],
                        	'O' => '',
                            'P' => $list['id'],
                            'Q' => $list['pay_mode'],
                            'R' => $list['request_date'],
                            'S' => $list['amount']
                    	);
                        //Print third and the rest of the users
                    	foreach (range('A', 'S') as $key => $columnID) {
                    		$sheet->setCellValue(sprintf('%s%d', $columnID, $row_num), $values[$columnID]);
                    	}
	                else:
	                	//Print titles on row 1
	                	$columnID = 'A';
	                    foreach ($title_list as $rowkey => $row) {
                            $sheet->getStyle(sprintf('%s%d', $columnID, $row_num))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	                        $sheet->getStyle(sprintf('%s%d', $columnID, $row_num))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	                        $sheet->setCellValue(sprintf('%s%d', $columnID, $row_num), $row);
	                        $columnID++;
	                    }
	                    
	                    $row_num++;
	                    //Build values
                        $values = array(
                        	'A' => sprintf('%s %s', $list['f_name'], $list['l_name']),
                        	'B' => '',
                        	'C' => '',
                        	'D' => $list['f_name'],
                        	'E' => '',
                        	'F' => $list['l_name'],
                        	'G' => $list['phone_no'], 
                        	'H' => '',
                        	'I' => '',
                        	'J' => $list['email'],
                        	'K' => $list['address'],
                        	'L' => $list['district'],
                        	'M' => $list['state'],
                        	'N' => $list['country'],
                        	'O' => '',
                            'P' => $list['id'],
                        	'Q' => $list['pay_mode'],
                        	'R' => $list['request_date'],
                        	'S' => $list['amount']
                    	);
                        //Print second row user
                    	foreach (range('A', 'S') as $key => $columnID) {
                            $sheet->getStyle(sprintf('%s%d', $columnID, $row_num))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                    		$sheet->setCellValue(sprintf('%s%d', $columnID, $row_num), $values[$columnID]);
                    	}
	                endif;
	                $row_num++;
	            }

	            ob_clean(); 
	            header('Content-Type: application/vnd.ms-excel');
	            header(sprintf('Content-Disposition: attachment;filename="%s.xls"', 'Pending Payments'));
	            header('Cache-Control: max-age=0');
	            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	            $objWriter->save('php://output');
	        endif;
	    endif;
        die();
    }
}
new Ajax_Export_Pending_Payments;