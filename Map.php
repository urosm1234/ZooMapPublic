<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet Map - Click for Coordinates</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        #map { height: 500px; width: 100%; }
        #coords { margin-top: 10px; font-size: 18px; }
    </style>
</head>
<body>

    <h2>Click on the Map to Get Coordinates</h2>
    <div id="map"></div>
    <p id="coords">Click on the map to see the coordinates here.</p>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize the Leaflet map
        var map = L.map('map', {maxZoom: 18}).setView([44.825819,20.453544], 12); // Default center (London)

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Click event to get coordinate
        var trueLocMatrix = []
        map.on('click', function (e) {
            var lat = e.latlng.lat
            var lng = e.latlng.lng
            trueLocMatrix.push([lat, lng]);
            console.log(trueLocMatrix.length)
            // Display coordinates
            document.getElementById('coords').innerText = trueLocMatrix.length;

            // Optional: Add a marker on click
            L.circle([lat, lng],{radius:0.01,color:'red'}).addTo(map);
        });

        /*if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;

          // Set the map view to the user's location
          map.setView([lat, lng], 15);

          // Add a marker at the user's location
          L.marker([lat, lng])
            .addTo(map)
            .bindPopup("You are here!")
            .openPopup();
        },
        (error) => {
          alert("Geolocation failed: " + error.message);
          // Fallback view
          map.setView([0, 0], 2);
        }
      );
    } else {
      alert("Geolocation is not supported by your browser.");
      map.setView([0, 0], 2);
    }*/

    </script>

</body>
</html>