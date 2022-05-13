function saveState(link, success = true){
	if(success == true){
		if($(link).is(":disabled")){
			$(link).html('Saved');
		}
		else {
			$(link).html('Saving');
			$(link).prop('disabled', true);
		}
	} else {
		if($(link).is("disabled") && $(link).html() == 'Saving'){
			$(link).html('Save');
			$(link).prop('disabled', false);
		}
	}
}