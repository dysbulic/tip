var columns = [ 'First Name', 'Last Name', 'E-mail Address' ];

$(document).ready(function() {
    var stylesheet = $('<style/>').attr('type', 'text/css');
    $('head').prepend(stylesheet);
    var tableStyle = $.rule('table {}').appendTo(stylesheet).css('border-collapse', 'collapse');
    var elemStyle = $.rule('th, td {}').appendTo(stylesheet).css('border', '1px solid').css('padding', ' .1em .25em');
    var inputStyle = $.rule('table input {}').appendTo(stylesheet).css('width', '100%').css('border', 'none');

    var table = $('<table/>');
    table.head = $('<thead/>');
    table.body = $('<tbody/>');

    table.addRow = function(values) {
        if(!values) values = [];
        if(values.length > columns.length) {
            alert('Too many values: ' + values.length);
            return;
        }
        var row = $('<tr/>');
        table.body.append(row);
        $(values).each(function(idx, val) {
            row.append($('<td/>').append(val));
        });
        for(var i = values.length; i < columns.length; i++) {
            row.append($('<td/>').append($('<input/>').attr('type', 'text').bind('change', function() { alert($(this).val()); })));
        }
    }

    table.append(table.head).append(table.body);

    table.head.append('<tr/>');
    $(columns).each(function(idx, val) {
        table.head.children().append($('<th/>').append(val));
    });

    table.addRow();
    table.addRow(['Will', 'Holcomb', 'will@dhappy.org']);
    
    $('body').append(table);
});
