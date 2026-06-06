(function ($) {
    'use strict';

    $(function () {
        var $table = $('#msedclSmartTable');
        if (!$table.length || ($table.data('dt-export') !== 1 && $table.data('dt-export') !== '1')) {
            return;
        }
        if ($table.find('tbody td[colspan]').length) {
            return;
        }

        var excludeFirst = $table.data('dt-exclude-first') === 1 || $table.data('dt-exclude-first') === '1';
        var excludeLast = $table.data('dt-exclude-last') === 1 || $table.data('dt-exclude-last') === '1';
        var exportColumns = ':visible';

        if (excludeFirst && excludeLast) {
            exportColumns = ':visible:not(:first-child):not(:last-child)';
        } else if (excludeFirst) {
            exportColumns = ':visible:not(:first-child)';
        } else if (excludeLast) {
            exportColumns = ':visible:not(:last-child)';
        }

        var pageTitle = document.title.replace(/\s*\|\s*/g, ' - ');

        $table.DataTable({
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                title: pageTitle,
                exportOptions: {
                    columns: exportColumns,
                    format: {
                        body: function (data) {
                            return $('<div>').html(data).text().replace(/\s+/g, ' ').trim();
                        }
                    }
                }
            }],
            pageLength: 25,
            order: [[excludeFirst ? 1 : 0, 'asc']]
        });
    });
})(jQuery);
