<?php namespace lxpgw\webuploader;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * The webuploader widget
 *
 * @package lxpgw\webuploader
 * @author lichunqiang <light-li@hotmail.com>
 * @version 0.1.0
 */
class WebUploader extends InputWidget
{
    //the mode of using file
    const MODE_ALL = 'all';
    const MODE_FLASH = 'flash';
    const MODE_HTML5 = 'html5';

    /**
     * The mode of webuploader
     * @var string
     */
    public $mode = self::MODE_ALL;

    /**
     * The setting options for the widget
     * @var array
     */
    public $pluginOptions = [];

    /**
     * The file input name
     * @var string
     */
    public $fileVal = 'file';

    /**
     * The upload action, this will be convert by [[Url::to]]
     * @var string
     */
    public $action;

    /**
     * If enabled the csrf token validation
     * @var boolean
     */
    public $enabledCsrf = true;

    /**
     * If auto upload the file
     * @var boolean
     */
    public $autoUpload = true;

    /**
     * The button label for the uploader
     * @var string
     */
    public $buttonLabel = '选择文件';

    /**
     * The event hooks
     * @var array
     */
    public $events = [];

    /**
     * @inhertidoc
     */
    public function init()
    {
        parent::init();
        if (null === $this->name) {
            $this->name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->getId();
        }

        if (null === $this->action) {
            throw new InvalidConfigException('The action must be configed!');

        }

        $bundle = $this->registerBundle();
        $this->registerAsset($bundle);
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::activeHiddenInput($this->model, $this->attribute, ['id' => null]);
        echo Html::tag('div', $this->buttonLabel, $this->options);
    }

    /**
     * Register the asset bundle of WebUploaderAsset
     * @return null
     */
    private function registerBundle()
    {
        if (self::MODE_ALL === $this->mode) {
            return WebUploaderAsset::register($this->view);
        } elseif (self::MODE_FLASH === $this->mode) {
            return WebUploaderFlashOnlyAsset::register($this->view);
        } elseif (self::MODE_HTML5 === $this->mode) {
            return WebUploaderHtml5OnlyAsset::register($this->view);
        } else {
            throw new InvalidConfigException('The wrong mode be setted!');
        }
    }
    /**
     * Register the asset
     * @return null
     */
    private function registerAsset($bundle)
    {
        $defaults = [
            'auto' => $this->autoUpload,
            'pick' => '#' . $this->options['id'],
            'swf' => $bundle->baseUrl . '/Uploader.swf',
            'server' => Url::to($this->action),
            'accept' => [
                'title' => 'Images',
                'extensions' => 'gif,jpg,jpeg,bmp,png',
                'mimeTypes' => 'images/*',
            ],
            'fileVal' => $this->fileVal,
            'fileNumLimit' => 1,
        ];
        if ($this->enabledCsrf) {
            $request = Yii::$app->getRequest();
            $csrfParam = $request->csrfParam;
            $csrfToken = $request->getCsrfToken();
            $defaults['formData'] = [
                $csrfParam => $csrfToken,
            ];
        }
        $options = ArrayHelper::merge($defaults, $this->pluginOptions);
        $events = ArrayHelper::merge([
            'uploadSuccess' => new JsExpression('function(file, response) {console.log(file);alert("uploaded");}'),
            'uploadError' => new JsExpression('function(file, reason) {alert(reason);}'),
        ], $this->events);
        $options = Json::encode($options);
        $js[] = "var uploader = WebUploader.create($options);";
        foreach ($events as $key => $func) {
            if ($func instanceof JsExpression) {
                $js[] = ";uploader.on('$key', $func)";
            }
        }
        $this->view->registerJs(implode($js));
    }

}
