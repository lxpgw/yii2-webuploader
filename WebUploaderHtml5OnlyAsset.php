<?php namespace lxpgw\webuploader;

use yii\web\AssetBundle;

/**
 * The html5 only bundle of webuploader
 *
 * @package lxpgw\webuploader
 * @version 0.1.0
 */
class WebUploaderHtml5OnlyAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $js = [
        'webuploader.html5only.js',
    ];
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/fex-webuploader/dist';
    /**
     * @inheritdoc
     */
    public $css = [
        'webuploader.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'only' => ['webuploader.css', 'webuploader.html5only.js'],
    ];
}
