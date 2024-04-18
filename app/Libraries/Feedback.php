<?php 
namespace App\Libraries;


class Feedback {
protected static $errors= [];
protected static $messages= [];
protected static $comments= [];
protected static $value="";
protected static $customs = [];

 

	public static function error($text=""){
	feedback::$errors[]=$text;    
	return true;
	}


	public static function message($text=""){
	feedback::$messages[]=$text;
	return true;
	}
	
	
	public static function comment($text=""){
	feedback::$comments[]=$text;
	return true;
	}	
    
	public static function customs($v=false){
	   if($v!==false){feedback::$customs=$v;}
	return feedback::$customs;
	}
    
    public static function custom($key,$value){
    feedback::$customs[$key]=$value;
    }
    
    
    
    /**
     * feedback::errors()
     * Returns just errors
     * @param string $format
     * @param string $separador
     * @return
     */
    public static function errors($format="string",$separador=","){
        if($format=="string"){return implode($separador,self::$errors);}
        else{
        return self::$errors;
        }
    }

    /**
     * feedback::messages()
     * Returns just messages in given format
     * @param string $format
     * @param string $separator
     * @return
     */
    public static function messages($format="string",$separator=","){
        if($format=="string"){return implode($separator,self::$messages);}
        else{
        return self::$messages;
        }
    }


	/**
	 * feedback_Core::value()
	 * Sets a value 
	 * @param string $text
	 * @return
	 */
	public static function value($text=false){
	   if($text===false){
        return feedback::$value;
	   }
	feedback::$value=$text;
	return true;
	}	
	

	public static function count($type="all"){
		if($type=="messages"){return count(feedback::$messages);}
		elseif($type=="errors"){return count(feedback::$errors);}
		elseif($type=="comments"){return count(feedback::$comments);}
		else {return count(feedback::$errors)+count(feedback::$messages)+count(feedback::$comments);}
	}
	
 
	 
	/**
	 * feedback_Core::output()
	 * 
	 * @param string $type any|messages|errors|comments
	 * @param string $format array|object|string|xml
	 * @return
	 */
	public static function output($type="any",$format="array"){
		if($type=="any"){		
			if($format=="array"){
			$ret = array("errors"=>feedback::$errors,"messages"=>feedback::$messages,"comments"=>feedback::$comments);
			return 	$ret;
			}
			elseif($format=="object"){
			$ob=(object)[];
			$ob->errors=feedback::object(feedback::$errors);
			$ob->messages=feedback::object(feedback::$messages);
			$ob->comments=feedback::object(feedback::$comments);
			return $ob;
			}
			elseif($format=="xml"){
			$x = new \SimpleXMLElement("<feedback>");
			$xe =$x->addChild("errors");$xm =$x->addChild("messages");$xc =$x->addChild("comments");
				foreach(feedback::$errors as $e){$xe->addChild("item",$e);}
				foreach(feedback::$messages as $m){$xm->addChild("item",$m);}
				foreach(feedback::$comments as $c){$xc->addChild("item",$c);}
			return $x->asXML();
			}
			elseif($format=="string"){
			$s=implode(" ",feedback::$errors).implode(" ",feedback::$messages).implode(" ",feedback::$comments);
			return 	$s;
			}
		}
		else if($type=="messages"){
			if($format=="string"){return feedback::string(feedback::$messages);}
			elseif($format=="array"){return feedback::$messages;}
			elseif($format=="object"){return feedback::object(feedback::$messages);}
			elseif($format=="xml"){return feedback::xml(feedback::$messages);}
		}
		else if($type=="errors"){
			if($format=="string"){return feedback::string(feedback::$errors);}
			elseif($format=="array"){return feedback::$errors;}
			elseif($format=="object"){return feedback::object(feedback::$errors);}
			elseif($format=="xml"){return feedback::xml(feedback::$errors);}
		}
		else if($type=="comments"){
			if($format=="string"){return feedback::string(feedback::$comments);}
			elseif($format=="array"){return feedback::$comments;}
			elseif($format=="object"){return feedback::object(feedback::$comments);}
			elseif($format=="xml"){return feedback::xml(feedback::$comments);}
		}else if($type=="value"){
        return feedback::$value;
		}
	}
	



	private static function string($arr=array()){
	return implode(" ",$arr);
	}
	
	private static function object($arr=array()){
	$ob = (object)[];
		foreach($arr as $k=>$a){
		$ob->{$k}=$a;	
		}
	return $ob;
	}
	
	private static function xml($arr=array()){
	$x = new \SimpleXMLElement("<feedback>");
		foreach($arr as $a){$x->addChild("item",$a);}
	return $x->asXML();
	}	
	
	
	/**
	 * feedback_Core::json_service()
	 * Echoes json response and dies!
	 * @param integer $status 1|0 or other integers!
	 * @return void
	 * @tutorial response: {status:"1",messages:"string",errors:"string",comments:"string"}
	 */
	public static function json_service($status=1,$comments=false){
	$ob=new \StdClass;
	$ob->status=$status;
	$ob->messages=feedback::string(feedback::$messages);
	$ob->errors=implode(" ",feedback::$errors);
		if($comments==true){
		$ob->comments=feedback::string(feedback::$comments);	
		}	
	$ob->value = feedback::$value;

    //Custom vars
		if(!empty(self::$customs)){
			foreach((array)self::$customs as $k=>$v){
				$ob->{$k}=$v;
				}
		}

 

	header("Content-type:text/plain");
	echo json_encode($ob);

	die();
	}
	public static function j(int $status=1, $comments = false){
		self::json_service($status,$comments);
	}
	
	/**
	 * Dumps messages, errors, comments
	 */
	public static function dump(){
	var_dump(feedback::$messages);
	var_dump(feedback::$errors);
	var_dump(feedback::$comments);
	}
	
	
	
}?>