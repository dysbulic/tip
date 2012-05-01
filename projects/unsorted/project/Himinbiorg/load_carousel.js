// Reformats the items in the projects table to work with the carousel
var carouselItems = new Array();
function prepItems(carouselId, displayId) {
    var transitionSpeed = 'fast';
    var fadeOpacity = .75;
    var origContent = $(displayId + '> div');
    $(carouselId).hover(
        function() { origContent.fadeOut(transitionSpeed); },
        function() { origContent.fadeIn(transitionSpeed); }
    );
    $(carouselId).find('li').each(function() {
        var content = ($('<div/>').append($(this).find('.title').remove()
                                          .append(" (").append($(this).find('.time').remove().text()).append(")")
                                         ).hide()
                       .append($(this).find('.description').remove()).hide());
        $(displayId).append(content);
        
        // The wrapping code calls empty() which removes event handlers, so they have to be readded each iteration
        carouselItems.push({
            image: $(this).find('.image').fadeTo(transitionSpeed, fadeOpacity),
            hoverOver: function() { $(this).fadeTo(transitionSpeed, 1); content.fadeIn(transitionSpeed); },
            hoverOut: function() { $(this).fadeTo(transitionSpeed, fadeOpacity); content.fadeOut(transitionSpeed); }
        });
    });
}

function initCallback(carousel) {
    // Enable autoscrolling on hover
    $('.jcarousel-next').hover(
        function() { carousel.options.scroll = 1; carousel.next(); carousel.options.auto = .5; return false; },
        function() { carousel.options.auto = 0; return false; });
    $('.jcarousel-prev').hover(
        function() { carousel.options.scroll = -1; carousel.next(); carousel.options.auto = .5; return false; },
        function() { carousel.options.auto = 0; return false; });

    // Save position when page is left
    $(window).unload(function() { $.cookie('list_index', carousel.first, { expires: 10 }); });
}

function itemVisibleInCallback(carousel, item, i, state, evt) {
    // The index() method calculates an index in the item range.
    var item = carouselItems[carousel.index(i, carouselItems.length) - 1];
    carousel.add(i, item['image']);
    item['image'].hover(item['hoverOver'], item['hoverOut']);
}

function itemVisibleOutCallback(carousel, item, i, state, evt) {
    //carousel.remove(i);
}

/**
 * Item html creation helper.
 */
function mycarousel_getItemHTML(item) {
    return '<img src="' + item.url + '" width="75" height="75" alt="' + item.title + '" />';
}
