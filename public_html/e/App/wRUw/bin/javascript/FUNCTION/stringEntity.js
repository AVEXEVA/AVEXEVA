function stringEntity(data){return isEntity(data) ? '&' + data.get('Table') + '&' + data.get('ID') : '';}
