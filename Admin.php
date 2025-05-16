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
$PATH = "./";
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


.form-wrapper {
    margin-bottom: 20px;
}

.sidebar-section {
    border-top: 1px solid #ccc;
    padding-top: 10px;
}

.section-title {
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
    margin-top: 10px;
    user-select:none;
}
.section-title > span{
  user-select:none;
}

.section-content {
    display: none;
    margin-top: 10px;
}

    </style>
    <script>
        
        const PATH ="./";
    function fetchAnimalsFromApi() { //Fetches data from the database and 
            const response = <?php echo json_encode($result) ?>;
            console.log(response)
            
            return response;  
        }
    var iconArray, markers = Array();

    function addOptionsToSelect() {
      selectElem = document.getElementById("selectedAnimalNew");
      selectElem.options.length = 0;

      let optio = document.createElement('option');
      optio.value = "";
      optio.textContent = "New Animal"
      selectElem.appendChild(optio);

      for(let i =0;i<iconArray.length;i++)
      {
      if(iconArray[i].title === null)
        continue;
      const option = document.createElement('option');
      option.value = i;
      console.log(iconArray[i].title);
      option.textContent = iconArray[i].title; 
      selectElem.appendChild(option);
      } 
    }
    async function addIconsToMap(map)
    {
        iconArray = await fetchAnimalsFromApi();
        var counter = 0;
        iconArray.forEach( (icon) => {
                let current = counter;
                var icont = new AnimalIcon({iconUrl: PATH +'images/new_icons/'+icon.name+'.png'});
                var marker = L.marker([icon.coordinateh, icon.coordinatew], { icon: icont, draggable:true }).addTo(map)
                .on('click',()=> fillInputsWithAnimalInfo(icon.id, current,iconArray ) )
                .on('click',()=> toggleSidebar(ifClosed = true) )
                .on('mouseup', (event)=> updateCoords(event, icon.id));
                console.log(icon.title)
                markers.push(marker);
                counter+=1;
            });
           addOptionsToSelect(); 
    }
    var current_id = -1;


    function fillInputsWithAnimalInfo(animal_id, animal_id, iconArray)
    {
        if(animal_id < iconArray.length && animal_id >= 0)
        {
            document.getElementById("Id").value = animal_id;

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
       current_id = iconArray[document.getElementById("Id").value].id; 
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
            return 3;
        }
        else document.getElementById("smallInput1").style.border = "black";
        if(tekst == "")
        {
            document.getElementById("largeInput").style.border = "red";
            return 5;
        }
        else document.getElementById("largeInput").style.border = "black";
        //if(title && latin_title && red &&  porodica && staniste&&  zivotni_vek &&  rasprostranjenost &&  klasa && endangered_level && tekst)
        let response = await updateAnimal(current_id , title, latin_title, red, porodica,staniste, zivotni_vek, rasprostranjenost, klasa, endangered_level, tekst)
        //else console.log(title + latin_title + red + porodica + staniste + zivotni_vek + rasprostranjenost + klasa + endangered_level + tekst)
          if(response)
          {
            iconArray = await fetchAnimalsFromApi();
            console.log("Good");
            return 1;
          }

    }

    async function uploadAnimalImages()
    {
      let imgPane = document.getElementById("updatePaneImageInput");
      let imgIcon = document.getElementById("updateIconImageInput");
      let icon = iconArray[document.getElementById("Id").value];
      let responseText = document.getElementById("responseTextImage");

     if(imgPane.files[0])
     await addImage(image = imgPane.files[0], path = "..\\new_images\\", id = icon.pane_id);
     if(imgIcon.files[0])
     await addImage(image = imgIcon.files[0], path = "..\\images\\new_icons\\", id = icon.id, name = icon.name); 

    }

    function cleanUpString(str)
    {
      const map = {
          'Č': 'C', 'Ć': 'C', 'Đ': 'Dj', 'Š': 'S', 'Ž': 'Z',
          'č': 'c', 'ć': 'c', 'đ': 'dj', 'š': 's', 'ž': 'z', ' ':'-'
      };
      
      return str.trim().replace(/[ČĆĐŠŽčćđšž ]/g, char => map[char] || char); 
    }

    async function insertAnimal()
    {
      let optionSelected = document.getElementById("selectedAnimalNew");

      if(optionSelected.value === "")
      {
        let newName = document.getElementById("newName").value;

        if(newName === "")
          return;
          
        let name = cleanUpString(newName);
        let response = await insertRequest(name,"Icon");
        if(!response || !response.id)
          return;   

        let imgIcon = document.getElementById("newImage2");
        if(imgIcon.files[0])
          await addImage(image = imgIcon.files[0], path = "..\\images\\new_icons\\", id = response.id, name = name);
          
        setTimeout(()=>{
        iconArray.push({"coordinateh" : 0, "coordinatew" : 0,  "endangered_level" : "", "id" : response.id,"klasa" : "", "latin_title" : "", "name" :name, "pane_id" : -1, "porodica" : "" , "rasprostranjenost" : "", "red" : "", "staniste" : ""  , "tekst" : "", "title" : newName, "zivotni_vek" : "" });
        let icont = new AnimalIcon({iconUrl: PATH +'images/new_icons/'+ name +'.png'});
        let newMarker = L.marker([0, 0], { icon: icont, draggable:true }).addTo(map)
                .on('click',()=> fillInputsWithAnimalInfo(response.id, iconArray.length - 1 , iconArray ) )
                .on('click',()=> toggleSidebar(ifClosed = true) )
                .on('mouseup', (event)=> updateCoords(event, response.id));
        
        let option = document.createElement('option');
        option.value = iconArray.length - 1;
        option.textContent = newName;
        selectElem.appendChild(option); 
        }, 1000);

        
        let iconId = response.id;
        let response1 = await insertRequest(newName, "Info");
        if(!response1 || !response1.id)
        {
          map.removeLayer(newMarker);
          iconArray.pop();
          return;
        }
        let imgPane = document.getElementById("newImage1");

        if(imgPane.files[0])
          await addImage(image = imgPane.files[0], path = "..\\new_images\\", id = response1.id);

        await updateIconRequest(iconId,response1.id);
      }
      else
      {
        let optionIndex = optionSelected.value;
        let response = await insertRequest(iconArray[optionIndex].name, "Icon");
        if(!response || !response.id)
          return;    
        await updateIconRequest(response.id, iconArray[optionIndex].pane_id);
      }

 
    }

    function toggleSidebar(ifClosed) {
    const sidebar = document.getElementById("sidebar");
    if(ifClosed && sidebar.classList.contains("open"))
        return
    sidebar.classList.toggle("open");
    }

    function toggleSection(id) {
    const section = document.getElementById(id);
    section.style.display = section.style.display === 'block' ? 'none' : 'block';
    }

    function selectedAnimalChanged()
    {
      if(document.getElementById("selectedAnimalNew").value !== "")
      {
        document.getElementById("newNameLabel").style.display = "none";
        document.getElementById("newName").style.display = "none";
        document.getElementById("newImageLabel1").style.display = "none";
        document.getElementById("newImage1").style.display = "none";
        document.getElementById("newImageLabel2").style.display = "none";
        document.getElementById("newImage2").style.display = "none";
      }
      else
      {
        document.getElementById("newNameLabel").style.display = "inline";
        document.getElementById("newName").style.display = "inline";
        document.getElementById("newImageLabel1").style.display = "inline";
        document.getElementById("newImage1").style.display = "inline";
        document.getElementById("newImageLabel2").style.display = "inline";
        document.getElementById("newImage2").style.display = "inline";
      }
    }
    </script>
</head>
<body>
    <form>
    <a id="logout-button" href = "./Requests/logout.php" type = "submit">Logout</a>
    </form>

    <div id ="map" ></div>

    <button class="toggle-button" onclick="toggleSidebar(false)">Toggle Form</button>

<div id="sidebar" class="sidebar">

    <div class="sidebar-section">
    <div class="section-title" onclick="toggleSection('section1')">
        Update Animal Info
    <span>+</span>
    </div>
        <div class="section-content" id="section1">
        <form method="put" action="/api/Updates/2">
            <label for="Id">Animal Id:</label>
            <input id="Id" type="number" name="id" class="small" style="background-color:#ccc" readonly />
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
            <textarea id="mediumInput1" type="text" name="staniste" class="medium"></textarea>
            <br>
            <label for="mediumInput2">Životni vek:</label>
            <textarea id="mediumInput2" type="text" name="zivotni_vek" class="medium"></textarea>
            <br>
            <label for="mediumInput3">Rasprostranjenost:</label>
            <textarea id="mediumInput3" type="text" name="rasprostranjenost" class="medium"></textarea>
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
            <button type="button" onclick="formSubmitted()">Submit</button>
            <span id="responseText"></span>
            <br>

        </form>
        </div>
    </div>
     <div class="sidebar-section">
        <div class="section-title" onclick="toggleSection('section2')">
            Update Animal Images
            <span>+</span>
        </div>
        <div class="section-content" id="section2">
            <label for="updatePaneImageInput">Pane image</label>
            <input type="file" id="updatePaneImageInput" />
            <span id="responseTextImage1"></span>
            <br>
            <label for="updateIconImageInput">Icon image</label>
            <input type="file" id="updateIconImageInput" />
            <span id="responseTextImage2"></span>
            <br>
            <button type="button" onclick="uploadAnimalImages()">Update</button>
        </div>
    </div>   <br>
    <!-- 🔹 New Add New Section -->
    <div class="sidebar-section">
        <div class="section-title" onclick="toggleSection('section3')">
            Add New Icon
            <span>+</span>
        </div>
        <div class="section-content" id="section3">
            <select id="selectedAnimalNew" onChange = "selectedAnimalChanged()">
              <option value = "">New Animal</option> 
            </select><br>
            <label id = "newNameLabel"for="newName">Animal Name:</label>
            <input type="text" id="newName" name="newName" placeholder="Enter name" /><br><br>
            <label id = "newImageLabel1" for="newImage1">Image 1:</label>
            <input type="file" id="newImage1" name="newImage1" /><br><br>

            <label id = "newImageLabel2" for="newImage2">Image 2:</label>
            <input type="file" id="newImage2" name="newImage2" /><br><br>

            <button type="button" onclick="insertAnimal()">Insert</button>
            <span id="insertResponseText"></span>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
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
