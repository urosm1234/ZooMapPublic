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
        var map = L.map('map').setView([44.825819,20.453544], 13); // Default center (London)

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Click event to get coordinates
        map.on('click', function (e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            // Display coordinates
            document.getElementById('coords').innerText = `Coordinates: ${lat}, ${lng}`;

            // Optional: Add a marker on click
            L.marker([lat, lng]).addTo(map)
                .bindPopup(`${lat},${lng}`)
                .openPopup();
        });
    </script>

</body>
</html>