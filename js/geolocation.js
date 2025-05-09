var testCoord = [44.8252326294156, 20.45535206794739];
var userPosition;
var positionMarker = null;
var pointCurr = null;


const startIcon = L.icon({iconUrl:PATH+"images/start_flag.png", iconSize:[50, 50], iconAnchor:   [20, 50]});
// specify popup options 
const customOptions = {
    closeButton: false,
    autoClose: true,
    'className' : 'custom-popup',
    offset: [-50, -85]
}


function createCustomPopup(text)
{
    return  `  <div class="custom-popup">
    <h3>${text}</h3>
    </div>`;
}

function findAproximatedCoords([lat, lng]) {
    const R = 6371.0; // km
    let x = R * Math.PI * lng / 180.0;

    let y = R * Math.PI * lat / 180.0;

    return [x, y];
}

function aproximateLocation(coords)
{ 
    coords = findAproximatedCoords(coords)
   
    let minDist = 3000000, returnIndex1 = -1, returnIndex2=-1, index = 0, newDot;
    let k=0.0,k1 =0.0, n=0.0, n1 =0.0;
    let counter = 0;
    nodeMatrix.forEach(node =>{
        let coords1 =  findAproximatedCoords(trueNodeMatrix[index]);
        for(let i =1;i<node.length;i++)
        {
            let coords2 = findAproximatedCoords(trueNodeMatrix[node[i]]);
            k = (coords1[0] - coords2[0]) / (coords1[1] - coords2[1]);
            if(k!=0.0)
            k1 = -1/k;
            else
            k1 = 9999999;
            n = coords1[0]-k*coords1[1];
            let x = (n-(coords[0] -k1*coords[1]))/(k1 - k);
            let y = k*x + n;

            if(x<=Math.max(coords1[1],coords2[1]) && x>=Math.min(coords1[1],coords2[1]) && y<=Math.max(coords1[0],coords2[0]) && y>=Math.min(coords1[0],coords2[0]))
            {
                
                let dist = findNodeDist(coords, [y, x]);

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
        let coords1 = findAproximatedCoords(trueNodeMatrix[returnIndex1]);
        let coords2 = findAproximatedCoords(trueNodeMatrix[returnIndex2]);
        let proportions = findNodeDist(coords1, newDot) / findNodeDist(coords1, coords2);
        return[[returnIndex1, nodeMatrix[returnIndex1][returnIndex2]], proportions];
    }
    else
    return[[returnIndex1, returnIndex1]];
}


function setPosition(Coords)
{
    console.log(Coords.coords)
    let res = aproximateLocation([Coords.coords.latitude, Coords.coords.longitude]);
    //let res = aproximateLocation(testCoord);
    if(res.length > 1)
    {
        /*console.log(findNodeDist(nodeMatrix[res[0][0]][0], nodeMatrix[res[0][1]][0]) / res[1]);
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
        pointCurr = [coords1[0] + ratio*Math.sin(radians), coords1[1] + ratio*Math.cos(radians)];*/
        
        pointCurr = [ (nodeMatrix[res[0][0]][0][0] + nodeMatrix[res[0][1]][0][0])/2, (nodeMatrix[res[0][0]][0][1] + nodeMatrix[res[0][1]][0][1])/2];
    }
    else
    {
        pointCurr = nodeMatrix[res[0][0]][0];
    }
    positionMarker = L.marker(pointCurr,{icon:startIcon}).bindPopup(createCustomPopup("You are here!"), customOptions).addTo(map);
}


