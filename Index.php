<?php

try{
   $result = require './Requests/getAnimals.php';
   // $result = "";
}
catch(\Throwable $e){
    die("Error 500");
}
$PATH = "./";

?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <script>
      PATH = "./"
    </script>  
    <title>Mapa - Beo Zoo Vrt</title>
    <link rel="icon" type="image/x-icon" href="https://www.beozoovrt.rs/wp-content/uploads/2019/10/cropped-favicon-beo-zoo-32x32.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="viewport" content="height=device-height, initial-scale=1.0">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel = "stylesheet" href = "index.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <link rel="stylesheet" href="<?php echo $PATH?>css/sidebar.css"></link>
    <link rel="stylesheet" href="<?php echo $PATH?>css/shared.css"></link>
    <link rel="stylesheet" href="<?php echo $PATH?>css/search.css"></link>
    <link rel="stylesheet" href="<?php echo $PATH?>css/loading_screen.css"></link>
    <script src = "<?php echo $PATH?>js/shared.js"></script>
    <script src = "<?php echo $PATH?>js/search.js"></script>
    <script src = "<?php echo $PATH?>js/sidebar.js"></script>
    <script src = "<?php echo $PATH?>js/nodeMatrix.js"></script>
    <script src = "<?php echo $PATH?>js/trueNodeMatrix.js"></script>
    <script src = "<?php echo $PATH?>js/findPath.js"></script>
    <script src = "<?php echo $PATH?>js/geolocation.js"></script>
    <script src = "<?php echo $PATH?>js/Index.js"></script>
    <script> // Converts request to JS to be able to interface with the leaflet API 
    function fetchAnimalsFromApi() { //Fetches data from the database

            const response = <?php echo ($result) ?>;
           /* fetch('./Requests/getAnimals.php')
              .then(res =>{console.log(res); res.json()})
              .then(response => {
                console.log(response)
                return response*/
            return response;  
    }

    </script>
    <style>

        :root {
            color-scheme: only light;
        }

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

        .leaflet-control-attribution.leaflet-control 
        {
           background:black;
           opacity:0.7; 
        }

        .custom-popup .leaflet-popup-content-wrapper {
            position:absolute;
            background: #f4f0e6;
            color: #4b3f2f;
        }

        .custom-popup .leaflet-popup-tip {
            display: none; /* hide the default triangle tip */
        }

    </style>
</head>
<body>
    <div class="container">
    <main role="main" class="pb-3">

        <div id="loading-screen">
            <div class="spinner"></div>
            <div>Loading map...</div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            
            <div class = "sidebar-main">

                <div class = "beozoovrt-img" >
                    <a href = "https://www.beozoovrt.rs/?lang=sr" target="_blank">
                    <img src ="<?php echo $PATH?>images/globe.png"></img>
                    </a>
                    <!-- <span class="link-text">Website</span> -->
                </div>

                <div id ="sidebar-icons-wrapper" class="sidebar-icons-wrapper">
                        <img style="cursor:pointer"></img>
                        <img style="cursor:pointer"></img>
                        <img style="cursor:pointer"></img>
                        <img style="cursor:pointer"></img>
                        <a href="https://www.beozoovrt.rs/Map/Attributions.php"><img src = "./images/CC.png" style = "cursor:pointer"></img></a>
                </div>
            </div>
            <div class="menu-button-wrapper">
            <button id="menu-button" class="menu-button" onclick="toggleSidebar()">
                <img id ="menu-arrow-img"  src="<?php echo $PATH?>images/right-arrow.png"></img>
            </button>
            </div>
        </div> 

        <!-- Main map div -->
        <div id="map"></div>

        <div class="search-container">
            <!--<i class="fas fa-search search-icon" ><svg xmlns="http://www.w3.org/2000/svg"  height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg></i> -->
            
            <input autocomplete="off" type="text" class="search-input" id="searchInput" placeholder="Pretraga..." onclick = "searchSelected()" onkeyup="filterOptions()"  >
            <i class="search-x-icon" id="search-x-icon" style="position:absolute; top:20px; cursor:pointer;"  onclick = clearPath()>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#d31a15" height="24px" width="24px" version="1.1" id="Capa_1" viewBox="0 0 460.775 460.775" xml:space="preserve">
                    <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55  c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55  c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505  c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55  l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719  c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
                </svg>
            </i>

            <div id="searchResults" class="search-results"></div>
        </div>
    
        <div id="animal-window-wrapper">
            <div id="animal-window">
                <div class="animal-title" id = "animal-title">
                    <h style="font-weight: bolder">Lion</h><br>
                    <h style="font-style: italic; font-size: 16px;">Liones</h><br>
                    <h style="font-size: 16px;color:red">   Endangered</h>
                    <span class="close-btn" onclick="togglePoppup(1, [])">&times</span>
                </div>
                <div class = "position-content-wrapper">

                    <img src='' alt="Animal" class="animal-image" id="animal-pane" alt="animal-image">

                    <div class="animal-description-wrapper">

                        <div class="animal-description" id = "description">
                            <p ></p>
                            <p ></p>
                            <p ></p>
                            <p ></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div> 

<script>
    //CREATING THE MAP
    var map = L.map('map', {
        zoomControl: false,
        attributionControl: false, 
        minZoom: -2,  
        maxZoom: 2,   
        center: [0, 0],  
        zoom: 1,   
        crs: L.CRS.Simple  
    });

    var AnimalIcon = L.Icon.extend({
    options:{
            iconSize: [60, 60], 
            shadowSize: [50, 50],         
            iconAnchor: [25, 50],       
            popupAnchor: [0, -50],
        }
    });

    //ADDING ICONS AND OTHER CONTENT TO THE MAP
    addIconsToMap(map);


    //DEFINE MAP IMG PROPORTIONS
    const imageWidth =   1600; 
    const imageHeight = 1200;  
    var imageBounds = [[0, 0], [imageHeight, imageWidth]];

    var imageUrl ='<?php echo $PATH ?>/images/map_new2.webp';  
    L.imageOverlay(imageUrl, imageBounds,{
    opacity: 1,
    className: 'mainMapImage'}).addTo(map);
    map.fitBounds(imageBounds);


     // DEFINE THE BOUNDS (SOUTHWEST AND NORTHEAST CORNERS)
    var southWest = L.latLng(-imageHeight/4, -400); 
    var northEast = L.latLng(imageHeight*1.2, imageWidth*1.3);
    var bounds = L.latLngBounds(southWest, northEast);

    map.setMaxBounds(bounds);

    map.on('drag', function() {
        map.panInsideBounds(bounds, { animate: true });
    });


    //CLOSES THE SEARCH 
    map.on('click', closeSearchList)


    //ATTRIBUTIONS
   /* var attribution = L.control.attribution({prefix:false}).addAttribution(
      `<div style="padding-left:5px">
          <a style = "font-size:20px;color:white;text-decoration:none" href = "Attributions.php">
            Attributions
          </a>
          <span style="font-size:30px;color:red;cursor:pointer" onClick = 'attribution.remove()'>
            &times
          </span>
       </div>`
    ).addTo(map);*/


    //GEOLOCATION PART

    function geoErorCallback(error)
    {
      alert("Ukoliko želite da koristite sve funkcionalnosti uključite lokaciju i refrešujte stranicu \n\nTo access all features turn on location and refresh the page");
    }
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(setPosition,geoErorCallback, {enableHighAccuracy: true ,timeout: 5000});
    }
    else{
      alert("Ukoliko želite da koristite sve funkcionalnosti uključite lokaciju i refrešujte stranicu \n\nTo access all features turn on location and refresh the page");

    }
    
    //UNCOMMENT TO DISPLAY GRAPH 
    //var curr = 0;
    /*var globalCounter = 0;
    function cycleMarkers()
    {
        if (globalCounter > nodeMatrix.length)
        {
            console.log("Done!!!");
            return;
        }
        let node = nodeMatrix[globalCounter];
        curr++;
        let index = globalCounter;
        let mark = L.marker(node[0], 'red').bindPopup(`marker num: ${index}`).on('click', ()=>connectNodes(index)).addTo(map);
        mark.openPopup();

        node.forEach(dot=>{
            if(dot != node[0])
            L.polyline([node[0], nodeMatrix[dot][0]], {
            color: 'red',
            weight: 10,
            dashArray: '2, 15', // Pattern for the dashes: 5px dash, 10px gap
            }).addTo(map);
        });


        globalCounter+=1;
    }*/

    //TESTING HELPER FUNCTION
    //map.on('click', getCoord);

    //map.on('click', cycleMarkers);
    /*map.on('zoomend', function () {
    const zoom = map.getZoom();
    if (zoom < 0) {
        markers.forEach((marker) => map.removeLayer(marker));
    } 
    else
    {
        markers.forEach((marker) => map.addLayer(marker));// show marker
    }
    });*/

    document.querySelector('img.leaflet-image-layer.leaflet-zoom-animated.mainMapImage').addEventListener('load', () => {
      document.getElementById("loading-screen").style.display = 'none';
    });

</script>
</body>
</html>
