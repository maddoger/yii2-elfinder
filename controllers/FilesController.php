<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\elfinder\controllers;

use maddoger\elfinder\Module;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Files.php
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package
 */
class FilesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'dialog'],
                        'roles' => ['elfinder.access'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['connector'],
                        'allow' => true,
                        'roles' => ['elfinder.access'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        $clientOptions = Module::getInstance()->clientOptions ?: [];

        return array(
            'connector' => array(
                'class' => 'maddoger\elfinder\ConnectorAction',
                'clientOptions' => $clientOptions,
            )
        );
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDialog()
    {
        return $this->renderPartial('dialog');
    }
}