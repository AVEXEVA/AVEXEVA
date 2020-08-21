function action(link, action){
	var formData = new FormData($('form#' + $(link).attr('form'))[0]);
	if(isEntity(formData)){
    formData.append('Action', action);
		$.ajax({
			url:'index.php',
			method:'POST',
			data: formData,
			beforeSend:function(){saveState(link);},
			success:function(code){
				if(code == 0){failedSave(link);}
				else{successfulSave(link, formData);}
			},
			error:function(){failedSave(link);}
		});
	}
}
