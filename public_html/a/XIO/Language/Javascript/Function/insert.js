function insert(link){
	var formData = new FormData($('form#' + $(link).attr('form'))[0]);
	if(isEntity(formData)){
		$.ajax({
			url:'index.php',
			method:'POST',
			data: formData,
			beforeSend:function(){
				saveState(link);
			},
			success:function(ID){
				formData.set('ID', ID);
				sucessfulSave(link, formData);
			},
			error:function(){failedSave(link);}
		});
	}
}