<?php
try
{
    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    $result = array();

    if (!in_array($User["permission_id"], array(1, 2)))
    {
        throw new Exception("Δεν έχετε δικαιώματα πρόσβασης στα δεδομένα", 500);
    }
    else
    {
        $id = $_REQUEST["id"];

        if ( ( ! ctype_digit($id) ) || ( $id == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        if ( count($_FILES) == 0) { throw new Exception("Παρακαλώ επιλέξτε πρώτα μια εικόνα", 500);  }

        $DestinationDirectory	= '../../media/owners/'; //specify upload directory ends with / (slash)

        //check if this is an ajax request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
            die();
        }

        // check $_FILES['files'] not empty
        if(!isset($_FILES['files']) || !is_uploaded_file($_FILES['files']['tmp_name'][0]))
        {
            die('Something wrong with uploaded file, something missing!'); // output error when above checks fail.
        }

        $ImageName 		= str_replace(' ','-',strtolower($_FILES['files']['name'][0])); //get image name
        $ImageSize 		= $_FILES['files']['size'][0]; // get original image size
        $TempSrc	 	= $_FILES['files']['tmp_name'][0]; // Temp name of image file stored in PHP tmp folder
        $ImageType	 	= $_FILES['files']['type'][0]; //get file type, returns "image/png", image/jpeg, text/plain etc.

        //Let's check allowed $ImageType, we use PHP SWITCH statement here
        switch(strtolower($ImageType))
        {
            case 'image/png':
                //Create a new image from file
                $CreatedImage =  imagecreatefrompng($_FILES['files']['tmp_name'][0]);
                break;
            case 'image/gif':
                $CreatedImage =  imagecreatefromgif($_FILES['files']['tmp_name'][0]);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
                $CreatedImage = imagecreatefromjpeg($_FILES['files']['tmp_name'][0]);
                break;
            default:
                throw new Exception("Παρακαλώ επιλέξτε μια εικόνα png,gif,jpeg,pjpeg", 500);
                break;
        }

        //Get file extension from Image name, this will be added after random name
        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
        $ImageExt = str_replace('.','',$ImageExt);

        //remove extension from filename
        $ImageName 		= preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName);

        $sql = "INSERT INTO owner_images SET
                    owner_id = ".$db->quote($id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        $owner_image_id = $db->lastInsertId();

        $NewImageName = $id.'-'.$owner_image_id.'.'.$ImageExt;

        $DestImageName = $DestinationDirectory.$NewImageName; // Image with destination directory

        $sql = "UPDATE owner_images SET
                    name = ".$db->quote($DestImageName)."
                WHERE owner_image_id = ".$db->quote($owner_image_id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        file_put_contents($DestImageName, file_get_contents($TempSrc));

        $result["status"] = 200;
        $result["files"] = array(
            array("name"=>$NewImageName, "size"=>$ImageSize, "type"=>$ImageType, "url"=>$DestImageName)
        );
        $result["message"] = "Η Φωτογραφία ανέβηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
        $result["id"] = $id.":".$owner_image_id;
    }
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );
?>