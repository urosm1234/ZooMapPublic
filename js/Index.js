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
    },
      //disableClusteringAtZoom:2,
      //spiderifyOnMaxZoom: false,
      spiderfyDistanceMultiplier:1.8 
    });
    async function addIconsToMap(map)
    {
        iconArray = await fetchAnimalsFromApi();
        let currIndex = 0;
        iconArray.forEach( (icon) => {
                var icont = new AnimalIcon({iconUrl: PATH +'images/new_icons/'+icon.name+'.png'});
                let marker = L.marker([icon.coordinateh, icon.coordinatew], { icon: icont, draggable:false });//.addTo(map);
                //icon.marker = marker;
                marker.on('click', ()=>optionSelected(icon, clear=true, panTo=false));
                if(icon.title)
                {
                    let index = currIndex;
                    marker.on('click',()=> togglePoppup(index, iconArray) );
                    //marker.bindPopup(icon.title);
                    markers.addLayer(marker);
                }
                else
                    marker.addTo(map);
                currIndex+=1;

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

