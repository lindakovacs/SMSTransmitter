$(document).ready(function(){
	
	$("#sending").hide();
	$("button[type=submit]").on("click",function(e)
	{
		e.preventDefault();

		$("#sending").show();
		var data = {'recipient':$("#recipient").val(),'originator':$("#originator").val(),'body':$("#body").val()}
		$.ajax(
		{
			method: $("form").attr("method"),
			url:$("form").attr("action"),
			data:data,
			success: function( data, textStatus, jqXHR)
			{
		        $("#response").attr("style","color:#0f0");
		        $("#response").text(JSON.stringify(data));
		        $("#sending").hide();
		        $("form").trigger("reset");
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
		        $("#response").attr("style","color:#f00");
		        $("#response").text(jqXHR.responseText);
		        $("#sending").hide();
			}

		});
	});
});