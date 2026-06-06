<?php

/**
 * Lead source icons (Instagram, Facebook, etc.) with brand colors for dashboards and tables.
 */

if (!function_exists('leadSourceGetConfig')) {
    function leadSourceGetConfig($sourceName)
    {
        $name = strtolower(trim((string) $sourceName));
        $rules = [
            ['keys' => ['instagram', 'insta'], 'icon' => 'instagram', 'slug' => 'instagram', 'gradient' => 'linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%)'],
            ['keys' => ['facebook', 'fb'], 'icon' => 'facebook', 'slug' => 'facebook', 'gradient' => 'linear-gradient(135deg, #1877f2 0%, #0d5dbf 100%)'],
            ['keys' => ['whatsapp', 'whats app'], 'icon' => 'message-circle', 'slug' => 'whatsapp', 'gradient' => 'linear-gradient(135deg, #25d366 0%, #128c7e 100%)'],
            ['keys' => ['twitter', 'x.com'], 'icon' => 'twitter', 'slug' => 'twitter', 'gradient' => 'linear-gradient(135deg, #1da1f2 0%, #0c7abf 100%)'],
            ['keys' => ['linkedin'], 'icon' => 'linkedin', 'slug' => 'linkedin', 'gradient' => 'linear-gradient(135deg, #0a66c2 0%, #004182 100%)'],
            ['keys' => ['youtube'], 'icon' => 'youtube', 'slug' => 'youtube', 'gradient' => 'linear-gradient(135deg, #ff0000 0%, #cc0000 100%)'],
            ['keys' => ['google'], 'icon' => 'chrome', 'slug' => 'google', 'gradient' => 'linear-gradient(135deg, #4285f4 0%, #34a853 50%, #fbbc05 100%)'],
            ['keys' => ['website', 'web', 'online'], 'icon' => 'globe', 'slug' => 'website', 'gradient' => 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)'],
            ['keys' => ['dealer'], 'icon' => 'briefcase', 'slug' => 'dealer', 'gradient' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)'],
            ['keys' => ['direct'], 'icon' => 'user-check', 'slug' => 'direct', 'gradient' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)'],
            ['keys' => ['referral', 'reference'], 'icon' => 'users', 'slug' => 'referral', 'gradient' => 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)'],
            ['keys' => ['walk', 'walk-in'], 'icon' => 'map-pin', 'slug' => 'walkin', 'gradient' => 'linear-gradient(135deg, #14b8a6 0%, #0d9488 100%)'],
            ['keys' => ['call', 'phone', 'tele'], 'icon' => 'phone', 'slug' => 'phone', 'gradient' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'],
            ['keys' => ['email', 'mail'], 'icon' => 'mail', 'slug' => 'email', 'gradient' => 'linear-gradient(135deg, #ec4899 0%, #db2777 100%)'],
            ['keys' => ['exhibition', 'expo', 'fair'], 'icon' => 'flag', 'slug' => 'exhibition', 'gradient' => 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)'],
            ['keys' => ['sms'], 'icon' => 'message-square', 'slug' => 'sms', 'gradient' => 'linear-gradient(135deg, #06b6d4 0%, #0891b2 100%)'],
            ['keys' => ['total', 'all leads'], 'icon' => 'layers', 'slug' => 'total', 'gradient' => 'linear-gradient(135deg, #334155 0%, #1e293b 100%)'],
        ];

        foreach ($rules as $rule) {
            foreach ($rule['keys'] as $key) {
                if ($key !== '' && strpos($name, $key) !== false) {
                    return $rule;
                }
            }
        }

        return [
            'icon' => 'tag',
            'slug' => 'default',
            'gradient' => 'linear-gradient(135deg, #64748b 0%, #475569 100%)',
        ];
    }

    function leadSourceIconHtml($sourceName, $size = 'md', $withLabel = false)
    {
        $cfg = leadSourceGetConfig($sourceName);
        $size = in_array($size, ['sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
        $slug = htmlspecialchars($cfg['slug'], ENT_QUOTES, 'UTF-8');
        $icon = htmlspecialchars($cfg['icon'], ENT_QUOTES, 'UTF-8');
        $label = htmlspecialchars(trim((string) $sourceName), ENT_QUOTES, 'UTF-8');

        $html = '<span class="lead-source-icon lead-source-icon--' . $size . ' lead-source-icon--' . $slug . '"';
        $html .= ' style="--lsi-gradient:' . $cfg['gradient'] . '">';
        $html .= '<span class="lead-source-icon__badge"><i class="feather icon-' . $icon . '"></i></span>';
        if ($withLabel && $label !== '') {
            $html .= '<span class="lead-source-icon__text">' . $label . '</span>';
        }
        $html .= '</span>';

        return $html;
    }

    function leadSourceDisplayHtml($sourceName, $suffix = '')
    {
        $source = trim((string) $sourceName);
        $suffix = trim((string) $suffix);
        $label = $source;
        if ($suffix !== '') {
            $label .= ' ' . $suffix;
        }

        $html = '<span class="lead-source-cell">';
        $html .= leadSourceIconHtml($source, 'sm', false);
        $html .= '<span class="lead-source-cell__label">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
        $html .= '</span>';

        return $html;
    }

    function leadSourceDashboardCardIcon($sourceName)
    {
        return leadSourceIconHtml($sourceName, 'lg', false);
    }

    function leadSourceIconsStylesheetTag()
    {
        global $SiteUrl;
        $base = isset($SiteUrl) ? rtrim($SiteUrl, '/') : '';
        $href = $base . '/css/lead-source-icons.css?v=1';

        return '<link rel="stylesheet" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">';
    }
}
