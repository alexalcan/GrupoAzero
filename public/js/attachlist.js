

function AttachList(sectionRel){
    var listHref = $(".attachList[rel='"+sectionRel+"']").attr("href");
	if(typeof(listHref)=="undefined"){console.log("No List Href para  "+sectionRel); return false;}

$(".attachList[rel='"+sectionRel+"']").html("Cargando...");

    $.ajax({
        url:listHref,
		success:function(h){
		    $(".attachList[rel='"+sectionRel+"']").replaceWith(h);
		    setTimeout(function(){ActivaAttachList(sectionRel);},10);
			}
        });
}

function ActivaAttachList(sectionRel){
//console.log($(".attachList[rel='"+sectionRel+"'] .MyAttAdder").length);
    $(".attachList[rel='"+sectionRel+"'] .MyAttAdder").click(function(e){
	e.preventDefault();
	console.log("Cilicked button");
    	var inp = $(this).closest(".attachList").find("[name='attachUpload']").eq(0);
    	//console.log(inp);
        AttSubeArchivo(inp);
        });  

    $(".attachList[rel='"+sectionRel+"'] .delatt").click(function(e){
		e.preventDefault();

		$(this).closest(".attachitem").addClass("deletable");
		var esteob = this;
		setTimeout(function(){
			EliminaAtt(esteob, sectionRel); 
			},50);
		});
		
	
	$("[name='attachUpload']").change(function(){
	//var inp = $(this).closest(".attachList").find("[name='attachUpload']").eq(0);
    	//console.log(inp);
        AttSubeArchivo($(this));	
	});	
	
		
console.log("Activado "+sectionRel);
}


function EliminaAtt(esteob, sectionRel){
	var title = $(esteob).attr("title");
	   
	if(!confirm(title)){
	    $(esteob).closest(".attachitem").removeClass("deletable");
		return false;
		}
	
	$(esteob).css("opacity",0);
	
	var  href = $(esteob).attr("href");
	console.log(href);	
	$.ajax({
		url:href,
		dataType:"json",
		error:function(err){
			alert(err.statusText);
			$(esteob).closest(".attachitem").removeClass("deletable");
			$(".attachList[rel='"+sectionRel+"'] .attachMonitor").text("");
			},
		beforeSend:function(){
		    $(".attachList[rel='"+sectionRel+"'] .attachMonitor").text("Espere...");
			},
		success:function(json){
				if(json.status == 1){
		
				    AttachList(sectionRel);
					}
				else{
				    $(".attachList[rel='"+sectionRel+"'] .attachMonitor").text("Espere...");
					}
			}
		});
	
    }
    



function AttSubeArchivo(ob){
    //console.log(ob);
var section = $(ob).closest(".attachList");
let uploadto = $(section).attr("uploadto");
let token = $(section).find("[name='_token']").val();
let order_id = $(section).find("[name='order_id']").val();
let partial_id = $(section).find("[name='partial_id']").val();
let listRel = $(section).attr("rel");
var fdata = new FormData();
	if(typeof(ob)=="undefined"){
		alert("No se encuentra el campo");
		return;
		}
	if(typeof(ob[0])=="undefined"){
		alert("Por favor elige un archivo.");
		return;
		}
	if(typeof(ob[0].files)=="undefined"){
		alert("Sin objeto Files en campo.");
		return;
		}
	if(typeof(ob[0].files[0])=="undefined"){
		alert("Por favor elige un archivo.");
		return;
		}


//const thisFile = ob;
fdata.append("_token", token );
fdata.append("upload",ob[0].files[0],ob.name);
//fdata.append("rel",listRel);

$(section).find(":hidden.param").each(function(){
    fdata.append($(this).prop("name"),$(this).val());
});

        $.ajax({
         url: uploadto,
         type: "POST",
         data:  fdata,
         dataType:"json",
         contentType: false,
         cache: false,
         processData:false,
         beforeSend : function()
         {
             $(ob).hide();
             $(section).find(".attachMonitor").text("Espere...");
         },
         success: function(json)
            {
				if(json.status == 1){
				    AttachList( listRel );
					}
				else{alert(json.error);}
            },
           error: function(err) 
            {
               $(section).find(".attachMonitor").text("");
         		alert(err.statusText);
            }          
          });
    
    
}


function MiModal(html,callback){
	if(typeof(callback)=="undefined"){callback=null;}
	
	if($(".mimodal").length>0){$(".mimodal").remove();}
	
var mm="<div class='mimodalBack'><div class='mimodal'>"+html+"</div></div>";
$("body").append(mm);	
}
function MiModalCerrar(){
$(".mimodalBack").remove();
	
}


