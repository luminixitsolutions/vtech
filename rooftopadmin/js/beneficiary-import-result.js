function showBeneficiaryImportResult(opts) {
    opts = opts || {};
    var matched = opts.matched || 0;
    var skippedAssigned = opts.skippedAssigned || 0;
    var missingIds = opts.missingIds || [];
    var html = '';

    if (opts.errorMessage) {
        html = '<p class="text-danger mb-0">' + $('<div>').text(opts.errorMessage).html() + '</p>';
    } else {
        if (matched > 0) {
            html += '<p class="text-success mb-2"><strong>' + matched + '</strong> record(s) selected and shown at the top.</p>';
        }
        if (skippedAssigned > 0) {
            html += '<p class="text-warning mb-2">' + skippedAssigned + ' matched ID(s) are already assigned (no checkbox). Use filter &quot;Not Assign&quot; only.</p>';
        }
        if (matched === 0 && skippedAssigned === 0 && missingIds.length === 0) {
            html = '<p class="text-danger mb-0">No matching beneficiary IDs found in the current list. Check filters or IDs in the file.</p>';
        }
    }

    $('#birSummary').html(html);
    $('#birCopyFeedback').hide();

    if (missingIds.length > 0) {
        $('#birMissingWrap').show();
        $('#birMissingIds').val(missingIds.join('\n'));
    } else {
        $('#birMissingWrap').hide();
        $('#birMissingIds').val('');
    }

    $('#beneficiaryImportResultModal').modal('show');
}

$(function() {
    $(document).on('click', '#birCopyMissingBtn', function() {
        var ta = document.getElementById('birMissingIds');
        if (!ta || !ta.value) {
            return;
        }
        ta.focus();
        ta.select();
        var copied = false;
        try {
            copied = document.execCommand('copy');
        } catch (e) {}
        if (!copied && navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(ta.value).then(function() {
                $('#birCopyFeedback').show();
            });
            return;
        }
        if (copied) {
            $('#birCopyFeedback').show();
        }
    });
});
