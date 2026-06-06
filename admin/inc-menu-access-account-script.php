<?php
/**
 * Shared JS: apply default / expanded menu access checkboxes on account forms.
 * @param int $defaultRoll Roll to load when empty (0 = use #Roll select)
 */
function renderMenuAccessAccountScript($defaultRoll = 0)
{
    $defaultRoll = (int) $defaultRoll;
    $fullAccessRolls = adminMenuAccessBypassRolls();
    ?>
<script>
(function () {
    var fullAccessRolls = <?php echo json_encode(array_map('strval', $fullAccessRolls)); ?>;

    function isFullAccessRoll(roll) {
        roll = String(roll || '');
        return fullAccessRolls.indexOf(roll) !== -1;
    }

    function checkAllMenuAccess() {
        document.querySelectorAll('.menu-access-view[name="Options[]"]').forEach(function (cb) {
            cb.checked = true;
        });
        syncAllMenuAccessParents();
    }

    function syncAllMenuAccessParents() {
        document.querySelectorAll('.menu-access-parent-view').forEach(function (parent) {
            var moduleKey = parent.getAttribute('data-module');
            var views = document.querySelectorAll('.menu-access-view[data-module="' + moduleKey + '"]');
            if (!views.length) return;
            var allOn = true;
            var anyOn = false;
            views.forEach(function (cb) {
                if (cb.checked) anyOn = true;
                else allOn = false;
            });
            parent.checked = allOn;
            parent.indeterminate = anyOn && !allOn;
        });
    }

    window.applyDefaultMenuAccessByRoll = function (roll) {
        roll = roll || '';
        if (!roll || roll === '0') {
            return;
        }
        if (isFullAccessRoll(roll)) {
            checkAllMenuAccess();
            return;
        }
        $.ajax({
            url: '../ajax_files/ajax_employee.php',
            method: 'POST',
            dataType: 'json',
            data: { action: 'getDefaultOptionsByRoll', Roll: roll },
            success: function (ids) {
                if (!Array.isArray(ids) || !ids.length) {
                    return;
                }
                var idSet = {};
                ids.forEach(function (id) { idSet[String(id)] = true; });
                document.querySelectorAll('.menu-access-view[name="Options[]"]').forEach(function (cb) {
                    cb.checked = !!idSet[cb.value];
                });
                syncAllMenuAccessParents();
            }
        });
    };

    $(function () {
        $('#Roll').on('change', function () {
            applyDefaultMenuAccessByRoll($(this).val());
        });
        if (isFullAccessRoll($('#Roll').val())) {
            checkAllMenuAccess();
        }
    });
})();
</script>
    <?php
}
