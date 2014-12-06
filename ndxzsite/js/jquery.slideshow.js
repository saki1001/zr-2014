var active = 0;
var zindex = 999;
var disable_click = false;

function next()
{
	var tmp = img.length;	
	active = active + 1;
	if ((active + 1) > tmp) active = 0;
	getNode(img[active]);
}

function previous()
{
	var tmp = img.length;
	active = active - 1;
	if ((active + 1) == 0) active = (tmp - 1);
	getNode(img[active]);
}

function getNode(id) 
{
	// display loading
	loading();

	// get the grow content via ajax
	$.post(baseurl + '/ndxzsite/plugin/ajax.php', { jxs : 'slideshow', i : id, z : zindex }, 
		function(html) 
		{
			fillShow(html.output, html.height, html.mime);
			disable_click = false;
	//});
	}, 'json');
		
	return false;
}

function loading()
{
	// remove previous and next slides
	$('a#slide-next').remove();
	$('a#slide-previous').remove();
	
	// get height of current #slideshow
	var h = $('#slideshow').height();
	var html = "<div id='loading'><span>" + (active + 1) + "/" + total + "</span></div>";

	$('.picture').prepend(html);
	
	return;
}


function adjust_height(next)
{
	// only if text is below the image
	if (placement == true) return false;

	var adjust = 0;
	var current_height = $('#slideshow').height();
	
	if (current_height > next)
	{
		// larger
		adjust = (current_height - next);
		
		// animate
		$('#slideshow').animate({height: (current_height - adjust)}, 100);
	}
	else if (current_height < next)
	{
		// smaller
		adjust = (next - current_height);
		
		// animate
		$('#slideshow').animate({height: (current_height + adjust)}, 100);
	}
	else
	{
		// do nothing
	}
}

function fillShow(content, next_height, mime)
{	
	// animate
	if ((fade == true))
	{
		$('#slideshow').append(content);
		
		var adj_height = $('#slideshow div#slide' + zindex).height();
		
		// get height of #slideshow
		adjust_height(adj_height);

		$('#slideshow div#slide' + (zindex + 1)).fadeOut('200').delay(3000).queue(function(next){$(this).remove();});
		$('#slideshow div#slide' + zindex).fadeIn('200');
	}
	else
	{
		//$('#slideshow').replaceWith(content);
		$('#slideshow').append(content);
		
		var adj_height = $('#slideshow div#slide' + zindex).height();
		
		// get height of #slideshow
		adjust_height(adj_height);

		$('#slideshow div#slide' + (zindex + 1)).remove();
		$('#slideshow div#slide' + zindex).fadeIn(0);
	}
	
	// count down
	zindex--;
}

$.fn.preload = function() 
{
    this.each(function()
	{
        $('<img/>')[0].src = baseurl + '/files/gimgs/' + this;
    });
}

$(document).keydown(function(e)
{
	if (e.keyCode == 37) { 
		if (disable_click == true) return false;
		disable_click = true;
		previous();
	}

	if (e.keyCode == 39) { 
		if (disable_click == true) return false;
		disable_click = true;
		next();
	}
});