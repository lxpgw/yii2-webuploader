<?php namespace lxpgw\webuploader;

use yii\web\AssetBundle;

/**
 * The asset bundle for webuploader.js
 *
 * @package lxpgw\webuploader
 * @author lichunqiang <light-li@hotmail.com>
 * @version 0.1.0
 * @see https://github.com/fex-team/webuploader
 */
class WebUploaderAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $js = [
        'webuploader.js',
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
        'only' => ['webuploader.css', 'webuploader.js', '*.swf'],
    ];
}
