<?php
/**
 * Menu access accordion for rooftop account forms ($row7['Options'] must be set).
 */
include_once __DIR__ . '/inc-menu-option-groups.php';
menuAccessSyncBuiltinOptionsToDb();
renderMenuAccessAccordion($row7['Options']);
