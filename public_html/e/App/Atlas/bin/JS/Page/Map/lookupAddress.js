function lookupAddress(address) {
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        'address': address
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          map.setZoom(18);
          if(LookUp_Address != null){LookUp_Address.setMap(null);}
          LookUp_Address = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(results[0].geometry.location.lat(),results[0].geometry.location.lng()),
            icon: {
              path:mapIcons.shapes.SQUARE_PIN,
              fillColor:'#00CCBB',
              fillOpacity:0,
              strokeColor:'black',
              strokeWeight:0
            },
            zIndex:99999999,
            id:'LookUp_Address',
            icon:flagSymbol('black')
          });
        }
    });
}
