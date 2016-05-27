<?php
namespace App\Helpers;


class Attr {

    public function isActive($navigation, $activeNavigation = null, $activeClass = 'active')
    {
    	return (isset($activeNavigation) && $activeNavigation == $navigation) ? $activeClass : '';
    }
}