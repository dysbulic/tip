__.directions = __.directions || new IdentifiedStack();

__.directions.corporeal = new IdentifiedStack( {
    up: '↑',
    down: '↓',
    left: '←',
    right: '→',
    still: '☸'
} );

__.directions.cardinal = new IdentifiedStack( {
    north: __.directions.corporeal.up,
    down:  __.directions.corporeal.down,
    left:  __.directions.corporeal.left,
    right:  __.directions.corporeal.right,
    still:  __.directions.corporeal.still
} );

__.silent = true;

__.coin = __.coin || new IdentifiedStack();

__.coin.toss = new IdentifiedStack( {
    head: '头',
    tail: '尾'
} );
