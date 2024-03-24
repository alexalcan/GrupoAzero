const MiModal = {
width:"",
title:"",

post:null,

contHtml:"",

content:function(h){this.contHtml=h;},

showBg:function(){
    let dn = $("#dialogoBg").length;
    if(dn==0){
        $("body").append("<div id='dialogoBg'></div>");
        $("#dialogoBg").css("opacity",0);
        $("#dialogoBg").animate({opacity:.5},300);
    }    
},

show:function(directHtml){
    if(typeof(directHtml)=="undefined"){directHtml="";}
    let dbn = $("#dialogoBg").length;
    if(dbn==0){
        $("body").append("<div id='dialogoBg'></div>");
    }
    let dn = $("#dialogoWrapper").length;
    if(dn==0){        
        $("body").append("<div id='dialogoWrapper'><aside id='dialogo'><a class='botClose' onclick='MiModal.exit()'></a><div class='tit'></div><div class='content'></div></aside></div>");
    }

    $("#dialogo").hide();

    if(directHtml.length > 0){
        $("#dialogo .content").html(directHtml);
    }else{
        $("#dialogo .content").html(this.contHtml);
    }
    
    $("#dialogo .tit").text(this.title);

        if(this.width.length>0){
            $("#dialogo").css("width",this.width);
        }

    let doPost = this.post;
    $("#dialogo").slideDown(400,function(){
        if(doPost!=null){doPost();}
    });

    
},
exit:function(){    
    $("#dialogo").slideUp(400,()=>{
        $("#dialogoWrapper").remove();
        $("#dialogoBg").remove();
    });
    
}

}