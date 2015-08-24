<?php namespace lxpgw\webuploader;

use yii\web\AssetBundle;

/**
 * The flash only bundle of webuploader
 *
 * @package lxpgw\webuploader
 * @version 0.1.0
 */
class WebUploaderFlashOnlyAsset extends AssetBundle
{
    /**
     * @inhertidoc
     */
    public $js = [
        'webuploader.flashonly.js',
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
        'only' => ['webuploader.css', 'webuploader.flashonly.js', '*.swf'],
    ];
}
