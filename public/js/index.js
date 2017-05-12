$(".navbar-trigger").on("click", function () {
	$(".navbar-container").toggleClass("expanded");
 		if ($(".navbar-container").hasClass("expanded")) {
	 		$(".navbar-trigger").addClass('back');
	 	} else {
	 		$(".navbar-trigger").removeClass('back');
	 	}
});
$(".news-others-container").on("click", function () {
	alert('tar bikin ke related page nya ha');
});
function openAboutModal(){
	if ($(".navbar-container").hasClass("expanded")) {
		$(".navbar-container").removeClass("expanded");
 		$(".navbar-trigger").removeClass('back');
 	} else {
 		
 	}
	$("#aboutModal").modal();
}
function popNavbar(){
	if($(window).width() < 768){
		if ($(".navbar-container").hasClass("expanded")) {
	 		// $(".navbar-trigger").addClass('back');
	 	} else {
	 		$(".navbar-container").addClass('expanded');
	 		$(".navbar-trigger").addClass('back');
	 	}
	}
}

function failCoverPicture(image) {
    image.src = 'img/img-unavailable.png';
    return true;
}
$(window).on("resize", popNavbar);