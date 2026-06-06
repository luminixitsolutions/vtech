(function () {
    'use strict';

    function hideAppLoader() {
        var loader = document.getElementById('app-page-loader');
        if (!loader || loader.getAttribute('data-hidden') === '1') {
            return;
        }

        loader.setAttribute('data-hidden', '1');

        if (window.jQuery) {
            window.jQuery(loader).fadeOut('slow');
            return;
        }

        loader.style.transition = 'opacity 0.4s ease';
        loader.style.opacity = '0';
        window.setTimeout(function () {
            loader.style.display = 'none';
        }, 400);
    }

    if (document.readyState === 'complete') {
        window.setTimeout(hideAppLoader, 300);
    } else {
        window.addEventListener('load', function () {
            window.setTimeout(hideAppLoader, 300);
        });
    }
})();
