var envirement = envirement || {};

var envirement = (function () {
    var strUserAgent = navigator.userAgent;
  
    /*
    Player-Codes:
    1: Ogv or webm
    2: Mp4
     
    Audio-Keys
    1: Ogg or mp3
    2: Mp3
      
    
    'MSIE'          =>'ie' >= 9                [PLAYERCODE 2]
    'Firefox'       =>'firefox' >= 3.5         [PLAYERCODE 1]
    'Chrome'        =>'chrome' >= 3            [PLAYERCODE 2]
    'OmniWeb'       =>'omniweb' >= 3           [PLAYERCODE 2]
    'Safari'        =>'safari' >= 3            [PLAYERCODE 2]
    'Opera Mobil'   =>'opera-mobile' >= 11.1   [PLAYERCODE 2]
    'Opera Mini'    =>'opera-mobile' >= 11.1   [PLAYERCODE 2]
    'Opera'         =>'opera' >= 10.5          [PLAYERCODE 1]
    'IEMobile'      =>'ie-mobile' >= 9         [PLAYERCODE 2]
    'BlackBerry'    =>'blackberry' >= 6        [PLAYERCODE 2]
    'Konqueror'     =>'konqueror' >= 3         [PLAYERCODE 2] 
     */
  
    var objEnv = {
        browserName: null,
        browserVersion: null,
        minBrowserVersion: null,
        os: null,
        playerCode: -1,
        objMediaKeys: [
        {
            video1: "ogv",
            video2: "mp4",
            audio1: "ogg",
            audio2: "mp3"
        },
        {
            video1: "webm",
            video2: "mp4",
            audio1: "ogg",
            audio2: "mp3"
        },
        {
            video1: "webm",
            video2: "mp4",
            audio1: "mp3",
            audio2: "mp3"
        }],

        mediakey: 0
    };

    var objOs = [
    {
        osName: "Macintosh",
        osIdentifier: "mac",
        isMobile: false
    },
    {
        osName: "Windows CE",
        osIdentifier: "win-ce",
        isMobile: true
    },
    {
        osName: "Windows Phone",
        osIdentifier: "win-ce",
        isMobile: true
    },
    {
        osName: "Windows",
        osIdentifier: "win",
        isMobile: false
    },
    {
        osName: "iPad",
        osIdentifier: "ios",
        isMobile: true
    },
    {
        osName: "iPhone",
        osIdentifier: "ios",
        isMobile: true
    },
    {
        osName: "iPod",
        osIdentifier: "ios",
        isMobile: true
    },
    {
        osName: "Android",
        osIdentifier: "android",
        isMobile: true
    },
    {
        osName: "Blackberry",
        osIdentifier: "blackberry",
        isMobile: true
    },
    {
        osName: "Symbian",
        osIdentifier: "symbian",
        isMobile: true
    },
    {
        osName: "WebOS",
        osIdentifier: "Webos",
        isMobile: true
    },
    {
        osName: "Linux",
        osIdentifier: "unix",
        isMobile: false
    },
    {
        osName: "FreeBSD",
        osIdentifier: "unix",
        isMobile: false
    },
    {
        osName: "OpenBSD",
        osIdentifier: "unix",
        isMobile: false
    },
    {
        osName: "NetBSD",
        osIdentifier: "unix",
        isMobile: false
    }];
    
    
    // ###############################################
    var objBrowser = [
    {
        browserName: "MSIE",
        browserIdentifier: "ie",
        minVersion: 9,
        versionSearch: "MSIE",
        playerCode: 2
    },
    {
        browserName: "Firefox",
        browserIdentifier: "firefox",
        minVersion: 3.5,
        versionSearch: "Firefox",
        playerCode: 1
    },
    {
        browserName: "Chrome",
        browserIdentifier: "chrome",
        minVersion: 3,
        versionSearch: "Chrome",
        playerCode: 2
    },
    {
        browserName: "OmniWeb",
        browserIdentifier: "omniWeb",
        minVersion: 3,
        versionSearch: "Version",
        playerCode: 2
    },
    {
        browserName: "Safari",
        browserIdentifier: "safari",
        minVersion: 3,
        versionSearch: "Version",
        playerCode: 2
    },
    {
        browserName: "Opera Mini",
        browserIdentifier: "opera-mini",
        minVersion: 11.1,
        versionSearch: "Opera Mini",
        playerCode: 2
    },
    {
        browserName: "Opera Mobile",
        browserIdentifier: "opera-mobile",
        minVersion: 11.1,
        versionSearch: "Version",
        playerCode: 2
    },
    {
        browserName: "Opera",
        browserIdentifier: "opera",
        minVersion: 10.5,
        versionSearch: "Version",
        playerCode: 1
    },
    {
        browserName: "IEMobile",
        browserIdentifier: "ie-mobile",
        minVersion: 9,
        versionSearch: "IEMobile",
        playerCode: 2
    },
    {
        browserName: "BlackBerry",
        browserIdentifier: "blackberry",
        minVersion: 6,
        versionSearch: "BlackBerry",
        playerCode: 2
    },
    {
        browserName: "Konqueror",
        browserIdentifier: "konqueror",
        minVersion: 3,
        versionSearch: "Konqueror",
        playerCode: 2
    }];
    
    /* keys for various playout-resolutions
     * 0:  video: vorbis (ogv) or mp4, audio: vorbis (ogg) or mp3
     * 1:  video: Matroska (webm) or mp4, audio: vorbis (ogg) or mp3
     * 2:  video: Matroska (webm) or mp4, audio: always mp3
     * -1: flash-fallback
     */
   
    function _setEnv() {
        var osId;
        var browserId;
        
        osId = _getOs();
        if (osId > -1) {
            objEnv.os = objOs[osId].osIdentifier; 
        }
        
        browserId = _getBrowser(); 
        if (browserId > -1) {
            objEnv.browserName = objBrowser[browserId].browserName;
            objEnv.minBrowserVersion = objBrowser[browserId].minVersion;
            objEnv.playerCode = objBrowser[browserId].playerCode;
            objEnv.browserVersion  = _getVersion(objBrowser[browserId].versionSearch);
        } 
    } 

    function _getOs() {
        var lengthOs = objOs.length;
        var osId = -1;
        
        for (var i = 0; i < lengthOs; i++) {
            if (strUserAgent.search(objOs[i].osName) > -1) {
                osId = i;
                break;
            }
        }
        return osId;
    }
    
    function _getBrowser() {
        var lengthBrowser = objBrowser.length;
        var browserId = -1;
        
        for (var i = 0; i < lengthBrowser; i++) {
            if (strUserAgent.search(objBrowser[i].browserName) > -1) {
                browserId = i;
                break;
            }
        }
        return browserId;
    }

    function _getVersion(versionSearchString) {
        var index =  strUserAgent.indexOf(versionSearchString);
        
        if (index == -1) {
            return null;
        }
        
        return parseFloat(strUserAgent.substring(index+versionSearchString.length+1));
    }

    return{
        getEnv: function(){
            _setEnv();
            return objEnv;
        }           
    };
}());