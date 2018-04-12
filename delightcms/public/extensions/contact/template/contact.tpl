<div ng-controller="ContactController">
    <h3 class="title">
        {L:HEADER_MAP}
    </h3>

    <div my-map="">
    </div>
    <div class="standardbox adressdata">
        <span>Ihr Webentwickler,<br></span>
        <span>Gunnar von Spreckelsen<br></span>
        <span>Heinrich-Hauschildt-Straße 19<br></span>
        <span>25336 Elmshorn<br></span>
        <span class='fa fa-phone'></span>
        <span>+49 1525 3479704<br></span>
        <span class='fa fa-envelope'></span>
        <span>service</span>
        <span class='fa fa-at'></span>
        <span>ihrwebentwickler.de<br></span>
    </div>
    <div class="divider">
    </div>
    <div id="contact-form">
        <h3 class="title">
            {L:HEADER_MESSAGE}
        </h3>
        <p>
            {L:INFO_MESSAGE}.
        </p>
        <form action="contact.html" method="post" role="form">
            <fieldset>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Name: <span>*</span></label>
                        <input type="text" required="" value="{DATA1}" name="emaildata[1]" id="name"
                               class="form-control">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Email: <span>*</span></label>
                        <input type="email" required="" value="{DATA2}" name="emaildata[2]" id="email"
                               class="form-control">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>{L:FORM_PHONE}:</label>
                        <input type="text" value="{DATA3}" name="emaildata[3]" id="telefon"
                               class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>{L:FORM_MESSAGE}: <span>*</span></label>
                        <textarea class="form-control" required="" rows="4" name="emaildata[4]">{DATA4}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-2 col-xs-3">
                        <span captcha>{{Contact.captchaImage}}</span>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                        <input type="text" class="form-control" name="captcha" {STYLE_KEYERROR}
                               placeholder="code" required="" ng-maxlength=4>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                        <span class="recaptcha imageicon fa fa-refresh" ng-click="getCaptcha()"></span>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                        <div class="imagebutton">
                            <input name="contactform" type="submit" class="btn btn-primary fa"
                                   value="&nbsp;&nbsp;&#xf1d8;&nbsp;&nbsp;"/>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>

<script>
    GLOBAL.GMAPS = GLOBAL.GMAPS || {};

    GLOBAL.GMAPS.welcometext = GLOBAL.GMAPS.welcometext || "{GOOGLEMAP_WELCOMETEXT}";
    GLOBAL.GMAPS.actiontext = GLOBAL.GMAPS.actiontext || "{GOOGLEMAP_ACTIONTEXT}";
    GLOBAL.GMAPS.latitude = GLOBAL.GMAPS.latitude || "{GOOGLEMAP_LATITUDE}";
    GLOBAL.GMAPS.longitude = GLOBAL.GMAPS.longitude || "{GOOGLEMAP_LONGITUDE}";
    GLOBAL.GMAPS.zoom = GLOBAL.GMAPS.zoom || {GOOGLEMAP_ZOOM};
</script>

<style>
    #gmaps {
        width: {GOOGLEMAP_WIDTH};
        height: {GOOGLEMAP_HEIGHT};
    }
</style>