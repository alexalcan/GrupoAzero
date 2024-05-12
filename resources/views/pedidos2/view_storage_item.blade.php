<?php
if(!empty($path)){

    $info = pathinfo($path);
    $ext = isset($info["extension"]) ? $info["extension"] : "pdf";

    if(in_array($ext,["jpg","jpeg","png","gif"])){
        echo "<a href='".asset('storage/'.$path)."' target='_blank' class='storageImg'><img src='".asset('storage/'.$path)."' /></a>";
    }
    else {
        echo "<a href='".asset('storage/'.$path)."' target='_blank' class='pdf'></a>";
    }    
}
else{

    echo "";

}


