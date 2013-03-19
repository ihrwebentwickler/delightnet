function Mediaplayer() {
    this.init = function (envirement) {
        this.objMediaplayerEnv = envirement.getEnv();
    };

    this.getEnvTestData = function () {
        var objTestData = {};
        objTestData.playerCode = this.objMediaplayerEnv.playerCode
        objTestData.os = this.objMediaplayerEnv.os;
        objTestData.browser = this.objMediaplayerEnv.browserName;
        objTestData.browserVersion = this.objMediaplayerEnv.browserVersion;
        objTestData.browserMinVersion = this.objMediaplayerEnv.minBrowserVersion;
        objTestData.video1 = this.objMediaplayerEnv.objMediaKeys[this.objMediaplayerEnv.mediakey].video1;
        objTestData.video2 = this.objMediaplayerEnv.objMediaKeys[this.objMediaplayerEnv.mediakey].video2;
        objTestData.audio1 = this.objMediaplayerEnv.objMediaKeys[this.objMediaplayerEnv.mediakey].audio1;
        objTestData.audio2 = this.objMediaplayerEnv.objMediaKeys[this.objMediaplayerEnv.mediakey].audio2;
     
        return objTestData;
    };

    this.getPlayerCode = function () {
        return this.objMediaplayerEnv.playerCode;
    };

    this.getFileType = function (strMediaType) {
        return this.objMediaplayerEnv.objMediaKeys[this.objMediaplayerEnv.mediakey][strMediaType + this.objMediaplayerEnv.playerCode];
    };
 
    this.playMedia = function(mediaDomObj ) {
        mediaDomObj.load();
        mediaDomObj.play();
  
        // future implementation exception handling by html-error-events-handling for media-env
        return false;
    };
};