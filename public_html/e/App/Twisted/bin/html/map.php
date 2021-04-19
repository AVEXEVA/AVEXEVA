<style>
#map {
  height: 800px;
  /* The height is 400 pixels */
  width: 80%;
  /* The width is the width of the web page */
}
</style>
<div id='map'>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB05GymhObM_JJaRCC3F4WeFn3KxIOdwEU&callback=initMap"
    type="text/javascript"></script>
<script>
function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 35.4823241, lng:-97.7593849 },
    zoom: 6,
  });
  infoWindow = new google.maps.InfoWindow();
  const locationButton = document.createElement("button");
  locationButton.textContent = "Pan to Current Location";
  locationButton.classList.add("custom-map-control-button");
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
  locationButton.addEventListener("click", () => {
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          infoWindow.setPosition(pos);
          infoWindow.setContent("Location found.");
          infoWindow.open(map);
          map.setCenter(pos);
        },
        () => {
          handleLocationError(true, infoWindow, map.getCenter());
        }
      );
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, map.getCenter());
    }
  });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
}
</script>
