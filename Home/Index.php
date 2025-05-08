<?php

try{
    require 'Requests/getAnimals1.php';

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
    <script>
        const PATH ="/ZooProject/ZooMap/";
    </script>
    <script src = "<?php echo $PATH?>js/shared.js"></script>
    <script src = "<?php echo $PATH?>js/search.js"></script>
    <script src = "<?php echo $PATH?>js/sidebar.js"></script>
    <script src = "<?php echo $PATH?>js/nodeMatrix.js"></script>
    <script src = "<?php echo $PATH?>js/trueNodeMatrix.js"></script>
    <script src = "<?php echo $PATH?>js/findPath.js"></script>
    <script src = "<?php echo $PATH?>js/geolocation.js"></script>
    
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


    .custom-popup .leaflet-popup-content-wrapper {
            position:absolute;
            background: #f4f0e6;
            color: #4b3f2f;
    }
    .custom-popup .leaflet-popup-tip {
    display: none; /* hide the default triangle tip */
  }

    </style>
    <script>
    function fetchAnimalsFromApi() { //Fetches data from the database and 
            const response = <?php echo json_encode($result) ?>;
            console.log(response)
            
            return response;  
        }
    var iconArray = [];
    var markers = new L.markerClusterGroup({
    iconCreateFunction: function (cluster) {
        var count = cluster.getChildCount();

        // Customize the cluster icon using HTML
        return L.divIcon({
        html: `
            <div class="custom-cluster-icon">
            <img src="${PATH}images/multiple_animals.png" alt="cluster-icon" />
            <span class="cluster-count">${count}</span>
            </div>
        `,
        className: '', // Prevent default styles
        iconSize: [50, 50] // Adjust based on your image size
        });
    }
    });
    async function addIconsToMap(map)
    {
        iconArray = await fetchAnimalsFromApi();
        iconArray.forEach( (icon) => {
                var icont = new AnimalIcon({iconUrl: PATH +'images/new_icons/'+icon.name+'.png'});
                let marker = L.marker([icon.coordinateh, icon.coordinatew], { icon: icont, draggable:false });//.addTo(map);

                if(icon.title)
                {
                    marker.on('click',()=> togglePoppup(icon.id, iconArray) );
                    markers.addLayer(marker);
                }
                else
                    marker.addTo(map);

            });

            
            const sidebarIcons = iconArray.filter(animal => animal.name == "wc" ||  animal.name == "ulaz" || animal.name=="restoran" || animal.name == "caffe");
            const positions = {'wc':0, 'caffe':1, 'restoran':2, "ulaz":3};
            for(let i =0; i< sidebarIcons.length; i+=1)
            {   
                let elem = document.getElementById("sidebar-icons-wrapper").children[positions[sidebarIcons[i].name]];
                //Check if there is already a duplicate of this element, so that we don't clear when drawing paths
                if(elem.src == "")
                    panToCoords(elem, sidebarIcons[i], true);
                else
                    panToCoords(elem, sidebarIcons[i], false);
                elem.src =PATH +"images/new_icons/" +sidebarIcons[i].name+'.png';
            }
            for(let i =0; i< document.getElementById("sidebar-icons-wrapper").children.length; i+=1)
            {
                document.getElementById("sidebar-icons-wrapper").children[i].addEventListener('click',()=>toggleSidebar());
            }
            markers.addTo(map);

    }

    function panToCoords(node, icon, clear = true)
    {
        node.addEventListener('click', () => optionSelected(icon, clear));
    }

    </script>
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
            <div class="menu-button-wrapper">
            <button id="menu-button" class="menu-button" onclick="toggleSidebar()">
                <img id ="menu-arrow-img"  src="<?php echo $PATH?>images/left-arrow.png"></img>
            </button>
            </div>
            <div class = "sidebar-main">

                <div class = "beozoovrt-img" href = "https://www.beozoovrt.rs/?lang=sr" role="button">
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
                </div>
            </div>
        </div> 

        <!-- Main map div -->
        <div id="map"></div>

        <div class="search-container">
            <!--<i class="fas fa-search search-icon" ><svg xmlns="http://www.w3.org/2000/svg"  height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg></i> -->
            
            <input autocomplete="off" type="text" class="search-input" id="searchInput" placeholder="Pretraga..." onclick = "searchSelected()" onkeyup="filterOptions()"  >
            <i class="search-x-icon" id="search-x-icon" onclick = clearPath()>
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
                    <h style="font-weight: italic; font-size: 16px;">Liones</h>
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
    // Dimensions of your background image
    // Initialize the Leaflet map, setting the initial view to cover the image area

    var map = L.map('map', {
        zoomControl: false,
        minZoom: -1,  
        maxZoom: 2,   
        center: [0, 0],  
        zoom: 1,   
        crs: L.CRS.Simple  
    });
        var AnimalIcon = L.Icon.extend({
        options:{
                iconSize: [50, 50], 
                shadowSize: [50, 50],         
                iconAnchor: [25, 50],       
                popupAnchor: [0, -50],

            }
        });

 

    //GEOLOCATION PART

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(setPosition,null, {enableHighAccuracy: true ,timeout: 5000});
    }
    
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





    // ADDING ICONS AND OTHER CONTENT TO THE MAP
    addIconsToMap(map);

    
    const imageWidth =   1600; 
    const imageHeight = 1200;  
    var imageBounds = [[0, 0], [imageHeight, imageWidth]];

    var imageUrl = PATH + '/images/map_new2.webp';  
    L.imageOverlay(imageUrl, imageBounds,{
    attribution: '© OpenStreetMap',
    opacity: 1,
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
    //map.on('click', cycleMarkers);
    
    /*var curr = 0;
    nodeMatrix.forEach(node => {
        let index = curr;
        let mark = L.marker(node[0], 'red').on('click', ()=>connectNodes(index)).addTo(map);
        //markerNodeArray.push(mark)  
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
