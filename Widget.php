<?php

/**
 * This class is merely used to publish a TOC based upon the headings within a defined container
 * @copyright Frenzel GmbH - www.frenzel.net
 * @link http://www.frenzel.net
 * @author Philipp Frenzel <philipp@frenzel.net>
 *
 *
 * Original yii version by
 * @author z_bodya
 */

namespace maddoger\elfinder;

use Yii;
use yii\base\Exception;
use yii\base\Widget as BaseWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class Widget extends BaseWidget
{
    /**
     * @var array the HTML attributes (name-value pairs) for the field container tag.
     * The values will be HTML-encoded using [[Html::encode()]].
     * If a value is null, the corresponding attribute will not be rendered.
     */
    public $options = array(
        'class' => 'elfinder',
    );

    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $clientOptions;

    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $connectorRoute = false;

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        // set required options
        if (empty($this->connectorRoute)) {
            throw new Exception('connectorRoute must be set!');
        }
        $this->clientOptions = [];
        $this->clientOptions['url'] = Url::to([$this->connectorRoute]);
        if (!isset($this->clientOptions['lang'])) {
            $this->clientOptions['lang'] = substr(Yii::$app->language, 0, 2);
        }
        if (Yii::$app->request->enableCsrfValidation) {
            $this->clientOptions['customData'][Yii::$app->request->csrfParam] = Yii::$app->request->csrfToken;
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::beginTag('div', $this->options) . "\n";
        echo Html::endTag('div') . "\n";
        $this->registerPlugin();
    }

    /**
     * Registers a specific dhtmlx widget and the related events
     */
    protected function registerPlugin()
    {
        $id = $this->options['id'];

        /** @var \yii\web\AssetBundle $assetClass */
        $bundle = CoreAsset::register($this->view);
        $bundle->js[] = 'js/i18n/elfinder.' . $this->clientOptions['lang'] . '.js';

        $cleanOptions = Json::encode($this->clientOptions);
        $js[] = "var elf = $('#$id').elfinder($cleanOptions).elfinder('instance');";

        $this->view->registerJs(implode("\n", $js));
    }

}
