<style>
.attachlist{
border:#ccc 1px dotted;
padding:20px;

}
.attachlist ul{
display:flex;
flex-wrap:wrap;
justify-content:flex-start;
align-items:flex-start;
column-gap:18px;
row-gap:10px;
}
.attachlist .attachitem{
display:block;
width:180px;
display:grid;
grid-template-columns:1fr 30px;
list-style:none;
padding:5px 2px 5px 10px;
margin:0;

}
.attachlist .attachitem a{
text-decoration:none;
color:#444;
}
.attachlist .attachitem.deletable{
background:#eeaaaa;
}

.attachlist .atticon{
max-width:150px;
height:100px;
z-index:20;
cursor:pointer;
border:#aaa 3px double;
box-shadow:#ddd 1px 1px 2px;
}
.attachlist .atticon.doc{
border:none;
box-shadow:none;
}
.attachlist .delspace{
text-align:left;
}

.attachlist a.delatt{
display:inline-block;
padding:4px 0px;
width:24px;
text-align:center;
font-size:12px;
background:red;
color:#fff;
border-radius:12px;
box-shadow:#666 1px 1px 2px;
text-decoration:none;
font-family:Arial,sans-serif;
font-weight:bold;

}
.attachlist a.delatt:hover{
box-shadow:#666 0px 0px 2px;
}

.attachlist .attachMonitor{
font-size:1.2rem;
color:#884444;

}
</style>

<p>Resto</p>

<!--   ***************************************************************************************** -->

<section class='attachlist' rel='ev' uploadto="{{ url('order/attachpost?catalog='.$urlParams['catalog']) }}" 
href="{{ url('order/attachlist?'.http_build_query($urlParams)) }}">
</section>

<!--   ***************************************************************************************** -->

<script type='text/javascript' src='{{ url("/")}}/js/app.js'></script>
<script type='text/javascript'>
$(document).ready(function(){


    AttachList("ev");
    
});

function AttachList(sectionRel){
    var listHref = $(".attachList[rel='"+sectionRel+"']").attr("href");
    $.ajax({
        url:listHref,
		success:function(h){
		    $(".attachList[rel='"+sectionRel+"']").replaceWith(h);
		    ActivaAttachList(sectionRel);
			}
        });
}

function ActivaAttachList(sectionRel){
    console.log(sectionRel);
    $(".attachList[rel='"+sectionRel+"'] .MyAttAdder").click(function(){
    	var inp = $(this).closest(".attachList").find("[name='attachUpload']").eq(0);
    	console.log(inp);
        SubeArchivo(inp);
        });  

    $(".delatt").click(function(e){
		e.preventDefault();

		$(this).closest(".attachitem").addClass("deletable");
		var esteob = this;
		setTimeout(function(){
			EliminaAtt(esteob, sectionRel); 
			},50);
		});
}


function EliminaAtt(esteob, sectionRel){
	var title = $(esteob).attr("title");
	   
	if(!confirm(title)){
	    $(esteob).closest(".attachitem").removeClass("deletable");
		return false;
		}
	
	$(esteob).css("opacity",0);
	
	var  href = $(esteob).attr("href");
		
	$.ajax({
		url:href,
		dataType:"json",
		error:function(err){
			alert(err.statusText);
			$(esteob).closest(".attachitem").removeClass("deletable");
			$(".attachMonitor").text("");
			},
		beforeSend:function(){
		    $(".attachMonitor").text("Espere...");
			},
		success:function(json){
				if(json.status == 1){
					console.log(sectionRel);
				    AttachList(sectionRel);
					}
				else{
				    $(".attachMonitor").text("Espere...");
					}
			}
		});
	
    }
    



function SubeArchivo(ob){
    //console.log(ob);
var section = $(ob).closest(".attachlist");
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
             $(".attachMonitor").text("Espere...");
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
               $(".attachMonitor").text("");
         		alert(err.statusText);
            }          
          });
    
    
}


</script>
