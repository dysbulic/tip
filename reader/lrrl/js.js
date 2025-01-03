var activeCallback = $.Callbacks()
WebFontConfig = {
    google: { families: ['Open+Sans:400italic,400,300,600:latin'] },
    active: function () { activeCallback.fire(); }
}

var lineHeight = 30

function redrawBlinds() {
    $topBlind.empty()
    $bottomBlind.empty()

    var lineNumber = 0
    var displayCount = 0

    console.log('ps', $('#text p').size(), $('p').size())
    
    $('#text p').each(function() {
	var $p = $(this)
	offset = $p.offset().top + 4
	
	for(var count = 0; count < ($p.height() / lineHeight); count++) {
	    var $line = $(document.createElementNS('http://www.w3.org/2000/svg', 'rect'))
	    $line.attr({
		x: 0,
		y: offset + ((count - 1) * lineHeight),
		width: '100%',
		height: lineHeight
	    })
	    if(++displayCount % 2 == 0) {
		$bottomBlind.append($line)
		$line.attr({ y: parseFloat($line.attr('y')) }) 
	    } else {
		$topBlind.append($line)
		$line.attr({ y: parseFloat($line.attr('y')) }) 
	    }
	}
    })

    $('#text h1').each(function() {
	var $h = $(this)
	console.log('h', $h.height(), $h.offset().top)
	var $line = $(document.createElementNS('http://www.w3.org/2000/svg', 'rect'))
	$line.attr({
	    x: 0,
	    y: $h.offset().top - 5,
	    width: '100%',
	    height: $h.height() - 12
	})
	$topBlind.append($line)
    })

    $('#text h2').each(function() {
	var $h = $(this)
	console.log('h', $h.height(), $h.offset().top)
	var $line = $(document.createElementNS('http://www.w3.org/2000/svg', 'rect'))
	$line.attr({
	    x: 0,
	    y: $h.offset().top - 18,
	    width: '100%',
	    height: $h.height() - 6
	})
	$topBlind.append($line)
    })

    $('body')[0].style.display = 'none'
    $('body')[0].offsetHeight
    $('body')[0].style.display = ''
}

$(document).click(function() {
    console.log('click')
    redrawBlinds()
})

$(function() {
    $topBlind = $(document.createElementNS('http://www.w3.org/2000/svg', 'clipPath'))
    $bottomBlind = $(document.createElementNS('http://www.w3.org/2000/svg', 'clipPath'))
    
    $topBlind.attr({ id: 'top' })
    $bottomBlind.attr({ id: 'bottom' })
    
    $('defs').append($topBlind)
    $('defs').append($bottomBlind)

    var $rev = $('#text').clone()
    $rev.attr({ id: 'reverse' })
    $('body').append($rev)

    redrawBlinds()
})
