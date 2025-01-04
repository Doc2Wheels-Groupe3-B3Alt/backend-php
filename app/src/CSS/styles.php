<?php

namespace App\CSS;

class Styles
{
    public function getStyles(): string
    {
        $css = file_get_contents(__DIR__ . '/styles.css');
        return $css;
    }
}
