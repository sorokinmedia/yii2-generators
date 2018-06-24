<?php
namespace ma3oBblu\gii\form;

use yii\web\AssetBundle;

class GeneratorAsset extends AssetBundle
{
    public $sourcePath = '@ma3obblu/gii/form/assets';
    public $js = [
        'generator.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}