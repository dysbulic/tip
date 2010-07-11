var Hello = {
    connection: null,
    start_time: null,

    log: function (msg) {
        $('#log').append("<p>" + msg + "</p>");
    },

    send_ping: function (to) {
        var ping = $iq( {
            to: to,
            type: "get",
            id: "ping1",
        }).c( "ping", { xmlns: "urn:xmpp:ping" } )

        Hello.log("Sending ping to " + to + ".")

        Hello.start_time = (new Date()).getTime()
        Hello.connection.send(ping)
    },

    handle_pong: function(iq) {
        var elapsed = (new Date()).getTime() - Hello.start_time
        Hello.log("Received pong from server in " + elapsed + "ms.")

        Hello.connection.disconnect()
        
        return false
    },
}

$(function() {
    function connect() {
        $(document).trigger( 'connect', {
            jid: $('#jid').val(),
            password: $('#password').val()
        } )
        $('#password').val('')
        var $this = $(this)
        $this.dialog !== undefined && $this.dialog('close')
    }

    var $input = $('#login_dialog')
    if( $input.dialog !== undefined ) {
        $input.dialog( {
            autoOpen: true,
            draggable: false,
            modal: true,
            title: 'Connect to XMPP',
            buttons: {
                Connect: connect,
            },
        } )
    } else {
        $input.append( $( '<input type="submit" value="Connect"/>' )
                       .click( connect ) )
    }
} )

var BOSH_SERVICE = 'http://bosh.metajack.im:5280/xmpp-httpbind'
var BOSH_SERVICE = 'http://localhost:5280/http-bind'
$(document).bind( 'connect', function( event, data ) {
    var connection = new Strophe.Connection( BOSH_SERVICE )

    connection.connect( data.jid, data.password, function (status) {
        if( status === Strophe.Status.CONNECTED ) {
            $(document).trigger( 'connected' )
        } else if( status === Strophe.Status.DISCONNECTED ) {
            $(document).trigger( 'disconnected' )
        } else if( status === Strophe.Status.CONNECTING ) {
            Hello.log( 'Connecting:' )
        }
    } )

    Hello.connection = connection
} )

$(document).bind( 'connected', function() {
    Hello.log( 'Connection established:' )

    Hello.connection.addHandler( Hello.handle_pong, null, 'iq', null, 'ping1' )

    var domain = Strophe.getDomainFromJid( Hello.connection.jid )
    
    Hello.send_ping( domain )
} )

$(document).bind( 'disconnected', function() {
    Hello.log( 'Connection terminated:' )
    Hello.connection = null;
} )
