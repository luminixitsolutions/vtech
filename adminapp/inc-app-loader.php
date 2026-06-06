<?php
if (!empty($GLOBALS['app_loader_included'])) {
    return;
}
$GLOBALS['app_loader_included'] = true;

$loaderLogo = 'logo2.png';
if (!empty($SiteUrl)) {
    $loaderLogo = rtrim($SiteUrl, '/') . '/logo2.png';
}
?>
<style>
#app-page-loader.loader-display {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999;
    background: #f0f3f7;
    margin: 0;
    padding: 0;
}
#app-page-loader .app-loader-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    text-align: center;
}
#app-page-loader .app-loader-logo {
    margin-bottom: 18px;
}
#app-page-loader .app-loader-logo img {
    display: block;
    max-width: 160px;
    width: auto;
    height: auto;
    margin: 0 auto;
}
#app-page-loader .app-loader-dots {
    display: block;
    position: relative;
    width: 64px;
    height: 12px;
    margin: 0 auto;
}
#app-page-loader .app-loader-dots span {
    position: absolute;
    top: 0;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: #405189;
    animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
#app-page-loader .app-loader-dots span:nth-child(1) { left: 6px; animation: app-loader-ellipsis1 0.6s infinite; }
#app-page-loader .app-loader-dots span:nth-child(2) { left: 6px; animation: app-loader-ellipsis2 0.6s infinite; }
#app-page-loader .app-loader-dots span:nth-child(3) { left: 26px; animation: app-loader-ellipsis2 0.6s infinite; }
#app-page-loader .app-loader-dots span:nth-child(4) { left: 45px; animation: app-loader-ellipsis3 0.6s infinite; }
@keyframes app-loader-ellipsis1 {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}
@keyframes app-loader-ellipsis3 {
    0% { transform: scale(1); }
    100% { transform: scale(0); }
}
@keyframes app-loader-ellipsis2 {
    0% { transform: translate(0, 0); }
    100% { transform: translate(19px, 0); }
}
</style>
<div id="app-page-loader" class="loader-display">
    <div class="app-loader-inner">
        <div class="app-loader-logo">
            <img src="<?php echo htmlspecialchars($loaderLogo, ENT_QUOTES, 'UTF-8'); ?>" alt="VTECH" onerror="this.onerror=null;this.src='logoload.jpg';">
        </div>
        <div class="app-loader-dots" aria-hidden="true">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>
<script src="js/app-loader.js"></script>
