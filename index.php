<?php require_once "includes/header.php"; ?>

<?php require_once "includes/hero.php"; ?>

<?php require_once "includes/footer.php"; ?>

<script type="text/javascript">

$(".mobile-sand h3 i").click(function() {
	$(".mobile-menu-items").toggle();
});

var h;

h = $(window).height() - $("#desktop-nav").height();

if($(window).width() > 992) {
	$("#hero-box").height(h);
}

</script>