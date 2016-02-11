<?php

if (!function_exists('ads_placement')) {
    function ads_placement($code) {
        $ads = Model\Portal\Ads::where('code', $code)->first();

        if ($ads && $ads->show) {
            $html = '<a href="'.$ads->link.'" target="'.$ads->target.'">';
            $html .= '<img src="'.$ads->image_url.'" width="'.$ads->width.'" height="'.$ads->height.'" title="'.$ads->name.'">';
            $html .= '</a>';
        } else {
            $html = '';
        }

        return $html;
    }
}