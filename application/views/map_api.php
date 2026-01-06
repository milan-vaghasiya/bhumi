    <?php $this->load->view('includes/header'); ?>
	<style>
	    #sidebar {
          flex-basis: 15rem;
          flex-grow: 1;
          padding: 1rem;
          max-width: 30rem;
          height: 100%;
          box-sizing: border-box;
          overflow: auto;
        }
        
        #map {
          flex-basis: 0;
          flex-grow: 4;
          height: 100%;
        }
        
        #sidebar {
          flex-direction: column;
        }

	</style>
	<div class="page-content-tab">
		<div class="container-fluid" style="padding:0px 10px;">
			<div class="row">

				<div class="col-lg-12">
                    <div id="map"></div>
                    <div id="sidebar">
                        <h3 style="flex-grow: 0">Request</h3>
                        <pre style="flex-grow: 1" id="request"></pre>
                        <h3 style="flex-grow: 0">Response</h3>
                        <pre style="flex-grow: 1" id="response"></pre>
                    </div>
				</div>
			</div>
		</div>
	</div>

	<?php $this->load->view('includes/footer'); ?>
	
	<!-- Javascript  -->   
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyACJW3ouSsTuZserlw3FRHIC2MWbppIuJ4"></script>
	<script>
	
	    let map;
        
        initMap();
	    async function initMap() {
	        const { Map } = await google.maps.importLibrary("maps");
  const bounds = new google.maps.LatLngBounds();
  const markersArray = [];
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 55.53, lng: 9.4 },
    zoom: 10,
  });
  // initialize services
  const geocoder = new google.maps.Geocoder();
  const service = new google.maps.DistanceMatrixService();
  // build request
  const origin = "360001, India";
  const destination = "360575, India";
  const request = {
    origins: ["360001, India","360001, India"],
    destinations: ["361001, India","360575, India"],
    travelMode: google.maps.TravelMode.DRIVING,
    unitSystem: google.maps.UnitSystem.METRIC,
    avoidHighways: false,
    avoidTolls: false,
  };

  // put request on page
  document.getElementById("request").innerText = JSON.stringify(
    request,
    null,
    2,
  );
  // get distance matrix response
  service.getDistanceMatrix(request).then((response) => {
    // put response
    document.getElementById("response").innerText = JSON.stringify(
      response,
      null,
      2,
    );

    // show on map
    const originList = response.originAddresses;
    const destinationList = response.destinationAddresses;

    deleteMarkers(markersArray);

    const showGeocodedAddressOnMap = (asDestination) => {
      const handler = ({ results }) => {
        map.fitBounds(bounds.extend(results[0].geometry.location));
        markersArray.push(
          new google.maps.Marker({
            map,
            position: results[0].geometry.location,
            label: asDestination ? "D" : "O",
          }),
        );
      };
      return handler;
    };

    for (let i = 0; i < originList.length; i++) {
      const results = response.rows[i].elements;

      geocoder
        .geocode({ address: originList[i] })
        .then(showGeocodedAddressOnMap(false));

      for (let j = 0; j < results.length; j++) {
        geocoder
          .geocode({ address: destinationList[j] })
          .then(showGeocodedAddressOnMap(true));
      }
    }
  });
}

function deleteMarkers(markersArray) {
  for (let i = 0; i < markersArray.length; i++) {
    markersArray[i].setMap(null);
  }

  markersArray = [];
}

window.initMap = initMap;
    </script>