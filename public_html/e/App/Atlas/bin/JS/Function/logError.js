function logError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      console.log("You denied the request for Geolocation. You will not be able to start, edit or complete tickets until you enable it. Please reset your history to enable location services.");
      break;
    case error.POSITION_UNAVAILABLE:
      console.log("Location information is unavailable. You will not be able to start, edit or complete tickets until your location information is enabled.");
      break;
    case error.TIMEOUT:
      console.log("The location permission has timed out. Please click again and click enable.");
      break;
    case error.UNKNOWN_ERROR:
      console.log("An unknown error occurred. Please contact the ITHelpDesk@NouveauElevator.com");
      break;
    default:
      console.log('Ignore. Beta Test 5')
  }
}