(function($){
    "use strict";
    $.fn.jpAjaxScroll = function(options, asd)
    {
        var settings = $.extend({
            trigger : false,
			postdata : {},
			fncall : '',
            container: $(this),
            scroll: false,
            distance : 0,
            start : function(){},
            complete : function(){},
            debug : false
        }, options);

        var scope = $(this);
        var loading = false;

        var log = function(obj) { if (settings.debug && console.log != undefined) { console.log(obj); } }
        log('initialized');
        log(scope);
        log(settings);

        $(this).on('click', settings.trigger, function(){			
			settings.start();
            loading = true;
			$.ajax({
				url: base_url + fncall,
				data:postdata,
				type: "POST",
				dataType:"json",
				global:false,
				/*beforeSend: function() {
					if(rescls != ""){
						$("."+rescls).html('<h4 class="text-center">Loading...</h4>');
					}
				},*/
			}).done(function(res){
				$("#next_page").val(res.next_page);
				if(scroll){container.append(res.leadDetail);}else{container.html(res.leadDetail);}
                loading = false;
				settings.complete();
			});
			
			/*
            log('triggered');
            settings.start();
            loading = true;
            var url = scope.find(settings.trigger).attr('href');
            log('requesting: ' + url);
            $('<div></div>').load(url, function() {
                var newScope = $(this);
                scope.find(settings.trigger).replaceWith(newScope.find(settings.trigger));
                scope.find(settings.container).append(newScope.find(settings.selector));
                loading = false;
                log('done');
                settings.complete();
            });
			*/
            return false;
        });

        if (settings.distance)
        {
            $(window).on('scroll',function(){
                if ($(document).scrollTop() >= ($(document).height() - $(window).height() - settings.distance))
                {
                    if (!loading)
                    {
                        $(settings.trigger).trigger('click');
						//loadHtmlData(settings);
                    }
                }
            });
        }
    };

})(jQuery);
/***** GET DYNAMIC DATA *****/
function loadHtmlDataN(settings){
	
	var postdata = settings.postdata || {};
	var fncall = settings.fncall || "";
	var container = settings.container || "lead/dynamicData";
	var scroll = settings.scroll || false;
	
	loading = true;
	$.ajax({
		url: base_url + fncall,
		data:postdata,
		type: "POST",
		dataType:"json",
		global:false,
		/*beforeSend: function() {
			if(rescls != ""){
				$("."+rescls).html('<h4 class="text-center">Loading...</h4>');
			}
		},*/
	}).done(function(res){
		$("#next_page").val(res.next_page);
		if(scroll){container.append(res.leadDetail);}else{container.html(res.leadDetail);}
		loading = false;
	});
}
/***** GET DYNAMIC DATA *****/
function loadHtmlData(data){
	
	var postData = data.postdata || {};
	var fnget = data.fnget || "";
	var controllerName = data.controller || controller;
	var rescls = data.rescls || "dynamicData";
	var scrollType = data.scroll_type || "";
	
	$.ajax({
		url: base_url + controllerName + '/' + fnget,
		data:postData,
		type: "POST",
		dataType:"json",
		global:false,
		/*beforeSend: function() {
			if(rescls != ""){
				$("."+rescls).html('<h4 class="text-center">Loading...</h4>');
			}
		},*/
	}).done(function(res){
		$("#next_page").val(res.next_page);
		if(!scrollType){$("."+rescls).html(res.leadDetail);}else{$("."+rescls).append(res.leadDetail);}
	});
}