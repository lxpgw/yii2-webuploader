<?php namespace lxpgw\webuploader;

use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\validators\FileValidator;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * The upload action for webuploader
 *
 * @package lxpgw\webuploader
 * @version 0.1.0
 */
class UploaderAction extends Action
{
    /**
     * The file name
     * @var string
     */
    public $fileInputName = 'file';

    /**
     * The options passded to FileValidator
     * @var array
     */
    public $fileValidation = [];
    /**
     * The target directory to store the file, support path alias
     * @var string
     */
    public $targetDirectory = '@webroot/upload';
    /**
     * The base url of the uploaded file
     * @var string
     */
    public $baseUrl = '@web/upload';
    /**
     * The file name to save
     * @var string|callable
     */
    public $fileName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->targetDirectory = Yii::getAlias($this->targetDirectory);
        $this->baseUrl = Yii::getAlias($this->baseUrl);

        if (is_callable($this->fileName)) {
            $this->fileName = call_user_func($this->fileName, $this);
        }
    }

    /**
     * @inhertidoc
     */
    public function run()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $file = UploadedFile::getInstanceByName($this->fileInputName);
        $validationOptions = ArrayHelper::merge([
            'extensions' => 'gif, jpg, png',
            'maxSize' => 2 * 1024 * 1024,
        ], $this->fileValidation);

        $validator = new FileValidator($validationOptions);
        if (!$validator->validate($file, $errmsg)) {
            return [
                'errcode' => 1,
                'errmsg' => $errmsg,
            ];
        }
        if (null === $this->fileName) {
            $this->fileName = md5($file->name . time());
        }

        $file_path = $this->targetDirectory . DIRECTORY_SEPARATOR . $this->fileName;
        if ($file->saveAs($file_path)) {
            return [
                'errcode' => 0,
                'errmsg' => 'Uploaded successfully!',
                'file' => [
                    'name' => $file->name,
                    'newFileName' => $this->fileName,
                    'uploaded' => $this->baseUrl . '/' . $this->fileName . '?_' . $_SERVER['REQUEST_TIME'],
                ],
            ];
        }
        return ['errcode' => 1, 'errmsg' => 'Error occured'];
    }
}
