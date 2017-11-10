<?php
namespace vinpel\DropZone;

use yii\helpers\Html;
use yii\helpers\Json;
use vinpel\DropZone\DropZoneAsset;

/**
* Usage: \vinpel\DropZone::widget();
* Class DropZone
* @package kato
*/
class DropZone extends \yii\base\Widget
{
  /**
  * @var array An array of options that are supported by Dropzone
  */
    public $options = [];

  /**
  * @var array An array of client events that are supported by Dropzone
  */
    public $clientEvents = [];

  //Default Values
    public $id                = 'myIdDropzone';
    public $uploadUrl         = '/site/upload';
    public $previewsContainer = 'previews';
    public $autoDiscover      = false;

  /**
  * Initializes the widget
  * @throw InvalidConfigException
  */
    public function init()
    {
        parent::init();

        //set defaults
        // Set the url
        if (!isset($this->options['url'])) {
            $this->options['url'] = $this->uploadUrl;
        }
        // Define the element that should be used as click trigger to select files.
        if (!isset($this->options['previewsContainer'])) {
            $this->options['previewsContainer'] = '#' . $this->previewsContainer;
        }
        // Define the element that should be used as click trigger to select files.
        if (!isset($this->options['clickable'])) {
            $this->options['clickable'] = true;
        }
        if (!isset($this->options['method'])) {
            $this->options['method'] = 'POST';
        }
        $this->autoDiscover = $this->autoDiscover===false?'false':'true';

        if (\Yii::$app->getRequest()->enableCsrfValidation) {
            $this->options['headers'][\yii\web\Request::CSRF_HEADER]      = \Yii::$app->getRequest()->getCsrfToken();
            $this->options['params'][\Yii::$app->getRequest()->csrfParam] = \Yii::$app->getRequest()->getCsrfToken();
        }


        $this->registerAssets();
    }
  /**
  * Create a div container
  * i don't add 'dropzone' class because the autoDiscover=false is inside jQuery.ready
  * @return string html div code
  */
    public function run()
    {
        return  Html::tag('div', $this->renderDropzone(), [
        'id'    => $this->id,
        ]);
    }
  /**
  * Dropzene preview, included inside the div container
  * @return string  Html div code for preview
  */
    private function renderDropzone()
    {
        $data = Html::tag('div', '', [
        'id'    => $this->previewsContainer,
        'class' => 'dropzone-previews'
        ]);

        return $data;
    }

  /**
  * Registers the needed assets
  */
    public function registerAssets()
    {
        $view = $this->getView();

        $js = 'Dropzone.autoDiscover = ' . $this->autoDiscover . ";\n";
        $js.='$("#'.$this->id."\").addClass('dropzone');\n";
        $js.='var '.$this->id.'=new Dropzone("#'.$this->id."\",\n";
        $js.= Json::encode($this->options, JSON_PRETTY_PRINT) . ");\n";

        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js .=$this->id.".on('$event', $handler);\n";
            }
        }
        $view->registerJs($js);
        DropZoneAsset::register($view);
    }
}
