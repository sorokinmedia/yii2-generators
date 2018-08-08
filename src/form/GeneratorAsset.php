<?php
namespace sorokinmedia\gii\generators\form;

use yii\web\AssetBundle;

/**
 * Class GeneratorAsset
 * @package sorokinmedia\gii\generators\form
 */
class GeneratorAsset extends AssetBundle
{
    public $sourcePath = '@sorokinmedia/gii/generators/form/assets';
    public $js = [
        'generator.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}