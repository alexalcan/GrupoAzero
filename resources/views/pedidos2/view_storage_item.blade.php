<?php
if(!empty($path)){

    $info = pathinfo($path);
    $ext = isset($info["extension"]) ? $info["extension"] : "pdf";

    if(in_array($ext,["jpg","jpeg","png","gif"])){
        echo "<a href='".asset('storage/'.$path)."' target='_blank' class='storageImg'><img src='".asset('storage/'.$path)."' /></a>";
    }
    elseif(strtolower($ext) == "pdf"){
        echo "<a class='atticon pdf' href='". asset("storage/".$path) ."' target='_blank'>";
            if (!empty($path)){
            echo "<embed src='". asset("storage/".$path) ."' alt='' style='width: 100%; height: auto;' onclick='this.parentNode.click()'></embed>";
            }
        echo "</a>";
    }
    else {
        echo "<a href='".asset('storage/'.$path)."' target='_blank' class='pdf'></a>";
    }    
}
else{

    echo "";

}


