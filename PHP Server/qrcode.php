<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="pure JS WiFi QR Code Generator">
    <meta name="author" content="Evgeni Golov">
    <meta name="flattr:id" content="menw3k">
    <title>pure JS WiFi QR Code Generator</title>
    <link rel="stylesheet" type="text/css" media="all" href="style.css">
    <link rel="stylesheet" type="text/css" media="all" href="style-responsive.css">
    <link rel="stylesheet" type="text/css" media="print" href="print.css">
    <link rel="stylesheet" href="github-fork-ribbon-css/gh-fork-ribbon.css" />
    <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
</head>
<body>
    <div class="container">
    <section id="generator">
    <div class="page-header"><h1 id="title">pure JS WiFi QR Code Generator</h1></div>
    <div id="history"></div>
    <form id="form">
        <div class="form-group has-feedback">
            <label for="ssid" class="control-label">SSID</label>
            <div class="input-group">
                <span id="basic-addon-ssid" class="input-group-addon"><i class="glyphicon glyphicon-signal"></i></span>
                <input type="text" id="ssid" name="ssid" class="form-control" placeholder="SSID" aria-describedby="basic-addon-ssid" required>
            </div>
        </div>

        <div class="form-group">
            <label for="enc" class="control-label">Encryption</label>
            <div class="input-group">
                <select name="enc" id="enc" class="form-control">
                    <option value="WPA">WPA/WPA2</option>
                    <option value="WEP">WEP</option>
                    <option value="nopass">None</option>
                </select>
            </div>
        </div>

        <div class="form-group has-feedback" id="key-p">
            <label class="control-label">Key</label>
            <div class="input-group">
                <span id="basic-addon1" class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" id="key" name="key" class="form-control" placeholder="key" aria-describedby="basic-addon1" required>
                <span class="input-group-btn"><button class="btn btn-default" type="button" id="display-key"><i class="glyphicon glyphicon-eye-open" id="display-key-icon"></i></button></span>
           </div>
        </div>

        <div class="form-group">
            <label class="pull-left control-label" for="hidden">Hidden</label>
            <div class="input-group">
                <input type="checkbox" name="hidden" id="hidden" data-toggle="tooltip" title="Is your SSID hidden?">
            </div>
        </div>

        <div class="form-group">
            <button id="generate" class="btn btn-primary">Generate!</button>
            <button id="save" type="button" class="btn" data-toggle="tooltip" title="Save the credentials for the WiFi in HTML5 localStorage for later use.">Save!</button>
            <a href="#" id="export" class="btn" target="_blank" data-toggle="tooltip" title="Export the QR code as a PNG.">Export!</a>
            <a href="javascript:window.print()" id="print" data-toggle="tooltip" title="Print the QR code.">Print!</a>
        </div>
    </form>
    <h1 id="showssid">SSID: none</h1>
    <h1 id="showkey">Passphrase: none</h1>
    <div id="qrcode"></div>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/jquery.qrcode.min.js"></script>
    <script src="js/jquery.storage.js"></script>
    <script type="text/javascript">
        function escape_string (string) {
            var to_escape = ['\\', ';', ',', ':', '"'];
            var hex_only = /^[0-9a-f]+$/i;
            var output = "";
            for (var i=0; i<string.length; i++) {
                if($.inArray(string[i], to_escape) != -1) {
                    output += '\\'+string[i];
                }
                else {
                    output += string[i];
                }
            }
            //if (hex_only.test(output)) {
            //    output = '"'+output+'"';
            //}
            return output;
        };

        function generate () {
            var ssid = $('#ssid').val();
            var hidden = $('#hidden').is(':checked');
            var enc = $('#enc').val();
            if (enc != 'nopass') {
                var key = $('#key').val();
                $('#showkey').text(enc+' Passphrase: '+key);
            } else {
                var key = '';
                $('#showkey').text('');
            }
            // https://github.com/zxing/zxing/wiki/Barcode-Contents#wi-fi-network-config-android-ios-11
            var qrstring = 'WIFI:S:'+escape_string(ssid)+';T:'+enc+';P:'+escape_string(key)+';';
            if (hidden) {
                qrstring += 'H:true';
            }
            qrstring += ';';
            $('#qrcode').empty();
            $('#qrcode').qrcode(qrstring);
            $('#showssid').text('SSID: '+ssid);
            $('#save').show();
            $('#print').css('display', 'inline-block');

            var canvas = $('#qrcode canvas');
            if (canvas.length == 1) {
                var data = canvas[0].toDataURL('image/png');
                var e = $('#export');
                e.attr('href', data);
                e.attr('download', ssid+'-qrcode.png');
                // e.show() sets display:inline, but we need inline-block
                e.css('display', 'inline-block');
            }
        };

        function save () {
            var ssid = $('#ssid').val();
            if (!ssid) return;
            var hidden = $('#hidden').is(':checked');
            var enc = $('#enc').val();
            var key = $('#key').val();
            var storage = $.localStorage('qificodes');
            if (!storage) storage = {};
            storage[ssid] = {'hidden': hidden, 'enc': enc, 'key': key};
            $.localStorage('qificodes', storage);
            loadhistory();
        };

        function load(ssid) {
            var storage = $.localStorage('qificodes');
            if (ssid in storage) {
                $('#ssid').val(ssid);
                $('#enc').val(storage[ssid]['enc']);
                $('#key').val(storage[ssid]['key']);
                $('#hidden').prop('checked', storage[ssid]['hidden']);
                generate();
            }
        };

        function loadhistory () {
            var storage = $.localStorage('qificodes');
            if (storage) {
                var history = $('#history-drop ul.dropdown-menu');
                var ssids = Object.keys(storage);
                history.empty();
                for (var i=0; i<ssids.length; i++) {
                    history.append('<li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="history-item">'+ssids[i]+'</a></li>');
                }
                history.append('<li role="presentation" class="divider"></li>');
                history.append('<li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="history-clear">clear history</a></li>');

                history.on('click', 'a.history-item', function(e) {
                    e.preventDefault();
                    load($(this).text());
                });
                history.on('click', 'a.history-clear', function(e) {
                    e.preventDefault();
                    clearhistory();
                });
                $('#history-drop').show();
            }
        };

        function clearhistory () {
            $.localStorage('qificodes', null);
            $('#history-drop').hide();
        };

        $(document).ready(function(){
            $('#form').submit(function() {
                generate();
                return false;
            });
            $('#save').click(function() {
                save();
            });
            $('#display-key').click(function() {
                var $key = $("#key");
                if ($key.attr('type') === 'password') {
                    $key.attr('type', 'text');
                    $("#display-key-icon").attr("class", "glyphicon glyphicon-eye-close");
                } else {
                    $key.attr('type', 'password');
                    $("#display-key-icon").attr("class", "glyphicon glyphicon-eye-open");
                }
            });
            $('#enc').change(function() {
                if($(this).val() != 'nopass') {
                   $('#key-p').show();
                   $('#key').attr('required','required');
                }
                else {
                   $('#key-p').hide();
                   $('#key').removeAttr('required');
                }
            });
            $('#form').tooltip({
                selector: "[data-toggle=tooltip]"
            });
            loadhistory();
        });
    </script>
</body>
</html>
