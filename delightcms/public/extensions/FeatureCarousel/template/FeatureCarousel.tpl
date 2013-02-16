<div id="playerbuttons{FETCAR_NB}" class="playerbuttons">
    <a class="but_prev" href="#">
        <img src="public/extensions/FeatureCarousel/images/player-prev.png" alt="" />
    </a>
    <a class="but_pause" href="#">
        <img src="public/extensions/FeatureCarousel/images/player-pause.png"  alt="" />   
    </a>
    <a class="but_start" href="#">
        <img src="public/extensions/FeatureCarousel/images/player-start.png" alt="" />    
    </a>
    <a class="but_next" href="#">
        <img src="public/extensions/FeatureCarousel/images/player-next.png" alt="" />    
    </a>
</div>

<div id="carouselContainer{FETCAR_NB}" class="carousel-container">
    <p class="carousel-left"><img src="public/extensions/FeatureCarousel/images/arrow-left.png" alt="" /></p>
    <p class="carousel-right"><img src="public/extensions/FeatureCarousel/images/arrow-right.png" alt="" /></p>
    
    <div id="carousel_{FETCAR_NB}">
        {FETCAR_IMAGE}
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var carousel = $("#carousel_{FETCAR_NB}").featureCarousel({
            carouselSpeed : {CAROUSELSPEED},
            instanceId: {FETCAR_NB}
        });

        $("#playerbuttons{FETCAR_NB} .but_prev").click(function () {
            carousel.prev();
        });
        $("#playerbuttons{FETCAR_NB} .but_pause").click(function () {
            carousel.pause();
        });
        $("#playerbuttons{FETCAR_NB} .but_start").click(function () {
            carousel.start();
        });
        $("#playerbuttons{FETCAR_NB} .but_next").click(function () {
            carousel.next();
        });
    });
</script>