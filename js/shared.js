
function updateCoords(e, id)
{

    let updateId = "id="+id;

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
    const data = updateId +"&"+upadteCoordinatesH+"&"+upadtecoordinatesW;
    xhr.send(data);
}

function updateAnimal(database_id , title,latin_title, red, porodica,staniste, zivotni_vek, rasprostranjenost, klasa, endangered_level, tekst){
    let upadteTitle = "title="+ title;
    let upadteLatin_title = "latin_title="+ latin_title;
    let upadteRed = "red="+ red;
    let upadtePorodica = "porodica="+ porodica;
    let upadteStaniste = "staniste="+ staniste;
    let upadteZivotni_vek = "zivotni_vek="+ zivotni_vek;
    let upadteRasprostranjenost = "rasprostranjenost="+ rasprostranjenost;

    let upadteKlasa= "klasa="+klasa;
    let upadteEndangered_level ="endangered_level="+endangered_level;
    let updateTekst = "tekst="+tekst;
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
    const data = updateId+"&"+upadteTitle+"&"+upadteLatin_title+"&"+upadteRed+"&"+upadtePorodica+"&"+upadteStaniste+"&"+upadteZivotni_vek+"&"+upadteRasprostranjenost+"&"+upadteKlasa+"&"+upadteEndangered_level+"&"+updateTekst;
    xhr.send(data);
    console.log(1);
}

function formatTitleAndParagraph(description)
{
    let index = description.indexOf("\n");
    let descTittle = description.substring(0, index);

    let descMain = description.substring(index,description.length);

    descMain = descMain[1].toUpperCase() + descMain.slice(2);
    
    return "<strong><b>" +descTittle+ "</b></strong><br>" + descMain;
}

function changeView(animal_id, iconArray) {
        if(animal_id>=0 && animal_id<iconArray.length)
        {
            let animal_title_h_tags = document.getElementById("animal-title").getElementsByTagName("h");

            animal_title_h_tags[0].innerHTML = iconArray[animal_id].title;
            animal_title_h_tags[1].innerHTML= iconArray[animal_id].latin_title;
            try{
                console.log(iconArray[animal_id].endangered_level)
                let endangered_levels = {"CR":["Critically endangered", "red"], "EN":["Endangered","orange"], "LC":["Least concern","green"],"VU":["Vulnerable", "#FFBF00"], "NT":["Near threatened", "#8A9A5B"]}
                animal_title_h_tags[2].style.color = endangered_levels[ iconArray[animal_id].endangered_level ][1];
                animal_title_h_tags[2].innerHTML = " "+iconArray[animal_id].endangered_level + " - "+ endangered_levels[ iconArray[animal_id].endangered_level ][0];
            }
            catch{
            document.getElementById("animal-title").getElementsByTagName("h")[2].innerHTML = ""
            }

            let targetParagraph = document.getElementById("description").children[0];
            let content = "";
            targetParagraph.innerHTML = "";

            if(iconArray[animal_id].staniste != null && iconArray[animal_id].staniste != "")
                content+=formatTitleAndParagraph("Stanište:\n"+iconArray[animal_id].staniste) + "<br><br>";

            if(iconArray[animal_id].zivotni_vek != null && iconArray[animal_id].zivotni_vek != "")
                content+="\n"+formatTitleAndParagraph("Životni vek:\n"+iconArray[animal_id].zivotni_vek)+ "<br><br>";

            if(iconArray[animal_id].rasprostranjenost != null && iconArray[animal_id].rasprostranjenost != "")
                content+="\n"+formatTitleAndParagraph("Rasprostranjenost:\n"+iconArray[animal_id].rasprostranjenost)+ "<br><br>";

            if(iconArray[animal_id].tekst != null && iconArray[animal_id].tekst != "")
                content+="\n"+formatTitleAndParagraph("\n"+iconArray[animal_id].tekst.replace("0S","°C"))+ "<br>";

            document.getElementById("animal-pane").src = PATH+'new_images/' + "animal"+iconArray[animal_id].id +".jpg";
            document.getElementById("animal-window").style.display = 'block';

            targetParagraph.innerHTML = content;
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

//var matrix = [];
//var counter = 0,*/ ;
function getCoord(e)
{
    try{
    var coord = e.latlng;
    var lat = coord.lat;
    var lng = coord.lng;
    console.log(lat + "," + lng);
    
    let index = curr;
    nodeMatrix.push([[lat, lng]]);
    //L.marker([lat, lng], 'red').on('click', ()=>connectNodes(index)).addTo(map);
    curr++;
    //console.log(nodeMatrix);
    }
    catch
    {
        return;
    }
}    

//TESTING ONLY FUNCTION
var first = -1;
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

    
    
