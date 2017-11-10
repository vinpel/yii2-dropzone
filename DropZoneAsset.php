<?php

namespace vinpel\DropZone;

use yii\web\AssetBundle;

/**
* Asset bundle for DropZone Widget
*/
class DropZoneAsset extends AssetBundle
{

    public $sourcePath = '@bower/dropzone/dist/min';

    public $js = [
    'dropzone.min.js',
    ];

    public $css = [
    'dropzone.min.css',
    ];

    public $publishOptions = [
    'forceCopy' => false
    ];

    public $depends = [
    'yii\web\JqueryAsset',
    'yii\jui\JuiAsset',
    ];
}
