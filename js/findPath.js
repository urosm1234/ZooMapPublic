const PathColors = "#123458";

function findNodeDist(coords1, coords2)
{
    let returnValue = 0.0;
    returnValue +=Math.sqrt((coords1[0] - coords2[0])**2 + (coords1[1] - coords2[1])**2);
    return returnValue;
}

function findDistFromLine(coords, coords1, coords2)
{
    k = (coords1[1] - coords2[1]) / (coords1[0] - coords2[0]);
    if(k!=0.0)
    k1 = -1/k;
    else
    k1 = 9999999;
    n = coords1[1]-k*coords1[0];
    let x = (n-(coords[1] -k1*coords[0]))/(k1 - k);
    let y = k*x + n;

    console.log(`x:${x},y:${y}`);

    return findNodeDist(coords, [x, y]);
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
    let minDist = 3000000, returnIndex1 = -1, returnIndex2=-1, index = 0, newDot;
    let k=0.0,k1 =0.0, n=0.0, n1 =0.0;
    nodeMatrix.forEach(node =>{
        let coords1 =  node[0];
        for(let i =1;i<node.length;i++)
        {
            let coords2 = nodeMatrix[node[i]][0];
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
    return[[returnIndex1, nodeMatrix[returnIndex1][returnIndex2]], newDot];
    else
    return[[returnIndex1, returnIndex1]];
}




function findShortestRoute(coordsStart, coordsEnd)
{
    let goalNode = findClosestPath(coordsEnd);
    console.log(goalNode);
 
    if(goalNode.length == 2)
    {
        nodeMatrix.push([goalNode[1], goalNode[0][0], goalNode[0][1]]);
        nodeMatrix[goalNode[0][0]].push(nodeMatrix.length - 1);
        nodeMatrix[goalNode[0][1]].push(nodeMatrix.length - 1);

        
        endNode = nodeMatrix.length - 1;
    }
    else
    endNode = goalNode[0][0];

    let startNodes = findClosestPath(coordsStart)[0];

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

    let returnMarker;
    if(goalNode.length == 2)
    {
        nodeMatrix[goalNode[0][0]].pop();
        nodeMatrix[goalNode[0][1]].pop();
        nodeMatrix.pop();
        returnMarker = goalNode[1];
    }
    else
        returnMarker = nodeMatrix[goalNode[0][0]][0];

    return [out , returnMarker];
}

function drawPath(coordsStart, coordsEnd)
{
            let drawingMaterial = findShortestRoute(coordsStart, coordsEnd)
            let path = L.polyline(drawingMaterial[0], {
                smoothFactor:5.0,
                noClip:true,
                color: PathColors,
                weight: 10,
                dashArray: '5, 15' // Pattern for the dashes: 5px dash, 10px gap
                }).addTo(map);
            let returnMarker = L.marker(drawingMaterial[1]).addTo(map);
            return [path, returnMarker];
}
