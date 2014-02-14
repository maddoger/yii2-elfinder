<?php

namespace rusporting\elfinder;

use yii\web\AssetBundle;

/**
 * @link http://www.frenzel.net/
 * @author Philipp Frenzel <philipp@frenzel.net> 
 */

class CoreAsset extends AssetBundle
{
    public $sourcePath = '@rusporting/elfinder/assets';
    public $css = array(
        'css/elfinder.full.css',
        'css/theme.css',
    );
    public $js = array(
        'js/elfinder.full.js',
        'js/i18n/elfinder.ru.js',
    );
    public $depends = array(
        'yii\jui\CoreAsset',
        'yii\jui\ThemeAsset',
        'yii\jui\EffectAsset',
        'yii\jui\ResizableAsset',
        'yii\jui\DraggableAsset',
        'yii\jui\DroppableAsset',
        'yii\jui\SelectableAsset'
    );
}