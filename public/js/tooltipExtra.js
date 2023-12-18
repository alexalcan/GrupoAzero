

TTE_TIME=0;
TTE_CERRABLE=false;
TTE_ABRIENDO=false;
    $(document).ready(function(){
        $(".toolTipExtra").hide();
	
        $(".tte").on("click",function(e){
			e.preventDefault();
            e.stopPropagation();
            if(TTE_ABRIENDO){return;}
			if(TTE_CERRABLE){
				$(".toolTipExtra:visible").slideUp(40,TteSetNoCerrable);
				return;
			}
            TTE_ABRIENDO=true;
			$(this).find(".toolTipExtra").slideDown(100,TteSetCerrable);
	
            });

        $(".toolTipExtra").on("mouseleave",function(e){
            e.stopPropagation();
            if(TTE_CERRABLE==false){return;}
			
			
			$(this).slideUp(100,TteSetNoCerrable);
			
			
            });
        
        });


    function TteSetCerrable(){
        TTE_CERRABLE=true;
        TTE_ABRIENDO=false;
        console.log("CERRABLE");
        }
    function TteSetNoCerrable(){
        TTE_CERRABLE=false;
        }
    
