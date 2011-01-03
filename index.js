$(function() {
  var $body = $('body');
  $body.addClass('tabbed');

  /* Moved to a @import rule in resume.css to avoid redraw on load

  // Add a stylesheet for the tabbed version
  $('head').append( $('<link />').attr( {
    rel : "stylesheet",
    href : "tabbed.css",
    type : "text/css",
  } ) );
  */

  /** Add top level HTML/jQuery tabs **/

  // Begin by collecting the current body into a single root
  var $origroot = $('<div/>');
  $body.children().appendTo( $origroot );
  $body.append( $origroot );
  
  var $top = $("<div id='top'><ul></ul></div>");
  $body.prepend( $top );

  // Match the height of the jquery display to the window
  function matchTabHeight( $container, $holder, $tab ) {
    $tab.height( $container.innerHeight()
                 - parseInt( $container.css( 'padding-top' ).replace( /px$/, '' ) )
                 - parseInt( $container.css( 'padding-bottom' ).replace( /px$/, '' ) )
                 - $holder.children( '.ui-tabs-nav' ).innerHeight()
                 - parseInt( $holder.css( 'border-top-width' ).replace( /px$/, '' ) )
                 - parseInt( $holder.css( 'border-bottom-width' ).replace( /px$/, '' ) )
                 - parseInt( $holder.css( 'padding-top' ).replace( /px$/, '' ) )
                 - parseInt( $holder.css( 'padding-bottom' ).replace( /px$/, '' ) )
                 - parseInt( $tab.css( 'padding-top' ).replace( /px$/, '' ) )
                 - parseInt( $tab.css( 'padding-bottom' ).replace( /px$/, '' ) )
                 - 2
                );
  }

  var currentTabId;
  var $toptabs = $top.tabs( {
    tabTemplate : "<li><a href='#{href}'>#{label}</a></li>",
    show : function( event, ui ) {
      // Show event fires for inner tabs as well as top tabs
      if( event.target.id == 'top' ) {
        if( currentTabId ) {
          $body.removeClass( currentTabId );
        }
        $body.addClass( ui.panel.id );
        currentTabId = ui.panel.id;
      }
    },
  } );

  // Callback to move the current body elements to the contents of a tab
  var moveBody = function( event, ui ) {
    $origroot.fadeOut( 'fast', function() {
      $(ui.panel).append( $origroot );
      $origroot.fadeIn( 'fast' );
    } );
  };
  $toptabs.bind( 'tabsadd', moveBody );
  $toptabs.tabs( 'add', '#htmltab', '<acronym title="Hypertext Markup Language">HTML</acronym>' );
  $toptabs.unbind( 'tabsadd', moveBody );

  var $root;
  $toptabs.tabs( {
    add : function( event, ui ) {
      $root = $(ui.panel);
    },
  } );
  $toptabs.tabs( 'add', '#jquerytab', 'jQuery <acronym title="User Interface">UI</acronym>' );
  
  var $jquerytab = $('#jquerytab');

  $toptabs.bind( 'tabsshow', function( event, ui ) {
    if( ui.panel.id == 'jquerytab' ) {
      // Different dimensions are reported post-loading
      var $window = $(window);
      $window.innerHeight = function() {
        return window.innerHeight
      };
      $window.css = function( prop ) {
        return $('body').css( prop );
      };
      matchTabHeight( $window, $top, $(ui.panel) );
    }
  } );

  var $box =   $('<div/>').css( {
    position : 'absolute',
    top : ( $top.children( '.ui-tabs-nav' ).outerHeight()
            + parseInt( $top.css( 'padding-top' ).replace( /px$/, '' ) ) ),
    height : ( window.innerHeight
               - $top.children( '.ui-tabs-nav' ).outerHeight()
               - parseInt( $top.css( 'padding-top' ).replace( /px$/, '' ) ) ),
    width : '100%',
    'margin-top' : '-2px',
    'margin-left' : '-2px',
    border : '1px solid',
  } )
  //    .appendTo( $body );

  var $areas = $('<div id="areas"><ul></ul></div>');
  $root.append( $areas );
  var $topictabs = $areas.tabs( {
    tabTemplate : '<li><a href="#{href}">#{label}</a></li>',
  } );

  $(window).click( function() {
    console.log( '.ui-tabs-nav.height() = ' + $areas.children( '.ui-tabs-nav' ).height() );
    console.log( '.ui-tabs-nav.innerHeight() = ' + $areas.children( '.ui-tabs-nav' ).innerHeight() );
    console.log( '.ui-tabs-nav.outerHeight() = ' + $areas.children( '.ui-tabs-nav' ).outerHeight() );
    return;
    console.log( 'document.body.clientHeight = ' + document.body.clientHeight );
    console.log( '$top.innerHeight() = ' + $top.innerHeight() );
    console.log( '$top.outerHeight() = ' + $top.outerHeight() );
    console.log( '$top.height() = ' + $top.height() );
    console.log( 'window.height = ' + window.height );
    console.log( '.ui-tabs-nav.height() = ' + $top.children( '.ui-tabs-nav' ).height() );
    console.log( '.ui-tabs-nav.innerHeight() = ' + $top.children( '.ui-tabs-nav' ).innerHeight() );
    console.log( '.ui-tabs-nav.outerHeight() = ' + $top.children( '.ui-tabs-nav' ).outerHeight() );
    console.log( 'window.innerHeight = ' + window.innerHeight );
    console.log( 'window.outerHeight = ' + window.outerHeight );
    $box.css( {
      top : ( $top.children( '.ui-tabs-nav' ).outerHeight()
              + parseInt( $top.css( 'padding-top' ).replace( /px$/, '' ) ) ),
      height : ( window.innerHeight
                 - $top.children( '.ui-tabs-nav' ).outerHeight()
                 - parseInt( $top.css( 'padding-top' ).replace( /px$/, '' ) ) ),
    } );
  } );
  $(window).click();

  // Runs to populate the tab version
  function tabVersion() {
    $toptabs.unbind( 'tabsshow', tabVersion );

    var tabcount = 0;
    $origroot.find( '#toplist' ).children().each( function( idx, elem ) {
      var $src = $(elem);
      if( $src.children().hasClass( 'title' ) ) {
        $topictabs.tabs( {
          add : function( event, ui ) {
            console.log( ui.panel.id );
            matchTabHeight( $jquerytab, $areas, $(ui.panel) );
            parseResumeItem( $src, $(ui.panel) );
          },
          show : function( event, ui ) {
          },
        } );
        var title = $src.children( '.title' ).text();
        $topictabs.tabs( 'add', '#tabs-' + ++tabcount, title );
      }
    } );
  }

  $toptabs.bind( 'tabsshow', tabVersion );

  function parseResumeItem( $src, $root ) {
    console.log( $src.attr( 'id' ) );
    if( $src.attr( 'id' ) == 'work' ) {
      var $headlist = $('<ul id="headers"/>');
      var $proplist = $('<ul id="props"/>');
      $src.children().children( 'li' ).each( function( idx, elem ) {
        var $pitem = $('<li/>');
        $pitem.append( $(elem).children( 'ul' ).clone() );
        $proplist.append( $pitem );
      
        var $hitem = $('<li/>');
        $hitem.append( $(elem).children( '.org' ).clone() );
        $hitem.append( $(elem).children( '.role' ).clone() );
        $hitem.click( function() {
          var offset = $pitem.offset().top;
          $('#props').animate(
            {
              scrollTop: offset,
            },
            1000
          );
        } );
      
        $headlist.append( $hitem );
      } );
      $headlist.children().each( function( idx ) {
        console.log( idx )
      } )

      $root.append( $headlist );
      $root.append( $proplist );

      var $scroller = $('<div id="scroller"/>');
      $scroller.append( $('<div/>').height( $headlist.get(0).scrollHeight ) );
      $scroller.scroll( function( event ) {
        $headlist.scrollTop( $scroller.scrollTop() )
       } );
      $scroller.width( 12 ); // Chrome doesn't include the scrollbar in the width

      $root.prepend( $scroller );
      
      // display table-cell doesn't scroll correctly
      $proplist.width( $root.width()
                       - $headlist.width()
                       - parseInt( $root.css( 'padding-left' ).replace( /px$/, '' ) )
                       - parseInt( $root.css( 'padding-right' ).replace( /px$/, '' ) )
                       );
      console.log($headlist.width());
      console.log($proplist.width());
    } else {
      $src.children().clone().remove( '.title' ).appendTo( $root );
    }
  }

  // Allow linking to the top level tabs
  if( document.location.hash != '' ) {
    $toptabs.tabs( 'select', document.location.hash );
  }
} );
