(function ($) {
    'use strict';

    function selectedForwardIds() {
        var ids = [];
        $('.msedcl-forward-check:checked').each(function () {
            var id = parseInt($(this).val(), 10);
            if (id > 0) {
                ids.push(id);
            }
        });
        return ids;
    }

    function syncSelectAllState() {
        var $enabled = $('.msedcl-forward-check:not(:disabled)');
        var $checked = $enabled.filter(':checked');
        var $selectAll = $('#msedclForwardSelectAll');
        if (!$selectAll.length || !$enabled.length) {
            $selectAll.prop('checked', false).prop('indeterminate', false);
            return;
        }
        $selectAll.prop('checked', $checked.length === $enabled.length);
        $selectAll.prop('indeterminate', $checked.length > 0 && $checked.length < $enabled.length);
    }

    $(document).on('change', '#msedclForwardSelectAll', function () {
        var checked = $(this).is(':checked');
        $('.msedcl-forward-check:not(:disabled)').prop('checked', checked);
        syncSelectAllState();
    });

    $(document).on('change', '.msedcl-forward-check', function () {
        syncSelectAllState();
    });

    $(document).on('click', '#msedclForwardBtn', function () {
        var ids = selectedForwardIds();
        if (!ids.length) {
            alert('Please select at least one customer to forward.');
            return;
        }
        if (!confirm('Forward ' + ids.length + ' selected customer(s) to Co-ordinator assign list?')) {
            return;
        }

        var $btn = $(this);
        var redirectUrl = $btn.data('redirect-url') || '';
        $btn.prop('disabled', true).text('Forwarding…');

        var postData = {};
        ids.forEach(function (id, idx) {
            postData['customer_ids[' + idx + ']'] = id;
        });

        $.ajax({
            url: 'ajax-forward-to-coordinator.php',
            type: 'POST',
            data: postData,
            dataType: 'json'
        }).done(function (res) {
            if (res && res.success) {
                if (res.redirect || redirectUrl) {
                    window.location.href = res.redirect || redirectUrl;
                    return;
                }
            }

            var msg = (res && res.message) ? res.message : (res && res.success ? 'Forwarded.' : 'Forward failed.');
            if (res && res.errors && res.errors.length) {
                msg += '\n\n' + res.errors.slice(0, 5).join('\n');
            }
            alert(msg);
            $btn.prop('disabled', false).html('<i class="feather icon-arrow-right mr-1"></i> Forward to Co-ordinator Assign');
        }).fail(function (xhr) {
            var msg = 'Forward request failed.';
            if (xhr.responseText) {
                try {
                    var parsed = JSON.parse(xhr.responseText);
                    if (parsed.message) {
                        msg = parsed.message;
                    }
                    if (parsed.success && parsed.redirect) {
                        window.location.href = parsed.redirect;
                        return;
                    }
                } catch (err) {
                    msg += '\n\n' + xhr.responseText.substring(0, 200);
                }
            }
            alert(msg);
            $btn.prop('disabled', false).html('<i class="feather icon-arrow-right mr-1"></i> Forward to Co-ordinator Assign');
        });
    });

    syncSelectAllState();
})(jQuery);
