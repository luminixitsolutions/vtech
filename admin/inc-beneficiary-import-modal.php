<div class="modal fade" id="beneficiaryImportResultModal" tabindex="-1" role="dialog" aria-labelledby="beneficiaryImportResultTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="beneficiaryImportResultTitle">Excel import result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="birSummary" class="mb-3"></div>
                <div id="birMissingWrap" style="display:none;">
                    <label class="form-label font-weight-bold" for="birMissingIds">Missing ID(s) — select and copy (Ctrl+C)</label>
                    <textarea id="birMissingIds" class="form-control" rows="8" readonly style="font-family:monospace;font-size:13px;"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="birCopyMissingBtn">Copy missing IDs</button>
                    <span id="birCopyFeedback" class="text-success small ml-2" style="display:none;">Copied!</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
