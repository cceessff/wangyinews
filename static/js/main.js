$(function () {
    var surl = location.href;
    var surl2 = $(".place a:eq(1)").attr("href");
    $("#nav .clearfix li a").each(function () {
        if ($(this).attr("href") == surl || $(this).attr("href") == surl2) $(this).parent().addClass("on")
    });
});


$(document).ready(function() { 
var tags_a = $("#divTags a"); 
tags_a.each(function(){ 
var x = 9; 
var y = 0; 
var rand = parseInt(Math.random() * (x - y + 1) + y); 
$(this).addClass("tags"+rand); 
}); 
}) 



$(function() {
			var pull 		= $('#pull');
				menu 		= $('#nav>ul');
				menuHeight	= menu.height();

			$(pull).on('click', function(e) {
				e.preventDefault();
				menu.slideToggle();
			});

			$(window).resize(function(){
        		var w = $(window).width();
        		if(w > 320 && menu.is(':hidden')) {
        			menu.removeAttr('style');
        		}
    		});
		});

$(".search-on").click(function(){
    $(".sous").slideToggle();
    $(".search-on i").toggleClass("fa-close (alias)");
});

$("#nav ul li").hover(function () {
    $(this).addClass("hover").siblings().removeClass("hover");
    $(this).find("#nav ul li a").addClass("hover");
    $(this).find("#nav ul li ul").show();
}, function () {
    //$(this).css("background-color","#f5f5f5");
    $(this).find("#nav ul li ul").hide();
    //$(this).find(".nav a").removeClass("hover");
    $(this).removeClass("hover");
    $(this).find("#nav ul li a").removeClass("hover");
})

$("#nav li").hover(function() {
	if($(this).find("li").length > 0){
		$(this).children("ul").stop(true, true).slideDown(300)
	}
},function() {
	$(this).children("ul").stop(true, true).slideUp("fast");
});





		
$(document).ready(function() { 
    var tags_a = $("#divhottag a"); 
    tags_a.each(function(){ 
        var x = 6; 
        var y = 0; 
        var rand = parseInt(Math.random() * (x - y + 1) + y); 
        $(this).addClass("tags"+rand); 
    }); 
})  

$(document).ready(function() { 
    var tags_a = $("#divrandtag a"); 
    tags_a.each(function(){ 
        var x = 6; 
        var y = 0; 
        var rand = parseInt(Math.random() * (x - y + 1) + y); 
        $(this).addClass("tags"+rand); 
    }); 
})  

$(function(){
    $('.tx-tab-hd li').click(function(){
        $(this).addClass('tx-on').siblings().removeClass('tx-on');
        $('.tx-tab-bd ul').hide().eq($(this).index()).show();
    })
})