<div id="contact">
    <h3 class="title">
        {L:HEADER_MAP}
    </h3>
    <div id="map" class="open-map">
    </div>
    <div class="contentbox">
        <div class="container">
            <div class="grid-wrapper">
                <div class="col-6">
                    <p>
                        <span>Your company<br></span>
                        <span>your firstname lastname<br></span>
                        <span>streethello 10<br></span>
                        <span>010234 Berlin<br></span>
                    </p>
                </div>
                <div class="col-6">
                    <p>
                        <span class='fas fa-phone'></span>
                        <span>+49 1234 567898<br></span>
                        <span class='fas fa-envelope'></span>
                        <span>service</span>
                        <span class="material-icons">alternate_email</span>
                        <span>youremailaddress.com<br></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="divider">
    </div>
    <div class="margin-top-big">
        <h3>
            {L:HEADER_MESSAGE}
        </h3>
        <p>
            {L:INFO_MESSAGE}.
        </p>
        <form action="contact.html" method="post" role="form" class="contactform margin-top contentbox contact-grit">
            <div class="container">
                <div class="grid-wrapper">
                    <div class="col-12">
                        <label for="name">Name: <span>*</span></label>
                        <input type="text" required="" value="{DATA1}" name="emaildata[1]" id="name">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="grid-wrapper">
                    <div class="col-12">
                        <label for="email">Email: <span>*</span></label>
                        <input type="email" required="" value="{DATA2}" name="emaildata[2]" id="email">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="grid-wrapper">
                    <div class="col-12">
                        <label for="telefon">{L:FORM_PHONE}: <span>*</span></label>
                        <input type="tel" value="{DATA3}" name="emaildata[3]" id="telefon">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="grid-wrapper">
                    <div class="col-12">
                        <label for="emaildata[4]">{L:FORM_MESSAGE}: <span>*</span></label>
                        <textarea class="form-control" required="" rows="4" name="emaildata[4]">{DATA4}</textarea>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="grid-wrapper">
                    <div class="col-2">
                        <input type="text" name="captcha" {STYLE_KEYERROR}
                               placeholder="code" required="" maxlength="4">
                    </div>
                    <div class="col-3 text-align-right">
                        <span class="sync-icon load-captcha"><i class="material-icons">sync</i></span>
                    </div>
                    <div class="col-3">
                        <span id="captchaImage">{{Contact.captchaImage}}</span>
                    </div>
                    <div class="col-1">
                        <input name="contactform" type="submit" value="{L:SENDBTN}"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    #map {
        width: {MAP_WIDTH};
        height: {MAP_HEIGHT};
    }
</style>

<script>
    let map;
    let mapLat = {MAP_LATITUDE};
    let mapLng = {MAP_LONGITUDE};
    let mapDefaultZoom = {MAP_ZOOM};

    function initialize_map() {
        if (typeof map === 'undefined') {
            map = new ol.Map({
                target: "map",
                layers: [
                    new ol.layer.Tile({
                        source: new ol.source.OSM({
                            url: "https://a.tile.openstreetmap.org/{z}/{x}/{y}.png"
                        })
                    })
                ],
                view: new ol.View({
                    center: ol.proj.fromLonLat([mapLng, mapLat]),
                    zoom: mapDefaultZoom
                })
            });
        }
    }

    function add_map_point(lat, lng) {
        let vectorLayer = new ol.layer.Vector({
            source: new ol.source.Vector({
                features: [new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(lng), parseFloat(lat)], 'EPSG:4326', 'EPSG:3857')),
                })]
            }),
            style: new ol.style.Style({
                image: new ol.style.Icon({
                    anchor: [0.5, 0.5],
                    anchorXUnits: "fraction",
                    anchorYUnits: "fraction",
                    src: "https://upload.wikimedia.org/wikipedia/commons/e/ec/RedDot.svg"
                })
            })
        });
        map.addLayer(vectorLayer);
    }

    initialize_map();
    add_map_point({MAP_LATITUDE},{MAP_LONGITUDE});
</script>
