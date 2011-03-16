var rowNeighbor = [];
var depth = 0;
var currentVertex;
var drawDown = true; // Draw tree down or to the right

function messageToTree(message) {
    var node;
    switch ( message.type ) {
    case 'enter':
        node = new Vertex(message.content);
        break;
    case 'text':
        node = new Blob(message.content);
        break;
    case 'exit':
        // Back up a level and exit
        depth--;
        if ( currentVertex.parent ) {
            currentVertex = currentVertex.parent;
        }
        return;
    default:
        throw 'Unknown message type: ' + message.type;
    }
    
    var neighbor = rowNeighbor[depth];
    if ( neighbor ) {
        // Avoid colliding with neighbors
        if( drawDown ) {
            // ToDo: link to make dynamic if elements move
            node.center.x = neighbor.bbox.x + node.bbox.width * 2;
        } else {
            node.center.y = neighbor.bbox.y + node.bbox.height * 2;
        }
    }
    if ( currentVertex ) {
        if( drawDown ) {
            node.center = { x: Math.max(node.center.x, currentVertex.center.x),
                            y: currentVertex.center.y + (currentVertex.bbox.height * 1.75) };
        } else {
            node.center = { x: currentVertex.center.x + (currentVertex.bbox.width * 1.75),
                            y: Math.max(node.center.y, currentVertex.center.y) };
        }
        currentVertex.addChild(node);
    }
    rowNeighbor[depth++] = node;
    
    $.tip.$('#').append(node.$svg);
    currentVertex = node;
    
    return node;
}
