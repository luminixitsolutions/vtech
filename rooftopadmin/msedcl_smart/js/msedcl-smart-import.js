(function ($) {
    'use strict';

    function showImportResult(res) {
        var msg = res.message || (res.success ? 'Import complete.' : 'Import failed.');
        if (res.errors && res.errors.length) {
            msg += '\n\n' + res.errors.slice(0, 10).join('\n');
        }
        alert(msg);
        if (res.success && res.redirect) {
            window.location.href = res.redirect;
        } else if (res.success) {
            window.location.reload();
        }
    }

    $(document).on('click', '.msedcl-smart-import-btn', function () {
        var $wrap = $(this).closest('.msedcl-smart-import-wrap');
        var $file = $wrap.find('.msedcl-smart-import-file');
        $file.data('ajax-url', $(this).data('ajax-url'));
        $file.data('import-type', $(this).data('import-type'));
        $file.data('redirect', $(this).data('redirect'));
        $file.val('').trigger('click');
    });

    $(document).on('change', '.msedcl-smart-import-file', function () {
        var file = this.files && this.files[0];
        if (!file) {
            return;
        }
        var ajaxUrl = $(this).data('ajax-url') || 'ajax-import-msedcl-smart-excel.php';
        var importType = $(this).data('import-type') || 'pmsgy';
        var redirect = $(this).data('redirect') || '';
        var fd = new FormData();
        fd.append('file', file);
        fd.append('import_type', importType);

        var $btn = $(this).closest('.msedcl-smart-import-wrap').find('.msedcl-smart-import-btn');
        $btn.prop('disabled', true).text('Importing…');

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function (res) {
            if (redirect && res.success) {
                res.redirect = redirect;
            }
            showImportResult(res || {});
        }).fail(function () {
            alert('Import request failed.');
        }).always(function () {
            $btn.prop('disabled', false).html('<i class="ion ion-md-cloud-upload mr-1"></i> Import Excel');
        });
    });

    $(document).on('click', '.msedcl-smart-action-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var customerId = $btn.data('customer-id');
        var action = $btn.data('action');
        var confirmMsg = $btn.data('confirm') || 'Continue?';
        if (!confirm(confirmMsg)) {
            return;
        }
        $btn.prop('disabled', true);
        $.post('ajax-msedcl-smart-action.php', { customer_id: customerId, action: action }, function (res) {
            alert(res.message || (res.success ? 'Done.' : 'Failed.'));
            if (res.success) {
                window.location.reload();
            } else {
                $btn.prop('disabled', false);
            }
        }, 'json').fail(function () {
            alert('Request failed.');
            $btn.prop('disabled', false);
        });
    });

    $(document).on('click', '.msedcl-smart-delete-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var customerId = $btn.data('customer-id');
        var listType = $btn.data('list-type') || '';
        var label = $btn.data('label') || 'this customer';
        var confirmMsg = $btn.data('confirm') || ('Delete ' + label + '?');
        if (!confirm(confirmMsg)) {
            return;
        }
        $btn.prop('disabled', true);
        $.post('ajax-delete-msedcl-smart-customer.php', {
            customer_id: customerId,
            list_type: listType
        }, function (res) {
            alert((res && res.message) ? res.message : (res && res.success ? 'Deleted.' : 'Delete failed.'));
            if (res && res.success) {
                window.location.reload();
            } else {
                $btn.prop('disabled', false);
            }
        }, 'json').fail(function () {
            alert('Delete request failed.');
            $btn.prop('disabled', false);
        });
    });
})(jQuery);
