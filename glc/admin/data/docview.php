<?php
//require_once("../../config.php");
/*** some basic sanity checks ***/
if(filter_has_var(INPUT_GET, "image_id") !== false && filter_input(INPUT_GET, 'image_id', FILTER_VALIDATE_INT) !== false)
    {
    /*** assign the image id ***/
    $image_id = filter_input(INPUT_GET, "image_id", FILTER_SANITIZE_NUMBER_INT);
    try     {
		/*** The sql statement ***/
        $sql = "SELECT image, image_type FROM documents WHERE image_id= '$image_id'";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$array 	= mysqli_fetch_array($query);
		//var_dump($array);
        /*** check we have a single image and type ***/
		//var_dump(sizeof($array));
        if(sizeof($array) == 4)
            {
            /*** set the headers and display the image ***/
            header("Content-type: ".$array['image_type']);

            /*** output the image ***/
			echo '<img src="data:image/jpeg;base64,'.base64_encode( $array['image'] ).'"/>';
            //echo $array['image'];
            }
        else
            {
            throw new Exception("Out of bounds Error");
            }
        }
    catch(PDOException $e)
        {
        echo $e->getMessage();
        }
    catch(Exception $e)
        {
        echo $e->getMessage();
        }
        }
  else
        {
        echo 'Please use a real id number';
        }
?>