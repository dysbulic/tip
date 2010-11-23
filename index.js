$(function() {
  
  var $body = $('body');
  $body.addClass('tabbed')

  $('head').append( $('<link />').attr( {
    rel : "stylesheet",
    href : "tabbed.css",
    type : "text/css",
  } ) );

  /** Add top level HTML/jQuery tabs **/

  // Begin by collecting the current body into a single root
  var $origroot = $('<div/>');
  $body.children().appendTo( $origroot );
  $body.append( $origroot );

  var $top = $("<div id='top'><ul></ul></div>");
  $body.prepend( $top );

  var $toptabs = $top.tabs( {
    tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",
  } );

  tabcount = 1;

  // Callback to move the current body elements to the contents of a tab
  var moveBody = function( event, ui ) {
    $origroot.fadeOut( 'fast', function() {
      $(ui.panel).prepend("<p>Test</p>");
      $(ui.panel).append( $origroot );
      $origroot.fadeIn( 'fast' );
    } );
  };
  $toptabs.tabs( { add: moveBody } );
  //$toptabs.bind( "tabsadd", moveBody );
  $toptabs.tabs("add", "#tabs-" + tabcount++, "<acronym title='Hypertext Markup Language'>HTML</acronym>");
  $toptabs.unbind( "tabsadd", moveBody );


  var $root;
  $toptabs.tabs( {
    add: function( event, ui ) {
      $root = $(ui.panel)
    }
  } );
  $toptabs.tabs("add", "#tabs-" + tabcount++, "jQuery <acronym title='User Interface'>UI</acronym>");
  
  var $areas = $("<div id='areas'><ul></ul></div>");
  var $topictabs = $areas.tabs( {
    tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",
    add: function( event, ui ) {
      $(ui.panel).append("<p>Test</p>");
    }
  } );

  function addTab(title) {
    $topictabs.tabs("add", "#tabs-" + tabcount++, title);
  }

  jQuery.getJSON( 'resume.json', function(data, textStatus, xhr) {
    // Runs on load to populate the initial tabs
    var category_index = 0;
    function addTabs() {
      if(category_index < data.categories.length) {
        addTab(data.categories[category_index++]);
        setTimeout(addTabs, 150);
      }
    }
    addTabs();
  } );
} );

