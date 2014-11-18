<?php
/**
 * @var $this yii\web\View
 */

$this->title = \Yii::t('maddoger/elfinder', 'File manager');
$this->params['breadcrumbs'][] = $this->title;

echo maddoger\elfinder\Widget::widget(
    array(
        'connectorRoute' => 'files/connector',
    )
);

