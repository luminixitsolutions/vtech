(function ($) {
    'use strict';

    function formatDateCols(metric) {
        var cols = ['pmsgy_date', 'mahadiscom_date', 'payment_date', 'survey_date'];
        var labels = {
            pmsgy_date: 'PMSGY Date',
            mahadiscom_date: 'Mahadiscom Date',
            payment_date: 'Payment Date',
            survey_date: 'Survey Date'
        };
        if (metric === 'pmsgy') {
            return [{ key: 'pmsgy_date', label: labels.pmsgy_date }];
        }
        if (metric === 'mahadiscom') {
            return [{ key: 'mahadiscom_date', label: labels.mahadiscom_date }];
        }
        if (metric === 'payment') {
            return [{ key: 'payment_date', label: labels.payment_date }];
        }
        if (metric === 'survey') {
            return [{ key: 'survey_date', label: labels.survey_date }];
        }
        return cols.map(function (key) {
            return { key: key, label: labels[key] };
        });
    }

    function buildTableHtml(rows, metric) {
        if (!rows || !rows.length) {
            return '<p class="text-muted mb-0">No records found.</p>';
        }

        var dateCols = formatDateCols(metric);
        var html = '<div class="table-responsive"><table class="table table-sm table-bordered table-striped mb-0">';
        html += '<thead class="thead-light"><tr>';
        html += '<th>Sr</th><th>Beneficiary ID</th><th>Customer Name</th><th>Mobile</th>';
        html += '<th>District</th><th>Taluka</th><th>Village</th><th>Capacity</th><th>Stage</th>';
        dateCols.forEach(function (col) {
            html += '<th>' + col.label + '</th>';
        });
        html += '</tr></thead><tbody>';

        rows.forEach(function (row, idx) {
            html += '<tr>';
            html += '<td>' + (idx + 1) + '</td>';
            html += '<td>' + escapeHtml(row.beneficiary_id || '') + '</td>';
            html += '<td>' + escapeHtml(row.cust_name || '') + '</td>';
            html += '<td>' + escapeHtml(row.cell_no || '') + '</td>';
            html += '<td>' + escapeHtml(row.district || '') + '</td>';
            html += '<td>' + escapeHtml(row.taluka || '') + '</td>';
            html += '<td>' + escapeHtml(row.village || '') + '</td>';
            html += '<td>' + escapeHtml(row.capacity || '') + '</td>';
            html += '<td>' + escapeHtml(row.stage || '') + '</td>';
            dateCols.forEach(function (col) {
                html += '<td>' + escapeHtml(row[col.key] || '') + '</td>';
            });
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        return html;
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    $(document).on('click', '.msedcl-abstract-count-link', function (e) {
        e.preventDefault();
        var filtersRaw = $(this).attr('data-filters') || '{}';
        var filters;
        try {
            filters = JSON.parse(filtersRaw);
        } catch (err) {
            alert('Could not read filter data.');
            return;
        }

        var $modal = $('#msedclAbstractRecordsModal');
        var $body = $modal.find('.modal-body');
        var $title = $modal.find('.modal-title');

        $title.text('Loading records…');
        $body.html('<div class="text-center py-4 text-muted">Loading…</div>');
        $modal.modal('show');

        $.getJSON('ajax-abstract-records.php', filters)
            .done(function (res) {
                if (!res || !res.success) {
                    $title.text('Records');
                    $body.html('<p class="text-danger mb-0">' + escapeHtml((res && res.message) || 'Could not load records.') + '</p>');
                    return;
                }
                $title.text(res.title + ' (' + res.count + ')');
                $body.html(buildTableHtml(res.rows, res.metric));
            })
            .fail(function () {
                $title.text('Records');
                $body.html('<p class="text-danger mb-0">Request failed.</p>');
            });
    });
})(jQuery);
