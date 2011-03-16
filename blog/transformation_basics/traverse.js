// Do a depth first traversal and set the positions
function traverse(node, pre, post) {
    if( pre !== undefined ) {
        pre.call(node);
    }
    if( node.children && node.children.length > 0 ) {
        $.each(node.children, function(idx, child) {
            traverse(child, pre, post);
        });
    }
    if( post !== undefined ) {
        post.call(node);
    }
}
