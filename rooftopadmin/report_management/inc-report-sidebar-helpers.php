<?php

if (!function_exists('reportSidebarOptionAllowed')) {
    function reportSidebarOptionAllowed($Options, $optionId)
    {
        return in_array((string) $optionId, $Options, true) || in_array($optionId, $Options, true);
    }
}

if (!function_exists('reportSidebarAnyOption')) {
    function reportSidebarAnyOption($Options, array $optionIds)
    {
        foreach ($optionIds as $optionId) {
            if (reportSidebarOptionAllowed($Options, $optionId)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('reportSidebarPageActive')) {
    function reportSidebarPageActive($Page, array $pages)
    {
        return in_array($Page, $pages, true);
    }
}

if (!function_exists('reportSidebarOpenClass')) {
    function reportSidebarOpenClass($Page, array $pages)
    {
        return reportSidebarPageActive($Page, $pages) ? ' open active' : '';
    }
}

if (!function_exists('reportSidebarActiveDot')) {
    function reportSidebarActiveDot($Page, array $pages)
    {
        if (!reportSidebarPageActive($Page, $pages)) {
            return '';
        }
        return '<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>';
    }
}

if (!function_exists('reportSidebarSerialLocationAllowed')) {
    function reportSidebarSerialLocationAllowed($Options, $Roll)
    {
        if (reportSidebarOptionAllowed($Options, 183)) {
            return true;
        }
        $roll = (int) $Roll;
        if (in_array($roll, array(1, 7, 26, 27), true)) {
            return true;
        }
        return reportSidebarAnyOption($Options, array(165, 166, 72));
    }
}
