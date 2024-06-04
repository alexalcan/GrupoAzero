const MiModal =  {
width:"",
title:"",

exitButton:true,

after: null,

contHtml: "",

constructor(){},

content: function(h){this.contHtml=h;},

showBg: function(){
    let dn = $("#dialogoBg").length;
    if(dn==0){
        $("body").append("<div id='dialogoBg'></div>");
        $("#dialogoBg").css("opacity",0);
        $("#dialogoBg").animate({opacity:.5},300);
    }    
},

show: function(directHtml){
    if(typeof(directHtml)=="undefined"){directHtml="";}
    let dbn = $("#dialogoBg").length;
    if(dbn==0){
        $("body").append("<div id='dialogoBg'></div>");
    }
    let dn = $("#dialogoWrapper").length;
    console.log(this.exitButton);
    if(dn > 0){        
        $("#dialogo").hide();
        $("#dialogoWrapper").remove();
    }

    var hh = "<div id='dialogoWrapper'><aside id='dialogo'>";
    hh+= (this.exitButton == true ) ? "<a class='botClose' onclick='MiModal.exit()'></a>" : "";
    hh+= (this.title.length > 0 ) ? "<div class='tit'></div>" : "" ;
    hh+= "<div class='content'></div></aside></div>";
    $("body").append(hh);

    if(directHtml.length > 0){
        $("#dialogo .content").html(directHtml);
    }else{
        $("#dialogo .content").html(this.contHtml);
    }
    
    $("#dialogo .tit").text(this.title);

        if(this.width.length>0){
            $("#dialogo").css("width",this.width);
        }
      

    $("#dialogo").slideDown(400,function(){

        if(this.after !== null){this.after();}
    });

    
},

exit : function(){    
    $("#dialogo").slideUp(400,()=>{
        $("#dialogoWrapper").remove();
        $("#dialogoBg").remove();
        $("body").trigger("MiModal-exit");
    });
    
}

}



/* @returns {boolean} True if the device is a mobile or tablet, false otherwise.
*/
function isMobileOrTablet() {
   const userAgent = navigator.userAgent.toLowerCase();
   const mobileKeywords = ["mobile", "android", "iphone", "ipad"];
   const tabletKeywords = ["tablet", "ipad"];

   // Check for mobile keywords
   for (let keyword of mobileKeywords) {
       if (userAgent.includes(keyword)) {
           return true;
       }
   }

   // Check for tablet keywords
   for (let keyword of tabletKeywords) {
       if (userAgent.includes(keyword)) {
           return true;
       }
   }

   return false;
}


function AjaxGet(href,callback, datos){
    if(typeof(datos)=="undefined"){datos = {};}
    if(typeof(callback)=="undefined"){callback=null;}

    $.ajax({
        url:href,
        method:"get",
        data:datos,
        error:function(err){alert(err.statusText);},
        success:function(h){
            if(typeof(callback)!="undefined" && callback != null){
                callback(h);
            }
        }
    });
}
function AjaxGetJson(href,callback, datos){
    if(typeof(datos)=="undefined"){datos = {};}
    if(typeof(callback)=="undefined"){callback=null;}

    $.ajax({
        url:href,
        method:"get",
        data:datos,
        dataType:"json",
        error:function(err){alert(err.statusText);},
        success:function(json){
            if(typeof(callback)!="undefined" && callback != null){
                callback(json);
            }
        }
    });
}
function AjaxPostJson(href,datos,callback){
    if(typeof(datos)=="undefined"){datos = {};}
    if(typeof(callback)=="undefined"){callback=null;}

    $.ajax({
        url:href,
        method:"post",
        data:datos,
        dataType:"json",
        error:function(err){alert(err.statusText);},
        success:function(json){
            if(typeof(callback)!="undefined" && callback != null){
                callback(json);
            }
        }
    });
}




function updateUrlParameter(url, param, value){
    var nurl = new URL(url);
    nurl.searchParams.set(param, value);
    return nurl;
}