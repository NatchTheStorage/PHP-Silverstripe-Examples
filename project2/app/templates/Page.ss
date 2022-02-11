<!DOCTYPE html>
<html lang="en">
<head>
    <%-- Generates base element that makes all links relative to it --%>
    <% base_tag %>
    <title>
        <% if $Property %>
            <% with $Property %>
                <% if $PrettyAddress %>$PrettyAddress<% else %>{$Title}<% end_if %>&nbsp;|&nbsp;{$GetTypeTitle}&nbsp;|
            <% end_with %>
        <% else_if $SeoTitle %>{$SeoTitle}&nbsp;|
        <% else_if not $isHome %>{$Title}&nbsp;|
        <% end_if %>&nbsp;{$SiteConfig.Title}
    </title>

    <%-- Set character encoding for the document --%>
    <meta charset="utf-8">

    <%-- Instruct Internet Explorer to use its latest rendering engine --%>
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <%-- Set viewport settings --%>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <%-- Upgrade insecure requests to preserve https --%>
    <%--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">--%>

    <%-- Generates meta data, setting false stops it generating a title tag --%>
    $MetaTags(false)

    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <%-- Fav icons, etc --%>
    <% include MetaIcons %>

    <%-- Open Graph--%>
    <% include OpenGraph %>

    <%--AddEvent script--%>
    <script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
    <!-- AddEvent Settings -->
    <script type="text/javascript">



    window.addeventasync = function(){
        addeventatc.settings({
            appleical  : {show:true, text:"Apple Calendar"},
            google     : {show:true, text:"Google <em>(online)</em>"},
            office365  : {show:true, text:"Office 365 <em>(online)</em>"},
            outlook    : {show:true, text:"Outlook"},
            outlookcom : {show:true, text:"Outlook.com <em>(online)</em>"},
            yahoo      : {show:true, text:"Yahoo <em>(online)</em>"},

            css        : false
        });
    };
    </script>


    <%-- Require CSS --%>
    <% require themedCSS('css/dist/app') %>
    <% require themedCSS('css/dist/modaal.min') %>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;400;500;700;800&display=swap" rel="stylesheet">
    <link rel=“stylesheet” href=“https://use.typekit.net/rsd1rkx.css”>
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link rel="stylesheet" href="https://unpkg.com/flickity-fullscreen@1/fullscreen.css">

    <%-- Fav icons, etc --%>
    <% include MetaIcons %>

    <%-- Open Graph --%>
    <% include OpenGraph %>

    <!-- Google Tag Manager -->
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{$SiteConfig.GoogleTagManagerCode}');
    </script>

</head>
<body class="body">
    <% include Header %>
<div class="main" role="main">
    <% include PageBanner %>
    $Layout
    <% include CallToAction %>
</div>
    <% include Footer %>

    <% require themedJavascript('javascript/dist/countUp.umd.js') %>
    <% require themedJavascript('javascript/dist/app.js') %>

    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <script src="https://unpkg.com/flickity-bg-lazyload@1/bg-lazyload.js"></script>
    <script src="https://unpkg.com/flickity-fullscreen@1/fullscreen.js"></script>




<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id={$SiteConfig.GoogleTagManagerCode}" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
</noscript>


</body>
</html>
