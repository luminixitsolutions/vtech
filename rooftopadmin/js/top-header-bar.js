(function ($) {
    'use strict';

    function mergeSearchIndex(primary, extra) {
        var seen = {};
        var out = [];
        (primary || []).concat(extra || []).forEach(function (row) {
            if (!row || !row.url || seen[row.url]) {
                return;
            }
            seen[row.url] = true;
            out.push(row);
        });
        return out;
    }

    function sidenavSearchEntries() {
        var base = String($('#topHeaderSearchForm').data('base') || '');
        var items = [];
        var seen = {};

        $('#layout-sidenav a.sidenav-link[href]').each(function () {
            var href = String($(this).attr('href') || '').trim();
            if (!href || href.indexOf('javascript') === 0 || href === '#') {
                return;
            }
            var title = $(this).find('div').first().text().trim();
            if (!title) {
                title = $(this).text().trim();
            }
            if (!title) {
                return;
            }
            var url = href;
            if (url.indexOf('://') === -1 && url.charAt(0) !== '/') {
                url = base + url;
            }
            if (seen[url]) {
                return;
            }
            seen[url] = true;
            items.push({
                title: title,
                url: url,
                group: 'Sidebar',
                keywords: (title + ' ' + url).toLowerCase()
            });
        });

        return items;
    }

    function initMenuSearch() {
        var $form = $('#topHeaderSearchForm');
        var $input = $('#topHeaderSearchInput');
        var $panel = $('#topHeaderSearchResults');
        if (!$form.length || !$input.length || !$panel.length) {
            return;
        }

        var index = [];
        try {
            index = JSON.parse(String($('#adminMenuSearchIndex').text() || '[]'));
        } catch (err) {
            index = [];
        }
        index = mergeSearchIndex(index, sidenavSearchEntries());

        var timer = null;

        function hidePanel() {
            $panel.removeClass('is-open').empty().attr('aria-hidden', 'true');
        }

        function showResults(rows) {
            $panel.empty();
            if (!rows.length) {
                $panel.append('<div class="top-header-search-empty">No matching pages</div>');
            } else {
                rows.forEach(function (row) {
                    var $btn = $('<button type="button" class="top-header-search-item"></button>');
                    $btn.append(
                        $('<span class="top-header-search-item-title"></span>').text(row.title),
                        $('<span class="top-header-search-item-group"></span>').text(row.group || '')
                    );
                    $btn.on('click', function () {
                        window.location.href = row.url;
                    });
                    $panel.append($btn);
                });
            }
            $panel.addClass('is-open').attr('aria-hidden', 'false');
        }

        function localFilter(q) {
            q = q.toLowerCase();
            var out = [];
            for (var i = 0; i < index.length; i++) {
                var row = index[i];
                var hay = (row.title + ' ' + row.group + ' ' + (row.keywords || '')).toLowerCase();
                if (hay.indexOf(q) !== -1) {
                    out.push(row);
                    if (out.length >= 25) {
                        break;
                    }
                }
            }
            return out;
        }

        function runSearch() {
            var q = $.trim($input.val());
            if (q.length < 1) {
                hidePanel();
                return;
            }
            showResults(localFilter(q));
        }

        $input.on('input', function () {
            clearTimeout(timer);
            timer = setTimeout(runSearch, 180);
        });

        $input.on('focus', function () {
            if ($.trim($input.val()).length) {
                runSearch();
            }
        });

        $form.on('submit', function (e) {
            var q = $.trim($input.val());
            var first = $panel.find('.top-header-search-item').first();
            if (first.length) {
                e.preventDefault();
                first.trigger('click');
                return;
            }
            if (!q.length) {
                e.preventDefault();
            }
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.top-header-search-wrap').length) {
                hidePanel();
            }
        });
    }

    $(function () {
        initMenuSearch();
    });
}(jQuery));
