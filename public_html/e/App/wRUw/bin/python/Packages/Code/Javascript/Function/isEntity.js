function isEntity(data, table = null){
	if(table == null){
		return data.has('Table') && data.has('ID');
	} else if(data.has('Table')){
		return data.get('Table') == table && data.has('ID');
	} else {
		return false;
	}
}