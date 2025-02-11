<?php

try{
    require 'Requests/getAnimals.php';

}
catch(\Throwable $e){
    die("Error");
}

?>


<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="viewport" content="height=device-height, initial-scale=1.0">
    <!-- Include Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel = "stylesheet" href = "index.css">
    <!-- Include Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="<?php echo $PATH?>css/sidebar.css"></link>
    <link rel="stylesheet" href="<?php echo $PATH?>css/shared.css"></link>
    <link rel="stylesheet" href="<?php echo $PATH?>css/search.css"></link>
    <script src = "<?php echo $PATH?>js/shared.js"></script>
    <script src = "<?php echo $PATH?>js/search.js"></script>
    <script src = "<?php echo $PATH?>js/sidebar.js"></script>
    <script src = "<?php echo $PATH?>js/nodeMatrix.js"></script>
    <script src = "<?php echo $PATH?>js/findPath.js"></script>
    <style>
        body{
            overflow:hidden;
        }
        .mainMapImage
        {
        /* !!! HAS TO BE ON THIS PAGE OR IT DOESN'T LOAD CORRECTLY */

        box-shadow: 0 20px 20px rgba(0, 0, 0, 0.4); /* 3D shadow effect */
        border-radius: 10px; /* Rounded corners */
        border-style: dashed;
        border-width:4px;
        border-radius: 10px;
        border-color:black;
        transform: translate('-50%','-50%');
        }
 

    </style>
    <script>
        const PATH ="/ZooProject/ZooMap/";
    function fetchAnimalsFromApi() { //Fetches data from the database and 
            const response = <?php echo json_encode($result) ?>;
            console.log(response)
            
            return response;  
        }
    var iconArray = [];
    async function addIconsToMap(map)
    {
        iconArray = await fetchAnimalsFromApi();
        iconArray.forEach( (icon) => {
                var icont = new AnimalIcon({iconUrl: PATH +'images/icons/'+icon.name+'.png'});
                var marker = L.marker([icon.coordinateh, icon.coordinatew], { icon: icont, draggable:false }).addTo(map);
                if(icon.title)
                marker.on('click',()=> togglePoppup(icon.id, iconArray) );
            });
            const toiletIcon = iconArray.filter(animal => animal.name == "wc" ||  animal.name == "ulaz");
            console.log(toiletIcon[0].coordinateh);
            console.log(toiletIcon[1].coordinatew);
            document.getElementById("sidebar-icons-wrapper").children[0].src =PATH +"images/icons/" +toiletIcon[0].name+'.png';
            document.getElementById("sidebar-icons-wrapper").children[1].src =PATH +"images/icons/" +toiletIcon[1].name+'.png';
            //document.getElementById("sidebar-icons-wrapper").children[0].addEventListener('click', () => togglePoppup(toiletIcon[0].id, iconArray));
            panToCoords(document.getElementById("sidebar-icons-wrapper").children[0], 0, toiletIcon);
            panToCoords(document.getElementById("sidebar-icons-wrapper").children[1], 1, toiletIcon);

    }

    function panToCoords(node, counter, toiletIcon)
    {
        node.addEventListener('click', () => optionSelected(toiletIcon[counter]));
    }

    </script>
</head>
<body>
    <body >
    <div class="container">
        <main role="main" class="pb-3">
    <div class="sidebar" id="sidebar">
        <div class="menu-button-wrapper">
        <button id="menu-button" class="menu-button" onclick="toggleSidebar()">
            <img id ="menu-arrow-img"  src="<?php echo $PATH?>images/left-arrow.png"></img>
        </button>
        </div>
        <div id ="sidebar-icons-wrapper" class="sidebar-icons-wrapper">
                <img style="cursor:pointer"src ="" ></img>
                <img style="cursor:pointer"src ="" ></img>
        </div>
    </div> 
    <!-- #region >-->


<div id="map"></div>
        <div class="search-container">
        <!-- Search input field -->
        <div class = "search-box">
            <input autocomplete="off" type="text" id="searchInput" class="search-input" placeholder="Pretraga..." onclick = "searchSelected()" onkeyup="filterOptions()" >
            <img class= "magnifying-img" src = "<?php echo $PATH?>images/magnifying_glass.png" onclick = "toggleResults()"></img>
        </div>
        <!-- Scrollable results list -->
            <div id="searchResults" class="search-results"></div>
        </div>
    <div id="animal-window">

        <div class="animal-title" id = "animal-title">
        <h style="font-weight: bolder">Lion</h>
        <!-- Close button -->
        <span class="close-btn" onclick="togglePoppup()">&times</span>
        </div>
    <div class = "position-content-wrapper">
    <!-- Animal image -->
     <!-- <div> -->
    <img src='' alt="Animal" class="animal-image" id="animal-pane" alt="animal-image">
    <!-- </div> -->

    <!-- Scrollable description text -->
    <div class="animal-description-wrapper">
    <div class="animal-description" id = "description">
        <p ></p>
        <p ></p>
        <p ></p>
        <p >
            The lion (Panthera leo) is a large cat of the genus Panthera native to Africa and India. It is one of the most
            recognizable animals due to its muscular, deep-chested body, short, rounded head, round ears, and a hairy tuft
            at the end of its tail. Lions are social animals that live in groups called prides. They are apex predators, and
            their primary prey are ungulates such as antelopes and zebras.            
        </p>
    </div>
    </div>
    <!-- Input element -->
    <!--<input type="text" class="animal-input" placeholder="Type your favorite animal here..."> -->
    </div>
    </div>
    </main>
    </div> 

<script>
    // Dimensions of your background image
    // Initialize the Leaflet map, setting the initial view to cover the image area
    var map = L.map('map', {
        minZoom: -1,  //44.824739, 20.452049 zoo corner
        maxZoom: 2,   // Allows zooming in
        center: [0, 0],  // Center of the image
        zoom: 1,   // Initial zoom level
        crs: L.CRS.Simple  // Use a simple coordinate reference system for flat images
    });
        var AnimalIcon = L.Icon.extend({
        options:{
                iconSize: [50, 50], 
                shadowSize: [50, 50],         
                iconAnchor: [25, 50],       
                popupAnchor: [0, -50],

            }
        });


    var userPosition;
    var positionMarker = null;
        
    const pointB = [200, 200];
    var dottedPath = null;

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(setPosition,null, {enableHighAccuracy: false,timeout: 5000});
    }
    function setPosition(newPosition)
    {
        userPosition = newPosition.coords;
        console.log(userPosition);
        if(positionMarker)
            positionMarker.setLatLng([ userPosition.latitude + 350,  userPosition.longitude + 1100]);
        else
        {
            let pointCurr = [ userPosition.latitude + 350,  userPosition.longitude + 1100];
            positionMarker =  L.marker(pointCurr).addTo(map);
            
        }

    }


    addIconsToMap(map);

    
    const imageWidth =   1600;  // Adjust this to match your image width (in pixels)
    const imageHeight = 1200;  // Adjust this to match your image height (in pixels)
    var imageBounds = [[0, 0], [imageHeight, imageWidth]];

    var imageUrl = PATH + '/images/map_new2.jpg';  // Replace with your actual image URL
    L.imageOverlay(imageUrl, imageBounds,{
    attribution: '© OpenStreetMap',
    opacity: 0.7,
    className: 'mainMapImage'}).addTo(map);
    map.fitBounds(imageBounds);

     // Define the bounds (SouthWest and NorthEast corners)
    var southWest = L.latLng(-imageHeight/4, -400); // Example SW corner
    var northEast = L.latLng(imageHeight*1.2, imageWidth*1.3); // Example NE corner
    var bounds = L.latLngBounds(southWest, northEast);

    // Apply the bounds to the map to limit panning
    map.setMaxBounds(bounds);

    // Make sure the map view stays within the bounds even after zooming out
    map.on('drag', function() {
        map.panInsideBounds(bounds, { animate: true });
    });

    map.on('click', getCoord); 
    map.on('click', closeSearchList)
    var curr = 0;
   /* nodeMatrix.forEach(node => {
        let index = curr;
        L.marker(node[0], 'red').on('click', ()=>connectNodes(index)).addTo(map);
        node.forEach(dot=>{
            if(dot != node[0])
            L.polyline([node[0], nodeMatrix[dot][0]], {
            color: 'red',
            weight: 10,
            dashArray: '2, 15', // Pattern for the dashes: 5px dash, 10px gap
            }).addTo(map);
        });
        curr++;
    });*/
    
</script>
</body>
</html>
