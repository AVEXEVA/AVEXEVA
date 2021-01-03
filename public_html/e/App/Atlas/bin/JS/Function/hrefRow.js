function hrefRow(table,obj){
	$("#" + table + " tbody tr").each(function(){
		$(this).on('click',function(){
			document.location.href=obj + ".php?ID=" + $(this).children(":first-child").html();
      //window.open(obj+ ".php?ID=" + $(this).children(":first-child").html(),"_blank");
		});
	});
}