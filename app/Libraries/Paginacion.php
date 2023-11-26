<?php
namespace App\Libraries;

/**
 * HTML paginacion class.
 *
*/
class Paginacion {

	// Enable or disable automatic setting of target="_blank"
	public static $xtotal = 0;
    public static $xactual = 1;
    public static $xrpp=3;
    public static $xclass="paginacion";
    public static $xitems="registros";
    public static $xidioma="es";
    public static $num_pags=false;
    public static $inf = 0;
    public static $sup = 0;
    public static $pag_var = "p";
    private static $calculado=false;
    
    /**
     * paginacion::total()
     * get/set
     * @param bool $num
     * @return
     */
    public static function total($num=false){
        if(is_numeric($num)){
        self::$xtotal = intval($num);
        }
    return self::$xtotal;
    } 

    /**
     * paginacion::actual()
     * get/set pagina actual
     * @param bool $num
     * @return
     */
    public static function actual($num=false){
        if(is_numeric($num)&&!empty($num)){
        self::$xactual = intval($num);
        }
    return self::$xactual;
    } 
        
    /**
     * paginacion::rpp()
     * get/set registros por pagina
     * @param bool $num
     * @return
     */
    public static function rpp($num=false){
        if(is_numeric($num)){
        self::$xrpp = intval($num);
        }
        if(self::$xrpp<1){self::$xrpp=1;}
    return self::$xrpp;
    }  


    /**
     * paginacion::items()
     * get/set Nombre de items (plural)
     * @param string $str
     * @return
     */
    public static function items($str=""){
        if(is_string($str)&&!empty($str)){
        self::$xitems = $str;
        }
    return self::$xitems;
    } 
    
	
    public static function idioma($str=false){
        if(is_string($str)&&!empty($str)){
        self::$xidioma = $str;
        }
    return self::$xidioma;
    }     
        
    
    /**
     * paginacion::render()
     * Genera html paginador según estilo iconos, largo,multi
     * @param string $tipo
     * @return string
     */
    public static function render($uri = "",$tipo="iconos"){
        if(self::$calculado==false){self::calc();}
        if($tipo=="largo"){
        return self::render_largo($uri,$tipo);
        }
        if($tipo=="iconos"){
        return self::render_iconos($uri,$tipo);
        }  
        if($tipo=="multi"){
        return self::render_multinum($uri);
        }  
    }
    
    
    /**
     * paginacion::calc()
     * Calcula # Inicial y Final de una lista
     * @return void
     */
    public static function calc(){
    self::$num_pags = ceil(self::$xtotal/self::$xrpp);
    self::$inf = (self::$xactual>1)?((self::$xactual-1)*self::$xrpp)+1:1;
    	if(self::$xactual<self::$num_pags){
    	self::$sup =self::$xactual*self::$xrpp;	
    	}else{
    		if(self::$xtotal%self::$xrpp==0){
    		self::$sup =self::$xactual*self::$xrpp;		
    		}else{
    		self::$sup = ((self::$xactual-1)*self::$xrpp)+(self::$xtotal%self::$xrpp);	
    		}    		
    	}
    self::$calculado=true;
    }
    
    
    
    /**
     * paginacion::paginar()
     * Devuelve sólo elementos de la página.
     * @param array 2D $res
     * @uses total() rpp()
     * @return array 2D
     */
    public function paginar($res=array()){
   		if(self::$num_pags==false){self::calc();}
	 return array_slice($res,(self::$inf-1),self::$xrpp,true);
    }
    
    
    /**
     * paginacion::pag_var()
     * 
     * @param bool $nombre
     * @return
     */
    public function pag_var($nombre=false){
        if($nombre!==false){self::$pag_var=$nombre;}
    return self::$pag_var;
    }
    
    
    /**
     * paginacion::render_largo()
     * 
     * @return string
     */
    public static function render_largo($uri="index.php?"){
    
    	switch(self::$xidioma){ 
    	   
    		case "en":
			$de="of";
			$a="to";
			$primero="First";
			$anterior="Previous";
			$siguiente="Next";
			$ultimo="Last";
			break;
            
    		default: 
			$de="de"; 
			$a="a";
			$primero="Primero";
			$anterior="Anterior";
			$siguiente="Siguiente";
			$ultimo="Último";
			break;
            
    	}
     $inicianext = (strpos($uri,"?")!==false)?"&":"?";    

    $h="<ul class='".self::$xclass."'>";
    $h.="<li>";
        if(self::$xactual>1){
        $h.="<a href='{$uri}".$inicianext.self::$pag_var."=1' title='$primero' rel='1'>|&lt;</a>";
        }else{
        $h.="|&lt;";
        }
    $h.="</li>";
    
    $h.="<li>";
        if(self::$xactual>1){
        $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".(self::$xactual-1)."' title='$anterior' rel='".(self::$xactual-1)."'>&lt;</a>";
        }else{
        $h.="&lt;";
        }
    $h.="</li>";
    
    $h.="<li>";
    $h.="<span>".self::$inf." $a ".self::$sup." $de ".number_format(self::$xtotal,0)." ".self::$xitems."</span>";    
    $h.="</li>";
        
        
    $h.="<li>";
        if(self::$xactual<self::$num_pags){
        $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".(self::$xactual+1)."' title='$siguiente' rel='".(self::$xactual+1)."'>&gt;</a>";
        }else{
        $h.="&gt;";
        }
    $h.="</li>";        
        
    $h.="<li>";
        if(self::$xactual<self::$num_pags){
        $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".self::$num_pags."' title='$ultimo' rel='".self::$num_pags."'>&gt;|</a>";
        }else{
        $h.="&gt;|";
        }
    $h.="</li>";
    $h.="</ul>";
    return $h;
    }
    
    
    
    
    
    public static function render_multinum($uri="index.php?"){        
      
        $mu = 5; //numero de multinumeros
            $de="de";
            $a="a";
            $primero="Primero";
            $anterior="Anterior";
            $siguiente="Siguiente";
            $ultimo="Último";
          
            
        $inicianext = (strpos($uri,"?")!==false)?"&":"?";
        
        $h="<ul class='".self::$xclass."'>";
        $h.="<li>";
        if(self::$xactual>1){
            $h.="<a href='{$uri}".$inicianext.self::$pag_var."=1' title='$primero' rel='1'>|&lt;</a>";
        }else{
            $h.="|&lt;";
        }
        $h.="</li>";
        
        $h.="<li>";
        if(self::$xactual>1){
            $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".(self::$xactual-1)."' title='$anterior' rel='".(self::$xactual-1)."'>&lt;</a>";
        }else{
            $h.="&lt;";
        }
        $h.="</li>";
        
        //***********************   Paginas
        $a = self::$xactual;
        $emax = (($a + $mu) <=self::$xtotal) ? ($a + $mu) : self::$xtotal;        
        
        $h.="<li class='steps'>";
        $h.="<span>".self::$inf." $a ".self::$sup." $de ".number_format(self::$xtotal,0)." ".self::$xitems."</span>";
        for($i=$a; $i<=$emax;$i++){
            $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".($i)."' title='$i' rel='".$i."' > $i </a>";
        }
        //$h.="<span>".self::$inf." $a ".self::$sup." $de ".number_format(self::$xtotal,0)." ".self::$xitems."</span>";
        $h.="</li>";
        
        
        $h.="<li>";
        if(self::$xactual<self::$num_pags){
            $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".(self::$xactual+1)."' title='$siguiente' rel='".(self::$xactual+1)."'>&gt;</a>";
        }else{
            $h.="&gt;";
        }
        $h.="</li>";
        
        $h.="<li>";
        if(self::$xactual<self::$num_pags){
            $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".self::$num_pags."' title='$ultimo' rel='".self::$num_pags."'>&gt;|</a>";
        }else{
            $h.="&gt;|";
        }
        $h.="</li>";
        $h.="</ul>";
        return $h;
    }
    
    
    
    
    
    public static function render_iconos($uri="index.php?"){
    
    	switch(self::$xidioma){ 
    	   
    		case "en":
			$de="of";
			$a="to";
			$primero="First";
			$anterior="Previous";
			$siguiente="Next";
			$ultimo="Last";
			break;
            
    		default: 
			$de="de"; 
			$a="a";
			$primero="Primero";
			$anterior="Anterior";
			$siguiente="Siguiente";
			$ultimo="Último";
			break;
            
    	}
     $inicianext = (strpos($uri,"?")!==false)?"&":"?";    

    $h="<ul class='".self::$xclass."'>";
    $h.="<li>";
        if(self::$xactual>1){
        $h.="<a href='{$uri}".$inicianext.self::$pag_var."=1' title='$primero' class='first' rel='1'></a>";
        }else{
        $h.=" ";
        }
    $h.="</li>";
    
    $h.="<li>";
        if(self::$xactual>1){
        $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".(self::$xactual-1)."' title='$anterior' class='prev' rel='".(self::$xactual-1)."'></a>";
        }else{
        $h.=" ";
        }
    $h.="</li>";
    
    $h.="<li>";
    $h.="<span>".self::$inf." $a ".self::$sup." $de ".self::$xtotal." ".self::$xitems."</span>";    
    $h.="</li>";
        
        
    $h.="<li>";
        if(self::$xactual<self::$num_pags){
        $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".(self::$xactual+1)."' title='$siguiente' class='next' rel='".(self::$xactual+1)."'></a>";
        }else{
        $h.=" ";
        }
    $h.="</li>";        
        
    $h.="<li>";
        if(self::$xactual<self::$num_pags){
        $h.="<a href='{$uri}".$inicianext.self::$pag_var."=".self::$num_pags."' title='$ultimo' class='last' rel='".self::$num_pags."'></a>";
        }else{
        $h.=" ";
        }
    $h.="</li>";
    $h.="</ul>";
    return $h;
    }
    
   
}