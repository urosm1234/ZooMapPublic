
function findNodeDist(coords1, coords2)
{
    /*console.log(coords1);
    console.log(coords2);*/
    let returnValue = 0.0;
    returnValue +=Math.abs((coords1[0] - coords2[0])**2 + (coords1[1] - coords2[1])**2);
    return returnValue;
}


function findClosestNode(coords)
{
    let index = 0,returnIndex, minDist = 300000;
    nodeMatrix.forEach(node => {
        let dist = findNodeDist(coords, node[0]);
        if(dist< minDist)
        {
            minDist = dist;
            returnIndex = index;
        }
        index++;
    });
    return returnIndex;
}

function findClosestPath(coords)
{
    let minDist = 300000, returnIndex1 = -1, returnIndex2=-1, index = 0;
    let k=0.0, n=0.0;
    nodeMatrix.forEach(node =>{
        let coords1 =  node[0];
        for(let i =1;i<node.length;i++)
        {
            let coords2 = nodeMatrix[node[i]][0];
            k = (coords1[0] - coords2[0]) / (coords1[1] - coords2[1]);
            n = coords[0]+k*coords[1];
            let x = (n-(coords1[0] -k*coords1[1]))/(2*k);
            let y = coords1[0] -k*coords1[1]+k*x;
            if(x<Math.max(coords1[1],coords2[1]) && x>Math.min(coords1[1],coords2[1]) && y<Math.max(coords1[0],coords2[0]) && y>Math.min(coords1[0],coords2[0]))
            {
                let dist = findNodeDist(coords, [y, x]);
                if(dist< minDist)
                {
                    minDist = dist;
                    returnIndex1 = index;
                    returnIndex2 = i;
                }
                if(dist<10)
                    return[returnIndex1, returnIndex2];
            }
        }
        index++;
    });
    return[returnIndex1, nodeMatrix[returnIndex1][returnIndex2]];
}

function findShortestRoute(coordsStart, coordsEnd)
{
    let endNode = findClosestNode(coordsEnd);
    console.log(endNode)
    let startNodes = findClosestPath(coordsStart);
    console.log(startNodes);

    const visited = Array(nodeMatrix.length).fill(0);
    const t = Array(nodeMatrix.length).fill(-1);
    const nodeDistances = Array(nodeMatrix.length).fill(-1.00);
    let curr = 0;
    
    if(nodeMatrix[endNode] == nodeMatrix[startNodes[0]])
        return [nodeMatrix[endNode][0],nodeMatrix[startNodes[0]][0]];

    if(nodeMatrix[endNode] == nodeMatrix[startNodes[1]])
        return [nodeMatrix[endNode][0],nodeMatrix[startNodes[1]][0]];


    nodeDistances[startNodes[0]] = findNodeDist(nodeMatrix[startNodes[0]][0], coordsStart);
    nodeDistances[startNodes[1]] = findNodeDist(nodeMatrix[startNodes[1]][0], coordsStart);
    t[startNodes[0]] = nodeDistances.length;
    t[startNodes[1]] = nodeDistances.length;

    let index = -1, min=-1;
    do{
        index = -1, min = -1;
        for(let i =0;i<nodeDistances.length;i++)
        {
            if(visited[i])
                continue;
            if(nodeDistances[i] > -1 && (nodeDistances[i]<min || min==-1))
            {
                index = i;
                min = nodeDistances[i];
            }
        }
        if(index!=-1)
        {
            visited[index] = 1;
            for(let i =1;i<nodeMatrix[index].length;i++)
            {
                let dist = findNodeDist(nodeMatrix[index][0], nodeMatrix[nodeMatrix[index][i]][0]);
                if(nodeDistances[nodeMatrix[index][i]]<0 || dist + min < nodeDistances[nodeMatrix[index][i]])
                {
                    t[nodeMatrix[index][i]] = index;
                    nodeDistances[nodeMatrix[index][i]] = dist + min;
                }
            }
        }
    } while(index !=-1)
    
    let out = [nodeMatrix[endNode][0]];
    let pom = endNode;
    while(t[pom]!=nodeDistances.length)
    {
        out.push(nodeMatrix[pom][0]);
        pom = t[pom];
    }

    out.push(nodeMatrix[pom][0]);
    out.push(coordsStart);
    return out;
}

function drawPath(coordsStart, coordsEnd)
{
            L.polyline(findShortestRoute(coordsStart, coordsEnd), {
                color: 'green',
                weight: 10,
                dashArray: '2, 15', // Pattern for the dashes: 5px dash, 10px gap
                }).addTo(map);
}