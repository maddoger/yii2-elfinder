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
use yii\base\Model;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget as elWidget;

class Widget extends elWidget
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
    public $clientOptions = array(
        'url' => '',
        'lang' => 'ru'
    );

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
        //checks for the element id
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        // set required options
        if (empty($this->connectorRoute))
        {
           echo "connectorRoute must be set!";
           exit;
        }
        $this->clientOptions['url'] = Url::to(array($this->connectorRoute));
        
        //fetch language from app
        $this->clientOptions['lang'] = substr(Yii::$app->language,0,2);        

        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::beginTag('div', $this->options) . "\n";
        echo Html::endTag('div')."\n";
        $this->registerPlugin();
    }

    /**
    * Registers a specific dhtmlx widget and the related events
    * @param string $name the name of the dhtmlx plugin
    */
    protected function registerPlugin()
    {
        $id = $this->options['id'];
        $view = $this->getView();

        /** @var \yii\web\AssetBundle $assetClass */
        $assetClass = 'maddoger\\elfinder\\CoreAsset';
        $assetClass::register($view);

        $js = array();
        
        $cleanOptions = Json::encode($this->clientOptions);
        $js[] = "var elf = $('#$id').elfinder($cleanOptions).elfinder('instance');";
        
        $view->registerJs(implode("\n", $js),View::POS_READY);
    }

}
