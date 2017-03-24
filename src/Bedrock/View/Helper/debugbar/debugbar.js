//load jquery function
load = function() {
    if(typeof $  == "undefined") load.getScript("http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
    load.tryReady(0);
}

// dynamically load any javascript file.
load.getScript = function(filename) {
    var script = document.createElement('script');
    script.setAttribute("type","text/javascript");
    script.setAttribute("src", filename);
    if (typeof script!="undefined") document.getElementsByTagName("head")[0].appendChild(script);
}

load.tryReady = function(time_elapsed) {
    // Continually polls to see if jQuery is loaded.
    if (typeof $ == "undefined") { // if jQuery isn't loaded yet...
        if (time_elapsed <= 5000) { // and we havn't given up trying...
            setTimeout("load.tryReady(" + (time_elapsed + 200) + ")", 200); // set a timer to check again in 200 ms.
        } else {
			//we do nothing
            //alert("Timed out while loading jQuery.")
        }
    } else {
        $(function() {
			
			//setup body
			if($('body').attr('pkdebugbar-overflow') === undefined) {
				$('body').attr('pkdebugbar-overflow', $('body').css('overflow'));
			}
			
            //resize debug bar window to browser window
            $(window).resize(function(){
              
                var newheight = ($(window).outerHeight()) - 80,
                    newwidth = $('body').outerWidth() - 80;
                
                $('.window.resizable').css({ height: newheight, width : newwidth });
                
            });

            //check if starting minized
            if(pkdebugbar_start_minimized == true) pkdebugToggle();
        });
    }
}

// start loading
load();


function pkdebugShow(id) {
	var target = "#" + id + "_window";
	id = "#" + id;
		
	if($('body').attr('pkdebugbar-overflow') === undefined) {
		var body_overflow = $('body').css('overflow');
		$('body').attr('pkdebugbar-overflow', body_overflow);
	}
	else var body_overflow = $('body').attr('pkdebugbar-overflow');

	if($(id).hasClass("current")) {
		$(id).removeClass("current");
        $(target).hide();
		$('body').css('overflow', body_overflow);
	} else {
		pkdebugCloseAll();
		$(id).addClass("current");
		$(target).show();
		$('body').css('overflow', 'hidden');
	}
    //fit window to screen
    if($(target).hasClass('resizable')) {
		
		var newheight = ($(window).outerHeight()) - 60,
			newwidth = $('body').width() - 70;
			
        $(target).css('height',newheight);
        $(target).css('width',newwidth);
    }
}

function pkdebugCloseAll() {
	$("#pkdebugbar .window").hide();
	$("#pkdebugbar .pkdb_tab").removeClass("current");
	$('body').css('overflow', $('body').attr('pkdebugbar-overflow'));
}

function pkdebugToggle() {
	var selector = $("#pkdebugbar li a#hideshow");
	if(selector.hasClass("hidebar")) {
		pkdebugCloseAll();
        $("#pkdebugbar, #pkdebugbar .pkdbpanel").css({ width: '36px'});
		$("#pkdebugbar li").hide();
		$("#pkdebugbar li#togglebar").show();
        selector.removeClass("hidebar").addClass("showbar");
	} else {
        $("#pkdebugbar, #pkdebugbar .pkdbpanel").css({ width: '100%'});
		$("#pkdebugbar li").show();
		selector.removeClass("showbar").addClass("hidebar");
	}
}

function pkfullscreenToggle() {
    $("#pkdebugbar .window").addClass('fullscreen');
}