<script>
    (function() {
        var app = {
            launchApp: function() {
                window.location.replace("goNDA://prodcut/<?= $document_id ?>");
                this.timer = setTimeout(this.openWebApp, 1000);
            },

            openWebApp: function() {
                //window.location.replace("https://gonda.app/");
                const data = getOS();
                if (data == 'Mac OS' || data == 'iOS') {
                    window.location.replace(
                        "https://apps.apple.com/us/app/gonda-self-service-legal-help/id1574339237");
                } else if (data == 'Android') {
                    window.location.replace(
                        "https://play.google.com/store/apps/details?id=com.gonda&ah=t2TYMeUk7mrJsucBR5NSHGqJ9lA"
                    );
                } else {
                    window.location.replace("https://gonda.app/");
                }

            }
        };

        app.launchApp();
    })();

    function getOS() {
        var userAgent = window.navigator.userAgent,
            platform = window.navigator.platform,
            macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
            windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
            iosPlatforms = ['iPhone', 'iPad', 'iPod'],
            os = null;

        if (macosPlatforms.indexOf(platform) !== -1) {
            os = 'Mac OS';
        } else if (iosPlatforms.indexOf(platform) !== -1) {
            os = 'iOS';
        } else if (windowsPlatforms.indexOf(platform) !== -1) {
            os = 'Windows';
        } else if (/Android/.test(userAgent)) {
            os = 'Android';
        } else if (!os && /Linux/.test(platform)) {
            os = 'Linux';
        }

        return os;
    }
</script>
