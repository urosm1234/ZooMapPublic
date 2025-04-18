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

    <script src = "<?php echo $PATH?>js/shared.js"></script>
    <script src = "<?php echo $PATH?>js/search.js"></script>
    <script src = "<?php echo $PATH?>js/sidebar.js"></script>
    <script src = "<?php echo $PATH?>js/nodeMatrix.js"></script>
    <script src = "<?php echo $PATH?>js/trueNodeMatrix.js"></script>
    <script src = "<?php echo $PATH?>js/findPath.js"></script>
    
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
 
        .custom-cluster-icon {
        position: relative;
        width: 60px;
        height: 60px;
        }

        .custom-cluster-icon img {
        width: 100%;
        height: 100%;
        border-radius: 50%; /* optional: circle shape */
        }

        .cluster-count {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color:rgb(232, 241, 255);
        font-size: 30px;
        font-weight: bolder;
        font-family: Arial, sans-serif;
        pointer-events: none;
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
            markers.addTo(map);

    }

    function panToCoords(node, icon, clear = true)
    {
        node.addEventListener('click', () => optionSelected(icon, clear));
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
                <img style="cursor:pointer"></img>
                <img style="cursor:pointer"></img>
                <img style="cursor:pointer"></img>
                <img style="cursor:pointer"></img>
        </div>
    </div> 



    <div id="map"></div>

    <!-- Search input field -->
    <div class="search-container">
        <!--<i class="fas fa-search search-icon" ><svg xmlns="http://www.w3.org/2000/svg"  height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg></i> -->
        
        <input autocomplete="off" type="text" class="search-input" id="searchInput" placeholder="Pretraga..." onclick = "searchSelected()" onkeyup="filterOptions()"  >

    <!-- Scrollable results list -->
        <div id="searchResults" class="search-results"></div>
    </div>
    <div id="animal-window-wrapper">
        <div id="animal-window">
            <div class="animal-title" id = "animal-title">
                <h style="font-weight: bolder">Lion</h><br>
                <h style="font-weight: italic; font-size: 16px;">Liones</h>
                <!-- Close button -->
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
    var pointCurr = null;
        
    const pointB = [200, 200];
    var dottedPath = null;

    //GEOLOCATION PART

    /*if (navigator.geolocation) {
        navigator.geolocation.watchPosition(setPosition,null, {enableHighAccuracy: true ,timeout: 5000});
    }*/
    
    function setPosition(newPosition)
    {
        console.log(newPosition.coords);
        userPosition = newPosition.coords;
        console.log(newPosition.coords);
        if(positionMarker)
        {
            userPosition = [44.824890,20.454204]
            let y = findDistFromLine(userPosition, [44.824685,20.452171], [44.824890,20.455744]);
            let x = Math.sqrt(findNodeDist(userPosition, [44.824685,20.452171])**2  - y**2);
            console.log(y);
            x = x*1432/findNodeDist([44.824715,20.452155],[44.824947,20.455744]);
            y = y*978/findNodeDist([44.826869,20.451801], [44.824715,20.452155]);
            //let pointCurr = [ userPosition.latitude + 350,  userPosition.longitude + 1100];
            pointCurr = [y,x];
            positionMarker.setLatLng(pointCurr);
        }
        else
        {
            userPosition = [44.824890,20.454204]
            let y = findDistFromLine(userPosition, [44.824685,20.452171], [44.824890,20.455744]);
            let x = Math.sqrt(findNodeDist(userPosition, [44.824685,20.452171])**2  - y**2);
            console.log(y);
            x = x*1432/findNodeDist([44.824715,20.452155],[44.824947,20.455744]);
            y = y*978/findNodeDist([44.826869,20.451801], [44.824715,20.452155]);
            //let pointCurr = [ userPosition.latitude + 350,  userPosition.longitude + 1100];
            pointCurr = [y,x];
            positionMarker =  L.marker(pointCurr).addTo(map);
            
        }

    }
    var curr = 0;
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



    function findSomeCoords([lat, lng]) {
        const R = 6371.0; // km
        let x = R * Math.PI * lng / 180.0;

        let y = R * Math.PI * lat / 180.0;

        return [x, y];
    }
    function findAbsDist(coords1,coords2)
    {
        return Math.sqrt((coords1[0]-coords2[0])**2 + (coords1[1]-coords2[1])**2 + (coords1[2]-coords2[2])**2)
    }

    function aproximateLocation(coords)
    { 
        console.log(coords)
        coords = findSomeCoords(coords)
       
        let minDist = 3000000, returnIndex1 = -1, returnIndex2=-1, index = 0, newDot;
        let k=0.0,k1 =0.0, n=0.0, n1 =0.0;
        let counter = 0;
        nodeMatrix.forEach(node =>{
            let coords1 =  findSomeCoords(trueNodeMatrix[index]);
            for(let i =1;i<node.length;i++)
            {
                let coords2 = findSomeCoords(trueNodeMatrix[node[i]]);
                k = (coords1[0] - coords2[0]) / (coords1[1] - coords2[1]);
                if(k!=0.0)
                k1 = -1/k;
                else
                k1 = 9999999;
                n = coords1[0]-k*coords1[1];
                let x = (n-(coords[0] -k1*coords[1]))/(k1 - k);
                let y = k*x + n;

                if(index == 59 && node[i] == 57)
                {
                    console.log([y, x]);
                    console.log(k, n);
                    console.log(k1, (coords[0] -k1*coords[1]));
                }
                if(x<=Math.max(coords1[1],coords2[1]) && x>=Math.min(coords1[1],coords2[1]) && y<=Math.max(coords1[0],coords2[0]) && y>=Math.min(coords1[0],coords2[0]))
                {
                    
                    let dist = findNodeDist(coords, [y, x]);
                    console.log(dist);
                    if(dist< minDist)
                    {
                        minDist = dist;
                        returnIndex1 = index;
                        returnIndex2 = i;
                        newDot = [y, x];
                    }
                }
            }
            if(findNodeDist(coords1, coords) < minDist)
            {
                minDist = findNodeDist(coords1, coords);
                returnIndex1 = index;
                returnIndex2 = -1;
                
            }
            index++;
        });
        if(returnIndex2 > 0)
        {
            let coords1 = findSomeCoords(trueNodeMatrix[returnIndex1]);
            let coords2 = findSomeCoords(trueNodeMatrix[returnIndex2]);
            let proportions = findNodeDist(coords1, newDot) / findNodeDist(coords1, coords2);
            console.log(proportions);
            return[[returnIndex1, nodeMatrix[returnIndex1][returnIndex2]], proportions];
        }
        else
        return[[returnIndex1, returnIndex1]];
    }

    /*function aproximateLocationBasic([bottomLeft, bottomRight, topLeft, target])
    {
        bottomLeft = findSomeCoords(bottomLeft);
        bottomRight = findSomeCoords(bottomRight);
        topLeft = findSomeCoords(topLeft);
        const maxHeight = 990.7857953427424, maxWidth = 1310.5279039143743;
        console.log(maxHeight);
        console.log(maxWidth);
        target = findSomeCoords(target);
        let x = (target[0] - bottomLeft[0]) / (bottomRight[0] - bottomLeft[0]);
        x *= maxWidth;
        let y = (target[1] - bottomLeft[1])/ (topLeft[1] - bottomLeft[1]);
        y*= maxHeight;
        return [y, x];
    }*/
    // ADDING ICONS AND OTHER CONTENT TO THE MAP
    addIconsToMap(map);

    
    const imageWidth =   1600;  // Adjust this to match your image width (in pixels)
    const imageHeight = 1200;  // Adjust this to match your image height (in pixels)
    var imageBounds = [[0, 0], [imageHeight, imageWidth]];

    var imageUrl = PATH + '/images/map_new2.jpg';  
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
    var testCoord = [44.8252326294156, 20.45535206794739];
    var res = aproximateLocation(testCoord);

    if(res.length > 1)
    {
        console.log(findNodeDist(nodeMatrix[res[0][0]][0], nodeMatrix[res[0][1]][0]) / res[1]);
        var rad = findNodeDist(nodeMatrix[res[0][0]][0], nodeMatrix[res[0][1]][0])/res[1];
        const coordsFinal = res[0];

        let coords1 = nodeMatrix[res[0][0]][0];
        let coords2 = nodeMatrix[res[0][1]][0];
        if(coords1[1] > coords2[1])
        {
            let temp = coords2;
            coords2 = coords1;
            coords1 = temp;
        }
        let ratio = res[1]*findNodeDist(coords1,coords2);
        let k = (coords1[0] - coords2[0]) / (coords1[1] - coords2[1]);

        const radians = Math.atan(k);
        const degrees = radians * (180 / Math.PI);
        //pointCurr = [coords1[0] + ratio*Math.sin(radians), coords1[1] + ratio*Math.cos(radians)];
        
        pointCurr = [ (nodeMatrix[res[0][0]][0][0] + nodeMatrix[res[0][1]][0][0])/2, (nodeMatrix[res[0][0]][0][1] + nodeMatrix[res[0][1]][0][1])/2];
        positionMarker = L.marker(pointCurr).addTo(map);
        console.log(coords1);
        console.log(coords2);
        console.log(res);
    }
    else
    {
        pointCurr = nodeMatrix[res[0][0]][0];
        positionMarker = L.marker(pointCurr).addTo(map);
    }

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

</script>
</body>
</html>
