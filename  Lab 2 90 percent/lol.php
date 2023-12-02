
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petrol Station Map</title>
    <style>
    body {
        margin: 0;
        padding: 0;
        background-color: #000; /* Black */
        font-family: Arial, sans-serif;
    }

    #navbar {
        background-color: #00FA9A; /* Dark Text Color */
        overflow: hidden;
    }

    #navbar input[type="radio"] {
        margin-top: 5px;
        margin-right: 10px;
    }

    #map {
        height: calc(100vh - 50px); /* Full height of the viewport - navbar height */
        width: 100%;
    }

    .controls {
        margin-top: 10px;
        padding: 5px;
        width: 300px;
        background-color: #fff; /* White */
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #style-selector-control {
        background-color: #fff;
        padding: 5px;
        font-family: Arial, sans-serif;
        overflow: auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .selector-control {
        margin-right: 10px;
    }

    .custom-map-control-button {
        background-color: #fff8dc; /* Pastel Yellow */
        color: #333; /* Dark Text Color */
        padding: 12px 12px; /* Adjust padding for a smaller size */
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px; /* Adjust font size */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }

    .custom-map-control-button:hover {
        background-color: #e6be00; /* Darker Gold on Hover */
    }

    .selector-control {
        margin-right: 5px; /* Adjust margin between radio buttons */
    }

    #navbar div {
        display: flex; /* Align radio buttons horizontally */
    }

    .button-icon {
        width: 30px; /* Adjust the width as needed */
        height: 30px; /* Adjust the height as needed */
        margin-right: 5px; /* Adjust the margin between the icon and text */
    }

    /* Responsive Styles */
    @media only screen and (max-width: 600px) {
        /* Adjust styles for screens with a maximum width of 600px */
        #map {
            height: calc(100vh - 100px); /* Adjust height for smaller screens */
        }

        .controls {
            width: 100%; /* Make controls full width on smaller screens */
        }

        #navbar input[type="radio"] {
            display: block; /* Stack radio buttons on smaller screens */
            margin-bottom: 5px; /* Add space between stacked radio buttons */
        }
    }
    </style>
</head>

<body>
  <div id="navbar">
     <div>
        Hide or Show other markers in Map: 
        <input type="radio" name="show-hide" id="hide-poi" class="selector-control" />Hide</label>
        <input type="radio" name="show-hide" id="show-poi" class="selector-control" checked="checked" />
        <label for="show-poi">Show</label>
      </div>

     <input id="pac-input" class="controls" type="text" placeholder="Search Box" />

      <button id="location-button" class="custom-map-control-button">Pan to Current Location</button>

      <button id="nearby-gas-stations-button" class="custom-map-control-button">Nearby Gas Stations</button>

      <button id="find-nearby-of-searched-place-button" class="custom-map-control-button">Find Nearby Gas Stations</button>
      
      <button id="nearby-petronas-button" class="custom-map-control-button">
      <img src="Petronas.png" alt="Petronas Icon" class="button-icon">
      </button>

      <button id="nearby-petron-button" class="custom-map-control-button">
          <img src="Petron.png" alt="Petron Icon" class="button-icon">
      </button>

      <button id="nearby-shell-button" class="custom-map-control-button">
          <img src="Shell.png" alt="Shell Icon" class="button-icon">
      </button>

      <button id="nearby-caltex-button" class="custom-map-control-button">
          <img src="Caltex.png" alt="Caltex Icon" class="button-icon">
       </button>

      <button id="see-feedbacks-button" class="custom-map-control-button" onclick="location.href='seefeedbacks.php';">Recommended Gas Station</button>
      <button id="home-button" class="custom-map-control-button" onclick="location.href='dashboard.php';">Homepage</button>
  </div>
<!-- Defined class -->
  <div id="style-selector-control" class="map-control"></div>
  <div id="map"></div>
    <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWe4pLo8sr6IiXDc5ZghjekeDUL4O2FzQ&callback=initAutocomplete&libraries=places&v=weekly"
    defer>
    </script>
    <button id="give-feedback-button" class="lol"></button>
    <button id="see-feedbacks-button" class="custom-map-control-button"></button>

<script>
  let map;
  let infoWindow;
  let markers = [];
  let searchBox;
  function initAutocomplete() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 14.521405, lng: 98.945312 },
    zoom: 5,
    mapTypeId: "roadmap",
  });

  infoWindow = new google.maps.InfoWindow();

  const input = document.getElementById("pac-input");
  searchBox = new google.maps.places.SearchBox(input);


    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    map.addListener("bounds_changed", () => {
      searchBox.setBounds(map.getBounds());
    });

  

    searchBox.addListener("places_changed", () => {
      const places = searchBox.getPlaces();

      if (places.length === 0) {
        return;
      }

      markers.forEach((marker) => {
        marker.setMap(null);
      });
      markers = [];

      const bounds = new google.maps.LatLngBounds();

      places.forEach((place) => {
        if (!place.geometry || !place.geometry.location) {
          console.log("Returned place contains no geometry");
          return;
        }

        const icon = {
          url: place.icon,
          size: new google.maps.Size(71, 71),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(17, 34),
          scaledSize: new google.maps.Size(25, 25),
        };

        markers.push(
          new google.maps.Marker({
            map,
            icon,
            title: place.name,
            position: place.geometry.location,
          })
        );

        if (place.geometry.viewport) {
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });

      map.fitBounds(bounds);
    });

    const styleControl = document.getElementById("style-selector-control");

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(styleControl);

    document.getElementById("hide-poi").addEventListener("click", () => {
      map.setOptions({ styles: styles["hide"] });
    });

    document.getElementById("show-poi").addEventListener("click", () => {
      map.setOptions({ styles: styles["default"] });
    });

    const styles = {
      default: [],
      hide: [
        {
          featureType: "poi.business",
          stylers: [{ visibility: "off" }],
        },
        {
          featureType: "transit",
          elementType: "labels.icon",
          stylers: [{ visibility: "off" }],
        },
      ],
    };

    const locationButton = document.getElementById("location-button");
    locationButton.addEventListener("click", () => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude,
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent("You're Here!");
            infoWindow.open(map);
            map.setCenter(pos);
          },
          () => {
            handleLocationError(true, infoWindow, map.getCenter());
          }
        );
      } else {
        handleLocationError(false, infoWindow, map.getCenter());
      }
    });

    const nearbyGasStationsButton = document.getElementById("nearby-gas-stations-button");
    nearbyGasStationsButton.addEventListener("click", () => {
      findNearbyGasStations();
    });

     const urlParams = new URLSearchParams(window.location.search);
    const gasStationName = urlParams.get('name');
    const gasStationAddress = urlParams.get('address');

    // Center the map at the gas station location
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': gasStationAddress }, function (results, status) {
        if (status === 'OK') {
            const gasStationLocation = results[0].geometry.location;
            map.setCenter(gasStationLocation);

            // Add a marker at the gas station location
            const marker = new google.maps.Marker({
                map: map,
                position: gasStationLocation,
                title: gasStationName,
            });

            markers.push(marker); // Add the marker to the markers array

// Optionally, open an info window for the gas station
infoWindow.setContent(`<h3>${gasStationName}</h3><p>${gasStationAddress}</p>`);
infoWindow.open(map, marker);

// Set a desired zoom level, for example, 15
map.setZoom(15);
        } else {
            console.error('Geocode was not successful for the following reason:', status);
        }
    });
  }

  if (map) {
          map.addListenerOnce("tilesloaded", findNearbyGasStations);
        }
function findNearbyGasStations() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        console.log("User Location:", position.coords.latitude, position.coords.longitude);

        const userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };

        map.setCenter(userLocation);
        map.setZoom(15);

        const request = {
          location: userLocation,
          radius: 5000, // You can adjust the radius as needed
          types: ["gas_station"],
        };

        const service = new google.maps.places.PlacesService(map);

        service.nearbySearch(request, (results, status) => {
          if (status == google.maps.places.PlacesServiceStatus.OK) {
            console.log("Nearby Gas Stations:", results);
            clearMarkers();
            for (let i = 0; i < results.length; i++) {
              createMarker(results[i]);
            }
          } else {
            console.error("Nearby Search failed with status:", status);
          }
        });
      },
      (error) => {
        console.error("Error getting user location:", error);
        handleLocationError(true, infoWindow, map.getCenter());
      }
    );
  } else {
    console.error("Geolocation not supported by the browser");
    handleLocationError(false, infoWindow, map.getCenter());
  }
}

function createMarker(place) {
  const marker = new google.maps.Marker({
    map,
    position: place.geometry.location,
    title: place.name,
  });

  google.maps.event.addListener(marker, "click", () => {
    const request = {
      placeId: place.place_id, // Use the place_id to get details
      fields: ["name", "formatted_address", "rating", "opening_hours", "photos", "types", "website", "icon"],
    };

    const service = new google.maps.places.PlacesService(map);

    service.getDetails(request, (placeDetails, status) => {
      if (status == google.maps.places.PlacesServiceStatus.OK) {
        const infoContent = `
          <div>
            <h3>${placeDetails.name}</h3>
            <p><strong>Address:</strong> ${placeDetails.formatted_address}</p>
            <p><strong>Rating:</strong> ${placeDetails.rating || 'N/A'}</p>
            ${placeDetails.opening_hours ? `<p><strong>Opening Hours:</strong> ${placeDetails.opening_hours.weekday_text.join('<br>')}</p>` : ''}
            ${placeDetails.website ? `<p><strong>Website:</strong> <a href="${placeDetails.website}" target="_blank">${placeDetails.website}</a></p>` : ''}
            ${placeDetails.photos ? `<p><img src="${placeDetails.photos[0].getUrl({ maxWidth: 100, maxHeight: 100 })}" alt="Gas Station Photo"></p>` : ''}
            ${displayAmenities(placeDetails.types)}
            <button id="give-feedback-button" class="custom-map-control-button">Give Feedback</button>
            </div>
          

        `;

        infoWindow.setContent(infoContent);

          // Add an event listener for the "Give Feedback" button
          const giveFeedbackButton = document.getElementById("give-feedback-button");
          giveFeedbackButton.addEventListener("click", () => {
            console.log("Give Feedback button clicked!");
            // Redirect to the feedback page with gas station details
            const feedbackUrl = `submit_feedback.php?name=${encodeURIComponent(placeDetails.name)}&address=${encodeURIComponent(placeDetails.formatted_address)}`;
            console.log("Feedback URL:", feedbackUrl);
            window.location.href = feedbackUrl;
          });

          infoWindow.open(map, marker);
        } else {
          console.error("Place Details request failed with status:", status);
        }
    });
  });

  markers.push(marker); // Add the marker to the markers array
}

function displayAmenities(types) {
  const amenitiesMap = {
    'atm': 'ATM',
    'restroom': 'Toilet',
    'snack': 'Snack Bar',
    'car_wash': 'Car Wash',
    'Cafe':'Cafe',
    'Tea': 'Tea,'
  };
  const amenities = types
    .filter(type => amenitiesMap[type])
    .map(type => amenitiesMap[type]);

  return amenities.length ? `<p><strong>Amenities:</strong> ${amenities.join(', ')}</p>` : '';
}


  function clearMarkers() {
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];
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

// Add this line in your script section
const nearbyOfSearchedPlaceButton = document.getElementById("find-nearby-of-searched-place-button");
nearbyOfSearchedPlaceButton.addEventListener("click", findNearbyOfSearchedPlace);

// Add the new function to find nearby gas stations of the searched place
function findNearbyOfSearchedPlace() {
  const places = searchBox.getPlaces();

  if (places.length === 0) {
    console.log("No places to find nearby gas stations.");
    return;
  }

  const selectedPlace = places[0]; // Assuming the user selects the first place in the list

  const request = {
    location: selectedPlace.geometry.location,
    radius: 5000, // You can adjust the radius as needed
    types: ["gas_station"],
  };

  const service = new google.maps.places.PlacesService(map);

  service.nearbySearch(request, (results, status) => {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
      console.log("Nearby Gas Stations of Searched Place:", results);
      clearMarkers();
      for (let i = 0; i < results.length; i++) {
        createMarker(results[i]);
      }
    } else {
      console.error("Nearby Search of Searched Place failed with status:", status);
    }
  });
}

const giveFeedbackButton = document.getElementById("give-feedback-button");
      giveFeedbackButton.addEventListener("click", () => {
        // Get the currently selected gas station marker
        const selectedMarker = markers.find(marker => marker.getAnimation() !== null);

        if (selectedMarker) {
          // Get the place details for the selected gas station
          const request = {
            placeId: selectedMarker.placeResult.place_id,
            fields: ["name", "formatted_address"],
          };

           const service = new google.maps.places.PlacesService(map);

           service.getDetails(request, (placeDetails, status) => {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
              // Redirect to submit_feedback.php with gas station details
              const feedbackUrl = `submit_feedback.php?name=${encodeURIComponent(placeDetails.name)}&address=${encodeURIComponent(placeDetails.formatted_address)}`;
              window.location.href = feedbackUrl;
            } else {
              console.error("Place Details request failed with status:", status);
            }
          });
        } else {
          // Display a message or handle the case where no gas station is selected
          console.log("No gas station selected for feedback.");
        }
      });
      const nearbyPetronasButton = document.getElementById("nearby-petronas-button");
      nearbyPetronasButton.addEventListener("click", () => {
        findNearbyPetronas();
    });
      
    function findNearbyPetronas() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    console.log("User Location:", position.coords.latitude, position.coords.longitude);

                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };

                    map.setCenter(userLocation);
                    map.setZoom(15);

                    const request = {
                        location: userLocation,
                        radius: 5000, // You can adjust the radius as needed
                        keyword: "Petronas", // Add the keyword for Petronas gas stations
                        types: ["gas_station"],
                    };

                    const service = new google.maps.places.PlacesService(map);

                    service.nearbySearch(request, (results, status) => {
                        if (status == google.maps.places.PlacesServiceStatus.OK) {
                            console.log("Nearby Petronas Gas Stations:", results);
                            clearMarkers();
                            for (let i = 0; i < results.length; i++) {
                                createMarker(results[i]);
                            }
                        } else {
                            console.error("Nearby Petronas Search failed with status:", status);
                        }
                    });
                },
                (error) => {
                    console.error("Error getting user location:", error);
                    handleLocationError(true, infoWindow, map.getCenter());
                }
            );
        } else {
            console.error("Geolocation not supported by the browser");
            handleLocationError(false, infoWindow, map.getCenter());
        }
    }
      const nearbyPetronButton = document.getElementById("nearby-petron-button");
      nearbyPetronButton.addEventListener("click", () => {
          findNearbyGasStations("Petron");
      });

        const nearbyShellButton = document.getElementById("nearby-shell-button");
        nearbyShellButton.addEventListener("click", () => {
            findNearbyGasStations("Shell");
        });

      const nearbyCaltexButton = document.getElementById("nearby-caltex-button");
      nearbyCaltexButton.addEventListener("click", () => {
          findNearbyGasStations("Caltex");
      });

    function findNearbyGasStations(keyword) {
      console.log(`Finding nearby ${keyword} gas stations...`);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    console.log("User Location:", position.coords.latitude, position.coords.longitude);

                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };

                    map.setCenter(userLocation);
                    map.setZoom(15);

                    const request = {
                        location: userLocation,
                        radius: 5000, // You can adjust the radius as needed
                        keyword: keyword, // Use the provided keyword
                        types: ["gas_station"],
                    };

                    const service = new google.maps.places.PlacesService(map);

                    service.nearbySearch(request, (results, status) => {
                        if (status == google.maps.places.PlacesServiceStatus.OK) {
                            console.log(`Nearby ${keyword} Gas Stations:`, results);
                            clearMarkers();
                            for (let i = 0; i < results.length; i++) {
                                createMarker(results[i]);
                            }
                        } else {
                            console.error(`Nearby ${keyword} Search failed with status:`, status);
                        }
                    });
                },
                (error) => {
                    console.error("Error getting user location:", error);
                    handleLocationError(true, infoWindow, map.getCenter());
                }
            );
        } else {
            console.error("Geolocation not supported by the browser");
            handleLocationError(false, infoWindow, map.getCenter());
        }
        console.log(`Nearby ${keyword} Gas Stations:`, results);
    }

    const feedbacksButton = document.getElementById("feedbacks-button");
        feedbacksButton.addEventListener("click", () => {
            // Redirect to feedbacktable.php when the button is clicked
            window.location.href = "feedbacktable.php";
        });

        const seeFeedbacksButton = document.getElementById("see-feedbacks-button");
    seeFeedbacksButton.addEventListener("click", () => {
        // Redirect to seefeedback.php when the button is clicked
        window.location.href = "seefeedbacks.php";
    });

    
      window.initAutocomplete = initAutocomplete;
  </script>
    </body>
  </html>