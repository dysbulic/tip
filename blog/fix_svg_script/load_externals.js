(function() {
    function getURL(obj) {
        var baseURL = 'http://projects.will.madstones.com/blog/';
        var title = obj.parents().find('h1, h2').eq(0).text();
        if(typeof title == 'undefined' || title == '') {
            title = window.location.pathname.toString().replace(/.*\/([^\/]+)\/.*/, '$1');
        }
        title = title.toLowerCase().replace(/-/g, '_').replace(/ /g, '_');
        var url = baseURL + title + '/';
        return url;
    }

if(typeof $ == 'undefined' && typeof jQuery != 'undefined')
    $ = jQuery;

if(typeof svgweb != 'undefined') {
    var svgwebLoadListener = svgweb._onDOMContentLoaded;
    svgweb._onDOMContentLoaded = function() {
        // IE doesn't allow appending to object
        // This executes, but the value doesn't change
        $('object').filter('[src]').each(function() {
            var url = getUrl($(this));
            var data = $(this).attr('src');
            $(this).attr('src', url + data);
        });
        svgwebLoadListener.apply(svgweb, arguments);
    }
}

$(document).ready(function() {
    // Convert from wordpress dashed to filesystem underscores
    if(document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1")) {
        $('object').filter('[data]').each(function() {
            var url = getURL($(this));
            var data = $(this).attr('data');
            if(data.substring(0, 7) != 'http://') {
                var children = $(this).children().remove();
                $(this).append($(this).clone().attr('data', url + data).append(children));
            }
        });
    }
});
})();
