<?php

if($_FILES["file"]["tmp_name"]){
    list($file_name,$file_type) = explode(".",$_FILES['file']['name']);
    if(!$ext = array_search(
        mime_content_type($_FILES['file']['tmp_name']),
        array(
            'gif' => 'image/gif',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
        ), true)
    ){
        return "mime error";
    }
    
    date_default_timezone_set('Asia/Tokyo');
    $name = date("YmdHis").".".$file_type;
    $file = "./imgs";
    

    if (move_uploaded_file($_FILES['file']['tmp_name'], $file."/".$name)) {
        chmod($file."/".$name, 0644);
    }

    echo $name;
}else{
    return var_dump($_FILES);
}
