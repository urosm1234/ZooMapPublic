

function updateCoords(e, database_id)
{
    const apiUrl = "/api/Updates/1"
    const data = {
        id: database_id,
        coordinatesH: Math.round(e.latlng.lat * 10)/10,
        coordinatesW: Math.round(e.latlng.lng * 10)/10,
        title: '',
        desc1:'',
        desc2:'',
        desc3:'',
        paragraph:''
    };
    const requestOptions = {
        method: 'PUT',
        headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
        },
    body: JSON.stringify(data),
    };

    fetch(apiUrl, requestOptions)
        .then(response => {
            if (!response.ok) {
            throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            outputElement.textContent = JSON.stringify(data, null, 2);
        })
        .catch(error => {
            console.log(error);});
    console.log(e.latlng);
}


function updateAnimal(database_id, titlei, desc1i, desc2i, desc3i, paragraphi){
    console.log(titlei);
    const apiUrl = "/api/Updates/2";
    const data = {
        id: database_id,
        coordinatesH: 0,
        coordinatesW: 0,
        title:titlei,
        desc1: desc1i,
        desc2: desc2i,
        desc3: desc3i,
        paragraph:paragraphi
    };

    const requestOptions = {
        method: 'PUT',
        headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
        },
    body: JSON.stringify(data),
    };
    console.log(requestOptions.body);
    fetch(apiUrl, requestOptions)
        .then(response => {
            if (!response.ok) {
            throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            outputElement.textContent = JSON.stringify(data);
        })
        .catch(error => {
            console.log(error);});
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

            document.getElementById("animal-pane").src = '/images/panes/' + iconArray[animal_id].name+"-pane.jpg";
            document.getElementById("animal-window").style.display = 'block';
            return 1;
        }
        else
        return 0;
   
    }

function togglePoppup(animal, iconArray)
{
    console.log(animal);
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
    

     /*if (popup.classList.contains('show')) {
            // If the map is shown, apply the hide animation
            popup.classList.remove('show');
            popup.classList.add('hide');

            // After the fade animation completes, set display to none
            setTimeout(function() {
                popup.classList.add('hide-complete');
                popup.classList.remove('hide');
            }, 250); // Match with the fadeOut animation duration
        } else {
            // If the map is hidden, remove hide-complete and apply the show animation
            console.log("great");
            result = await changeView(animal);
            console.log(result);

            popup.classList.remove('hide-complete');
            popup.classList.add('show');
        }*/
}

function getCoord(e)
{
    var coord = e.latlng;
    var lat = coord.lat;
    var lng = coord.lng;
    console.log(lat + "," + lng);
}    
    
function addIconsToMap(iconArray)
{
    iconArray.forEach( (icon) => {
            
            var icont = new AnimalIcon({iconUrl: '/images/icons/'+icon.name+'.png'});
            var marker = L.marker([icon.coordinatesH, icon.coordinatesW], { icon: icont, draggable:true }).addTo(map)
            .on('click',()=> togglePoppup(icon.array_id, iconArray) )
            .on('mouseup', (event)=> updateCoords(event, icon.database_id, iconArray));
        });
}
    
    
    