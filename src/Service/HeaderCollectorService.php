<?php

namespace WioForms\Service;

class HeaderCollectorService
{
    private $wioForms;
    private $css;
    private $js;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->css = [];
        $this->js = [];
    }

    public function addJS($js)
    {
        $this->js[] = $js;
    }

    public function addCss($css)
    {
        $this->css[] = $css;
    }

    public function getHeaders($dir = '')
    {
        $this->dir = '';
        if ($dir !== false) {
            if (isset($this->wioForms->settings['SrcDir'])) {
                $this->dir = $this->wioForms->settings['SrcDir'];
            }
        }
        if ($dir !== false) {
            $this->dir .= '/';
        }

        $headers = [];

        foreach ($this->css as $css) {
            $headers[] = $this->showCss($css);
        }

        foreach ($this->js as $js) {
            $headers[] = $this->showJs($js);
        }

        return $headers;
    }

    private function showCss($css)
    {
        if ((strpos($css, 'http://') !== false)
            or (strrpos($css, 'https://') !== false)) {
            return '<link rel="stylesheet" href="'.$css.'" />';
        }

        return '<link rel="stylesheet" href="'.$this->dir.$css.'" />';
    }

    private function showJs($js)
    {
        if ((strpos($js, 'http://') !== false)
            or (strpos($js, 'https://') !== false)) {
            return '<script type="text/javascript" src="'.$js.'"></script>';
        }

        return '<script type="text/javascript" src="'.$this->dir.$js.'"></script>';
    }
}
