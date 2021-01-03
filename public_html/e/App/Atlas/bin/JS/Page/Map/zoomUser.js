function zoomUser(link){
  var val = $(link).val();
  for ( i in marker ){
    if(marker[i].id == val){
      var latlng = new google.maps.LatLng(marker[i].getPosition().lat(), marker[i].getPosition().lng());
      map.setCenter(marker[i].getPosition());
      map.setZoom(15);
      if(LookUp_User != null){
        marker[LookUp_User].setIcon(pinSymbol(marker[LookUp_User]['Color']));
      }
      marker[i].setIcon(pinSymbol('black'));
      marker[i]['Color'] = 'black';
      LookUp_User = i;
    }
  }
}
