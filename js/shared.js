

function updateCoords(e, name)
{

    let upadteName = "name="+name;

    let upadteCoordinatesH = "coordinatesh="+Math.round(e.latlng.lat * 10)/10;
    let upadtecoordinatesW = "coordinatesw="+Math.round(e.latlng.lng * 10)/10;

    let apiUri = "./Requests/updateCoords.php";
    const xhr = new XMLHttpRequest();

    // Configure it: POST-request for the URL /path/to/your-script.php
    xhr.open("POST", apiUri, true);
    
    // Set the request header
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Define what to do when the response comes back
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Successfully received response
            console.log("Response:", xhr.responseText);
             // Display the response in an alert box
        } else {
            console.log("Error:", xhr.statusText);
        }
    };
    const data = upadteName+"&"+upadteCoordinatesH+"&"+upadtecoordinatesW;
    xhr.send(data);
}


function updateAnimal(database_id, titlei, desc1i, desc2i, desc3i, paragraphi){
    let upadteTitle = "title="+ titlei;

    let upadteDesc1= "desc1="+desc1i;
    let upadteDesc2 ="desc2="+desc2i;
    let updateDesc3 ="desc3="+desc3i;
    let updateParagraph = "paragraph="+paragraphi;
    let updateId = "id="+database_id;
    let apiUri = "./Requests/updateText.php";
    const xhr = new XMLHttpRequest();

    // Configure it: POST-request for the URL /path/to/your-script.php
    xhr.open("POST", apiUri, true);
    
    // Set the request header
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Define what to do when the response comes back
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Successfully received response
            document.getElementById("responseText").innerText = xhr.responseText;
            if(xhr.responseText == "Success")
                document.getElementById("responseText").style.color = "green";
            else
                document.getElementById("responseText").style.color  = "red";
             // Display the response in an alert box
        } else {
            console.log("Error:", xhr.statusText);
        }
    };
    const data = updateId+"&"+upadteTitle+"&"+upadteDesc1+"&"+upadteDesc2 + "&" + updateDesc3 +"&" + updateParagraph;
    xhr.send(data);
    console.log(1);
}



function formatTitleAndParagraph(description)
{
    let index = description.indexOf("\n");
    let descTittle = description.substring(0, index);
    let descMain = description.substring(index,description.length);
    return "<strong><b>" +descTittle+ "</b></strong><br>" + descMain;
}

function changeView(animal_id, iconArray) {
     // Replace with custom filepath
        animal_id = animal_id-1;
        if(animal_id>=0 && animal_id<iconArray.length)
        {
            //Sets information of the selected animal
            document.getElementById("animal-title").children[0].innerHTML = iconArray[animal_id].title;
            if(iconArray[animal_id].desc1 != null && iconArray[animal_id].desc1 != "")
            document.getElementById("description").children[0].innerHTML = formatTitleAndParagraph(iconArray[animal_id].desc1);
            else
            document.getElementById("description").children[0].innerHTML = "";


            if(iconArray[animal_id].desc2 != null && iconArray[animal_id].desc2 != "")
            document.getElementById("description").children[1].innerHTML = formatTitleAndParagraph(iconArray[animal_id].desc2);
            else
            document.getElementById("description").children[1].innerHTML = "";

            if(iconArray[animal_id].desc3 != null && iconArray[animal_id].desc3 != "")
            document.getElementById("description").children[2].innerHTML = formatTitleAndParagraph(iconArray[animal_id].desc3);
            else
            document.getElementById("description").children[2].innerHTML = "";

            if(iconArray[animal_id].paragraph != null)
            document.getElementById("description").children[3].innerHTML = formatTitleAndParagraph(iconArray[animal_id].paragraph);
            else
            document.getElementById("description").children[3].innerHTML = "";

            document.getElementById("animal-pane").src = PATH+'images/panes/' + iconArray[animal_id].name+"-pane.jpg";
            document.getElementById("animal-window").style.display = 'block';
            return 1;
        }
        else
        return 0;
   
    }

function togglePoppup(animal, iconArray)
{
    var popup = document.getElementById("animal-window");
    if (popup.style.display == 'block')
    popup.style.display = 'none';
    else
    {
        
        if(animal!="none")
        {
            changeView(animal, iconArray);
        }
    }
    

}

var matrix = [];
var counter = 0, first = -1;
function getCoord(e)
{
    var coord = e.latlng;
    var lat = coord.lat;
    var lng = coord.lng;
    console.log(lat + "," + lng);
    
    let index = curr;
    nodeMatrix.push([[lat, lng]]);
    L.marker([lat, lng], 'red').on('click', ()=>connectNodes(index)).addTo(map);
    curr++;
    console.log(nodeMatrix);
}    

function connectNodes(index)
{
    if(first == -1)
    {
        first = index;
        console.log(first);
        return;
    }

    if(index!=first)
    {
        nodeMatrix[first].push(index);
        nodeMatrix[index].push(first);
        L.polyline([nodeMatrix[first][0], nodeMatrix[index][0]], {
            color: 'red',
            weight: 10,
            dashArray: '2, 15', // Pattern for the dashes: 5px dash, 10px gap
            }).addTo(map);
    }
    
    first = -1;
}

    
    