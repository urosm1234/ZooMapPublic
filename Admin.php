<?php
try{
    @require 'Requests/getAnimals.php';
}
catch(\Throwable $e){
    die("Error 500");
}

session_start();

if(!isset($_SESSION['user']))
{
    header('Location: Login.php');
    
    exit();
}

// Destroy the session

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Mapa - Beo Zoo Vrt</title>
    <link rel="icon" type="image/x-icon" href="https://www.beozoovrt.rs/wp-content/uploads/2019/10/cropped-favicon-beo-zoo-32x32.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "Admin.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Include Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src = "<?php echo $PATH?>js/shared.js"></script>
    <script src = "<?php echo $PATH?>js/admin.js"></script>

    <title>Text Input Form</title>
    <style>
                    :root {
        color-scheme: only light;
        }
        #map{
            width:100%;
            height:95vh;
            overflow-y:hidden;
        }
        .form-wrapper{
            display:flex;
            justify-content: center;
            position:relative;
            margin-top:30px;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 300px;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], textarea {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input.small {
            width: 100px;
            height:50px;
        }

        input.medium {
            width: 250px;
            height:50px;
        }

        textarea.large {
            width: 350px;
            height: 150px;
        }

        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
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

        #logout-button
        {
            position:absolute;
            display:flex;
            justify-content: center;
            align-items: center;
            top:10px;
            left:10px;
            padding:5px;
            width:100px;
            height:50px;
            font-size:20px;
            color:white;
            z-index: 4;
            font-weight:bold;
            background-color: #28a745;
            border-radius:15px;
            text-decoration: none;
        }
        .sidebar {
        height: 100%;
        position: fixed;
        top: 0;
        right: 0;
        background-color: #f4f4f4;
        overflow-x: hidden;
        transition: 0.3s;
        padding: 30px;
        width: 500px;
        box-shadow: -2px 0 5px rgba(0,0,0,0.1);
        display:none;
        }
        .sidebar.open {
            display:block;
        }
        .toggle-button {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1001;
        padding: 10px 15px;
        background-color: #007BFF;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        }

    </style>
    <script>
        
        const PATH ="./";
    function fetchAnimalsFromApi() { //Fetches data from the database and 
            const response = <?php echo json_encode($result) ?>;
            console.log(response)
            
            return response;  
        }
    var iconArray;
    async function addIconsToMap(map)
    {
        iconArray = await fetchAnimalsFromApi();
        iconArray.forEach( (icon) => {
                var icont = new AnimalIcon({iconUrl: PATH +'images/new_icons/'+icon.name+'.png'});
                var marker = L.marker([icon.coordinateh, icon.coordinatew], { icon: icont, draggable:true }).addTo(map)
                .on('click',()=> fillInputsWithAnimalInfo(icon.id, icon.id-1,iconArray ) )
                .on('click',()=> toggleSidebar(ifClosed = true) )
                .on('mouseup', (event)=> updateCoords(event, icon.id));
                console.log(icon.name)
                console.log(icon.id-1);
            });
    }
    var current_id = -1;


    function fillInputsWithAnimalInfo(database_id, animal_id, iconArray)
    {
        if(animal_id < iconArray.length && animal_id >= 0)
        {
            current_id = database_id;
            document.getElementById("smallInput1").value = iconArray[animal_id].title;

            if(iconArray[animal_id].latin_title!= null)
            document.getElementById("smallInput2").value = iconArray[animal_id].latin_title;
            else
            document.getElementById("smallInput2").value = ""

            if(iconArray[animal_id].red!= null)
            document.getElementById("smallInput3").value = iconArray[animal_id].red;
            else
            document.getElementById("smallInput3").value = ""

            if(iconArray[animal_id].porodica!= null)
            document.getElementById("smallInput4").value = iconArray[animal_id].porodica;
            else
            document.getElementById("smallInput4").value = ""

            if(iconArray[animal_id].staniste != null)
            document.getElementById("mediumInput1").value = iconArray[animal_id].staniste;
            else
            document.getElementById("mediumInput1").value = "";

            if(iconArray[animal_id].zivotni_vek != null)
            document.getElementById("mediumInput2").value = iconArray[animal_id].zivotni_vek;
            else
            document.getElementById("mediumInput2").value = "";

            if(iconArray[animal_id].rasprostranjenost != null)
            document.getElementById("mediumInput3").value = iconArray[animal_id].rasprostranjenost;
            else
            document.getElementById("mediumInput3").value = "";

            if(iconArray[animal_id].klasa != null)
            document.getElementById("smallInput5").value = iconArray[animal_id].klasa;
            else
            document.getElementById("smallInput5").value = ""

            if(iconArray[animal_id].endangered_level != null)
            document.getElementById("smallInput6").value = iconArray[animal_id].endangered_level;
            else
            document.getElementById("smallInput6").value = ""

            if(iconArray[animal_id].tekst != null)
            document.getElementById("largeInput").value = iconArray[animal_id].tekst;
            else
            document.getElementById("largeInput").value = "";
            return 1;
        }
        return 0;
    }

    async function formSubmitted()
    {
        
        if(current_id <= 0)
            return 0;
        
        let title = document.getElementById("smallInput1").value;
        let latin_title = document.getElementById("smallInput2").value;
        let red = document.getElementById("smallInput3").value;
        let porodica = document.getElementById("smallInput4").value;
        let staniste = document.getElementById("mediumInput1").value;
        let zivotni_vek = document.getElementById("mediumInput2").value;
        let rasprostranjenost = document.getElementById("mediumInput3").value;
        let klasa = document.getElementById("smallInput5").value;
        let endangered_level = document.getElementById("smallInput6").value;
        let tekst = document.getElementById("largeInput").value;
        if(title == "")
        {
            document.getElementById("smallInput1").style.border = "red";
            return 0;
        }
        else document.getElementById("smallInput1").style.border = "black";
        if(tekst == "")
        {
            document.getElementById("largeInput").style.border = "red";
            return 0;
        }
        else document.getElementById("largeInput").style.border = "black";
        //if(title && latin_title && red &&  porodica && staniste&&  zivotni_vek &&  rasprostranjenost &&  klasa && endangered_level && tekst)
        updateAnimal(current_id , title, latin_title, red, porodica,staniste, zivotni_vek, rasprostranjenost, klasa, endangered_level, tekst)
        //else console.log(title + latin_title + red + porodica + staniste + zivotni_vek + rasprostranjenost + klasa + endangered_level + tekst)
        return 1;
    }

    function toggleSidebar(ifClosed) {
    const sidebar = document.getElementById("sidebar");
    if(ifClosed && sidebar.classList.contains("open"))
        return
    sidebar.classList.toggle("open");
  }
    </script>
</head>
<body>
    <form>
    <a id="logout-button" href = "./Requests/logout.php" type = "submit">Logout</a>
    </form>

    <div id ="map" ></div>

    <button class="toggle-button" onclick="toggleSidebar(false)">Toggle Form</button>

    <div id = "sidebar"class="sidebar">

    <h1>Input Form</h1>
    <div class = "form-wrapper">
    <form method="put" action="/api/Updates/2">
        <label for="Id">Animal Id:</label>
        <input id="Id" type="number" name="id" class="small" style="background-color:#ccc" readonly/>
        <br>
        <label for="smallInput1">Naziv životnije:</label>
        <input id="smallInput1" type="text" name="title" class="medium" />
        <br>
        <label for="smallInput2">Naziv na latinskom:</label>
        <input id="smallInput2" type="text" name="latin_title" class="medium" />
        <br>
        <label for="smallInput3">Red:</label>
        <input id="smallInput3" type="text" name="red" class="medium" />
        <br>
        <label for="smallInput4">Porodica:</label>
        <input id="smallInput4" type="text" name="porodica" class="medium" />
        <br>
        <label for="mediumInput1">Stanište:</label>
        <textarea id="mediumInput1" type="text" name="staniste" class="medium" ></textarea>
        <br>
        <label for="mediumInput2">Životni vek:</label>
        <textarea id="mediumInput2" type="text" name="zivotni_vek" class="medium" ></textarea>
        <br>
        <label for="mediumInput3">Rasprostranjenost:</label>
        <textarea id="mediumInput3" type="text" name="rasprostranjenost" class="medium" ></textarea>
        <br>
        <label for="smallInput5">Klasa:</label>
        <input id="smallInput5" type="text" name="klasa" class="medium" />
        <br>
        <label for="smallInput6">ENDANGERED LEVEL:</label>
        <input id="smallInput6" type="text" name="endangered_level" class="medium" />
        <br>
        <label for="largeInput">Tekst:</label>
        <textarea id="largeInput" name="largeInput" class="large"></textarea>
        <br>
        <button type="button" onclick = "formSubmitted()">Submit</button>
        <span id ="responseText"></span>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    </form>
    </div>
    </div>



<script>


    var map = L.map('map', {
        minZoom: -1,  //44.824739, 20.452049 zoo corner
        maxZoom: 2,   // Allows zooming in
        center: [0, 0],  // Center of the image
        zoom: 0,   // Initial zoom level
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

        addIconsToMap(map);
    
    const imageWidth =   1600;  // Adjust this to match your image width (in pixels)
    const imageHeight = 1200;  // Adjust this to match your image height (in pixels)
    var imageBounds = [[0, 0], [imageHeight, imageWidth]];

    var imageUrl = PATH+'images/map_new2.webp';  // Replace with your actual image URL
    L.imageOverlay(imageUrl, imageBounds,{
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

</script>
</body>
</html>
