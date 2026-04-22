<style>
    #pac-input { z-index: 2; padding: 8px; border: 1px solid #585858; border-radius: 3px; background-color: #fff; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); font-size: 16px; margin: 10px; width: 199px; margin-left: -3px; }
    #current-location-btn{ right: initial !important; left: 400px; border: 1px solid #585858; border-radius: 3px; font-weight: 500; background-color: #fff; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); font-size: 16px; cursor: pointer; margin: 10px; color:rgb(0, 0, 0)}
    #map {   height: 700px; width: 100%;}
    /*@media only screen and (max-width: 600px) {*/
    /*    #pac-input {position: absolute;  top: 10px; left: 200px !important; z-index: 2;  padding: 10px; border: 1px solid #585858; border-radius: 4px; background-color: #fff; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);  font-size: 16px;  margin: 10px;  width: 150px; }*/
    /*    #current-location-btn{   position: absolute; top: 10px; left:0; z-index: 2; padding: 10px; border: 1px solid #585858; border-radius: 4px; background-color: #fff; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); font-size: 16px; cursor: pointer;  margin-top: 70px;  margin-right: 14%; }*/
    /*    .gm-fullscreen-control{ top: 60px !important; }*/
    /*}*/
    /* Hide Map/Satellite toggle on mobile */
    /*@media (max-width: 768px) {*/
    /*    .gm-style-mtc {*/
    /*        display: none !important;*/
    /*    }*/
    /*}*/
    
    @media only screen and (max-width: 768px) {

    #pac-input {
        position: absolute;
        top: 10px !important;
        right: 10px !important;
        left: auto !important;
        z-index: 2;
        padding: 8px 10px;
        border: 1px solid #585858;
        border-radius: 4px;
        background-color: #fff;
        box-shadow: 0 2px 2px rgba(0,0,0,0.1);
        font-size: 13px;
        margin: 0;
        width: 130px;
        box-sizing: border-box;
    }

    #current-location-btn {
        position: absolute;
        top: 10px !important;
        right: 150px !important;
        left: auto !important;
        z-index: 2;
        padding: 8px 10px;
        border: 1px solid #585858;
        border-radius: 4px;
        background-color: #fff;
        box-shadow: 0 2px 2px rgba(0,0,0,0.1);
        font-size: 13px;
        cursor: pointer;
        margin: 0;
        width: 130px;
        text-align: center;
        white-space: nowrap;     /* ← stops text wrapping to 2 lines */
        overflow: hidden;
        box-sizing: border-box;
    }

    .gm-fullscreen-control {
        top: 55px !important;
    }

    .gm-style-mtc {
        display: none !important;
    }

    #map {
        height: 450px;
    }
}
</style> 

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgxRQ7ftLwTNM6HNGTFi8fRErhOGyB2Fs&libraries=places,geometry&callback=initMap"></script>  
<script>
        var singapore = { lat: 1.2833377739091532, lng: 103.77864361023468 };
        var map;
        var marker = null;
        var directionsDisplay;

        function calculateDistanceAndTime(clickedLocation, destination) {
                    var service = new google.maps.DistanceMatrixService();
                    service.getDistanceMatrix({
                        origins: [clickedLocation],
                        destinations: [destination],
                        travelMode: 'DRIVING',
                        unitSystem: google.maps.UnitSystem.METRIC,
                        avoidHighways: false,
                        avoidTolls: false,
                        drivingOptions: {
                            departureTime: new Date(Date.now() + 8 * 60 * 60 * 1000),
                            trafficModel: 'bestguess'
                        }
                    }, function(response, status) {
                        if (status == 'OK') {
                            var distance = response.rows[0].elements[0].distance.text;
                            var duration = response.rows[0].elements[0].duration.text;
                            var content = "Distance: " + distance + "<br>Travel time: " + duration;
                            var infowindow = new google.maps.InfoWindow({
                                content: content
                            });
                            infowindow.open(map, marker);
                        } else {
                            console.error('Error:', status);
                        }
                    });

                    var directionsService = new google.maps.DirectionsService();
                    directionsDisplay = new google.maps.DirectionsRenderer();
                    directionsDisplay.setOptions({
                        polylineOptions: {
                            strokeColor: '#003A5A',
                            strokeOpacity: 1,
                            strokeWeight: 7
                        }
                    });
                    directionsDisplay.setMap(map);

                    var request = {
                        origin: clickedLocation,
                        destination: destination,
                        travelMode: 'DRIVING'
                    };
                    directionsService.route(request, function(result, status) {
                        if (status == 'OK') {
                            directionsDisplay.setDirections(result);
                        } else {
                            console.error('Directions request failed due to ' + status);
                        }
                    });
                }

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: singapore,
                zoom: 16,
                styles: [{"elementType":"geometry","stylers":[{"hue":"#ff4400"},{"saturation":-68},{"lightness":-4},{"gamma":0.72}]},{"featureType":"road","elementType":"labels.icon"},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"hue":"#0077ff"},{"gamma":3.1}]},{"featureType":"water","stylers":[{"hue":"#00ccff"},{"gamma":0.44},{"saturation":-33}]},{"featureType":"poi.park","stylers":[{"hue":"#44ff00"},{"saturation":-23}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"hue":"#007fff"},{"gamma":0.77},{"saturation":65},{"lightness":99}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"gamma":0.11},{"weight":5.6},{"saturation":99},{"hue":"#0091ff"},{"lightness":-86}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"lightness":-48},{"hue":"#ff5e00"},{"gamma":1.2},{"saturation":-23}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"saturation":-64},{"hue":"#ff9100"},{"lightness":16},{"gamma":0.47},{"weight":2.7}]}]
            });

            var spgsIcon = {
                url: 'https://meraki-education.com/spgs/wp-content/uploads/2026/01/SPGS-Singapore-Logo-1-1.png',
                scaledSize: new google.maps.Size(50, 50)
            };

            var spgsMarker = new google.maps.Marker({
                position: singapore,
                map: map,
                icon: spgsIcon
            });

            var input = document.getElementById('pac-input');
            input.style.minHeight = '40px';
            input.style.height = '40px';
            input.style.left = '203px';
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var currentLocationButton = document.createElement('button');
            currentLocationButton.textContent = 'Current Location';
            currentLocationButton.id = 'current-location-btn';
            
            currentLocationButton.style.minHeight = '40px';
            currentLocationButton.style.height = '40px';

            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(currentLocationButton);

            currentLocationButton.addEventListener('click', function(){
                if (marker) {
                    marker.setMap(null);
                }
                
                if (directionsDisplay) {
                    directionsDisplay.setMap(null);
                }
                
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var currentLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
        
                        marker = new google.maps.Marker({
                            position: currentLocation,
                            map: map
                        });
        
                        calculateDistanceAndTime(currentLocation, singapore);
                    }, function() {
                        alert('Error: The Geolocation service failed.');
                    });
                } else {
                    alert('Error: Your browser doesn\'t support geolocation.');
                }
            });

            map.addListener('click', function(event) {
                var clickedLocation = event.latLng;

                if (marker) {
                    marker.setMap(null);
                }
                if (directionsDisplay) {
                    directionsDisplay.setMap(null);
                }

                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map
                });

                calculateDistanceAndTime(clickedLocation, singapore);

                input.value = ''; // Clear the search box value
            });

            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                var selectedLocation = places[0].geometry.location;

                if (marker) {
                    marker.setMap(null);
                }
                
                if (directionsDisplay) {
                    directionsDisplay.setMap(null);
                }

                marker = new google.maps.Marker({
                    position: selectedLocation,
                    map: map
                });

                calculateDistanceAndTime(selectedLocation, singapore);

                input.value = ''; // Clear the search box value
            });

            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });
        }

    // window.addEventListener('load', initMap);
</script>