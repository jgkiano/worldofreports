$(".mobile-sand h3 i").click(function() {
	$(".mobile-menu-items").toggle();
});
var h;
h = $(window).height() - $("#desktop-nav").height();
if($(window).width() > 992) {
	$("#hero-box").height(h);
}
$("#desktop-nav li").click(function() {
	$(".dropdown").removeClass("show-dropdown");
	switch($(this).attr("id")) {
		case "countries-link":
			$("#country-dropdown").addClass("show-dropdown");
			break;
		case "industry-link":
			$("#industry-dropdown").addClass("show-dropdown");
			break;
		case "company-link":
			$("#company-dropdown").addClass("show-dropdown");
			break;
		case "economy-link":
			$("#economy-dropdown").addClass("show-dropdown");
			break;
		case "consumer-link":
			$("#consumer-dropdown").addClass("show-dropdown");
			break;
		case "solutions-link":
			$("#solutions-dropdown").addClass("show-dropdown");
			break;
		case "cost-data-link":
			$("#cost-data-dropdown").addClass("show-dropdown");
			break;
	}
	
});

