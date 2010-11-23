$(function() {
  $display = $("<div><ul></ul></div>");
  $('body').append($display);


  // tabs init with a custom tab template and an "add" callback filling in the content
  var $tabs = $display.tabs({
    tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",
    add: function( event, ui ) {
      var tab_content = "Tab " + tab_counter + " content.";
      $( ui.panel ).append( "<p>" + tab_content + "</p>" );
    }
  });

  var tab_counter = 1;
  function addTab(title) {
    $tabs.tabs("add", "#tabs-" + tab_counter, title);
    tab_counter++;
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

