/**
 Creating a worker:
   var worker = [Worker(src)|SharedWorker(src,name)]
 Events:
   Worker:
     message = { data }
     error = { }
   SharedWorker: connect, message, error
  
 **/

var port;
this.addEventListener( "connect", function( evt ) {
    port = evt.ports[0]; //evt.target;

    port.addEventListener( "message", function( evt ) {
        port.postMessage( "inside:" + evt.data );
    },
    false );
    port.start();
},
false );
