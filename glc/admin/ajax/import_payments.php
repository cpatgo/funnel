<?php
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 300);
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

class Ajax_Import_Payments {
    public function __construct()
    {
        $this->import();
        printf('<script type="text/javascript">window.location="%s/glc/admin/index.php?page=mass_payment_import&import=1";</script>', GLC_URL);
    }

    public function import($username = array())
    {
        require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
        require_once(dirname(dirname(dirname(__FILE__))).'/class/PHPExcel.php');

        $payments_class = getInstance('Class_Payment');

        if(isset($_FILES['csv_file']) && is_uploaded_file($_FILES['csv_file']['tmp_name'])):

            //upload directory
            $upload_dir = sprintf('%s/documents/', dirname(dirname(dirname(__FILE__))));
            //create file name
            $file_path = $upload_dir . $_FILES['csv_file']['name'];
            //move uploaded file to upload dir
            if (!@move_uploaded_file($_FILES['csv_file']['tmp_name'], $file_path)) {
                //error moving upload file
                echo "Error moving file upload";
                die();
            }

            try {
                $inputFileType = PHPExcel_IOFactory::identify($file_path);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($file_path);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($file_path,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            //  Get worksheet dimensions
            $sheet = $objPHPExcel->getSheet(0); 
            $highestRow = $sheet->getHighestRow(); 
            $highestColumn = $sheet->getHighestColumn();

            //  Loop through each row of the worksheet in turn
            for ($row = 2; $row <= $highestRow; $row++){ 
                // Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $payments_class->update_paid_unpaid_user($rowData);
            }
            //delete csv file
            unlink($file_path);
        endif;
    }
}
new Ajax_Import_Payments;