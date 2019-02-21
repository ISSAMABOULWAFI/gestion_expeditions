var results = [], // contains your rows
    page = 1, // current page
    pagesize = 5; // items per page
	
	
$(document).ready(function(){
	$("#confirm").submit(function(){
		var c = confirm("Click OK to continue?");
		return c;
	});
//	$("#main1").DataTable();
	$('.stepperpositif').change(function(){
		if($(this).attr('value')<1){
			$(this).attr('value','1');
		}
		
	});
	
	$('#mailboxbtn').click(function(){
		$('#mailbox2').hide();
		$('#mailbox').show();
	});
	$('#mailbox>.header>.close').click(function(){
		$('#mailbox').hide();
	});
	$('#mailboxbtn2').click(function(){
		$('#mailbox').hide();
		$('#mailbox2').show();
	});
	$('#mailbox2>.header>.close').click(function(){
		$('#mailbox2').hide();
	});
	
	//$('.multiplemails').multiple_emails();
	
			//To render the input device to multiple email input using BootStrap icon
	$('.multiplemails').multiple_emails({position: "bottom",theme: "Basic"});
			//OR $('#example_emailBS').multiple_emails("Bootstrap");
			
	
	var form = $('#mailform');
    form.submit(function(e) {
       //$(this).submit();
	   var c = confirm("Envoi de l'Email.\nVoulez-vous continuer?");
	   if(c){
		   $('#sendConfirm').html("<div ><h2>Sending message. Please wait...</h2></div>");
		   $.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				data: $(this).serialize(),
				success : function(d){
					// check the page response
					$('#sendConfirm').html("<div><h2>"+d+"</h2></div>");
					$('#mailbox').slideUp();
				}
			});
		   
	   }
	   return false;
    });
	var form2 = $('#mailform2');
    form2.submit(function(e) {
       //$(this).submit();
	   var c = confirm("Click OK to continue?");
	   if(c){
		   $('#sendConfirm2').html("<div>Sending message. Please wait...</div>");
		   $.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				data: $(this).serialize(),
				success : function(d){
					// check the page response
					$('#sendConfirm2').html("<div>"+d+"</div>");
					//$('#mailbox2').slideUp();
				}
			});
		   
	   }
	   return false;
    });
	//alert('ok');	
	$('.jqte-test').jqte();
	$(".status").click(function()
	{
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({'status': jqteStatus});
	});
	
	$('.empty').click(function(){
		
		alert("Aucune étiquette trouvée");
	});
	
	$("#paging").on("click", "a", function() {
		page = $(this).text();
		loadPage();
	});
	init();
	
	loadPage();
    generatePagingLinks();
	
	
	
});


// function for generating paging links

function init() {
	$("#tbody tr").each(function(i, v){
        results[i]=$(this).html();
	});
	$("#tbody").children("tr").remove();
	//console.log(results.length);
	//$.each(results, function(index, val) {
    //console.log(results[0]);
	//});
}
function generatePagingLinks() {
    var links = Math.ceil(results.length / pagesize); // calculate number of links

    $("#paging").children("a").remove(); // Remove existing paging links

    // generate new paging links
    for (var i = 0; i < links; i++)
        $("<a>").attr("href", "#").addClass("btn btn-default").text(i + 1).appendTo("#paging");
}

// function for loading the entries of a single page
function loadPage() {
    // Remove existing entries
    $("#tbody").children("tr").remove();

    // Iterate through objects and generate new entries
    for (var i = (page-1)*pagesize; i < page*pagesize; i++) {
		//console.log("")
        if (!results[i])
            break;

        var row = $("<tr>").html(results[i]);
        

        $(row).appendTo('#tbody');
    }
	//var row = $("<tr>").html(results[0]);
        

    //$(row).appendTo('#tbody');
	
}
