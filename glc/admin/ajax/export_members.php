<?php
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 300);
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

class Ajax_Export {
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
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $user_list = $user_class->get_users_ids($_POST['export_user']);

        if(!empty($user_list)):
            $row_num = 1;
            foreach ($user_list as $listkey => $list) {
                $columnID = 'A';
                if($row_num !== 1):
                    foreach ($list as $rowkey => $row) {
                        $sheet->getStyle(sprintf('%s%d', $columnID, $row_num))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $sheet->setCellValue(sprintf('%s%d', $columnID, $row_num), $row);
                        $columnID++;
                    }
                else:
                    foreach ($list as $rowkey => $row) {
                        $sheet->getStyle(sprintf('%s%d', $columnID, $row_num))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $sheet->setCellValue(sprintf('%s%d', $columnID, $row_num), $rowkey);
                        $columnID++;
                    }
                    $columnID = 'A';
                    $row_num++;
                    foreach ($list as $rowkey => $row) {
                        $sheet->getStyle(sprintf('%s%d', $columnID, $row_num))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $sheet->setCellValue(sprintf('%s%d', $columnID, $row_num), $row);
                        $columnID++;
                    }
                endif;
                $row_num++;
            }

            ob_clean(); 
            header('Content-Type: application/vnd.ms-excel');
            header(sprintf('Content-Disposition: attachment;filename="%s.xls"', 'Users'));
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        endif;
        die();
    }
}
new Ajax_Export;