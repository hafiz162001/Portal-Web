<html>
    <head>
        <title>BLOCX/EVORIA</title>
        <meta property="description" content="{{ $description }}"> {{--  desc product --}}
        <meta property="og:title" content="{{ $title }}"> {{--  title product --}}
        <meta property="og:url" content="http://evoria.id/">
        <meta property="og:type" content="article" />
        <meta property="og:description" content="{{ $description }}"> {{--  desc product --}}
        <meta property="og:image" content="{{ $link_image }}">
        <meta property="og:site_name" content="Evoria">
    </head>
    <body>
        <script>
            function getMobileOperatingSystem() {
                var userAgent = navigator.userAgent || navigator.vendor || window.opera;
                var linkWeb = "http://evoria.id/";
                var storelink = "";
                var html = "<html>";
                html += '<head>';
                html += '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
                html += '</head>';
                html += "<body style='width:100%;padding: 0;margin: 0;height: 100%;' >";            
                if (/android/i.test(userAgent)) {
                    var link = "{{ $uri }}";
                    // link = "onesmile://?path=status-payment&id=245";
                    var storelink =  "{{ $android_link }}";
                    html += "<br/>  <a href='"+storelink+"' style='display: block;background-color: khaki;color: black;text-decoration: none;padding: 10px;border: 1px solid black;border-radius: 5px;margin: 0 0 10% 35%;bottom: 0;width: 25%;text-align: center;position: fixed;'  >Open Store</a>";
                    html += "</body>";
                    html += "</html>";
                    document.write(html);
                    window.location.replace(link);
                    return;
                } 
                if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                    var link = "{{ $uri }}"
                    storelink = "{{ $ios_link }}";
                    html += "<br/> <a href='"+storelink+"' style='display: block;background-color: khaki;color: black;text-decoration: none;padding: 10px;border: 1px solid black;border-radius: 5px;margin: 0 0 10% 35%;bottom: 0;width: 25%;text-align: center;position: fixed;'  >Open Store</a>";
                    html += "</body>";
                    html += "</html>";
                    document.write(html);
                    window.location.replace(link);
                    return
                } 
                window.location.replace(linkWeb);
                return
            }
            location.onLoad(getMobileOperatingSystem());
            </script> 
    </body>
</html>