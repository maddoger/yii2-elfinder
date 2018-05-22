<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\elfinder;

use maddoger\core\BackendModule;
use Yii;
use yii\rbac\Item;

/**
 * File manager module
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package maddoger/yii2-elfinder
 */
class Module extends BackendModule
{
    /**
     * @var array elfinder roots
     * Defaults to @static
     */
    public $roots = [];

    /**
     * @var array file attributes
     */
    public $attributes;

    /**
     * @var array additional elfinder options
     */
    public $clientOptions = [];

    /**
     * Init module
     */
    public function init()
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['maddoger/elfinder'])) {
            Yii::$app->i18n->translations['maddoger/elfinder'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@maddoger/elfinder/messages',
                'sourceLanguage' => 'en-US',
            ];
        }

        if ($this->attributes === null) {
            $this->attributes = [
                [
                    // hide .gitignore
                    'pattern' => '/\.gitignore$/',
                    'read' => false,
                    'write' => false,
                    'hidden' => true,
                    'locked' => true
                ],
                [   // hide .php
                    'pattern' => '/\.php$/',
                    'read' => false,
                    'write' => false,
                    'hidden' => true,
                    'locked' => true
                ],
                [   //hide .tmp
                    'pattern' => '#.*(\.tmb|\.quarantine)$#i',
                    'read' => false,
                    'write' => false,
                    'hidden' => true,
                    'locked' => true
                ],
            ];
        }


        if (!$this->roots) {
            $this->roots = [
                [
                    'driver' => 'LocalFileSystem',
                    'path' => Yii::getAlias('@static'),
                    'URL' => Yii::getAlias('@staticUrl'),
                ]
            ];
        }

        foreach ($this->roots as $key=>$root) {
            if (!isset($root['accessControl'])) {
                $this->roots[$key]['accessControl'] = [$this, 'accessControl'];
            }
            if (!isset($root['accessControlData'])) {
                $this->roots[$key]['accessControl'] = ['read' => ['elfinder.access'], 'write' => ['elfinder.upload']];
            }
            if (!isset($root['attributes'])) {
                $this->roots[$key]['attributes'] = $this->attributes;
            }
        }
        $this->clientOptions['roots'] = $this->roots;
    }

    /**
     * @param $operation
     * @param $path
     * @param $data
     * @param \elFinderVolumeDriver $connector
     * @return bool
     */
    public function accessControl($operation, $path, $data, $connector)
    {
        if (!$data || !is_array($data) || !isset($data[$operation])) {
            return null;
        }
        foreach ($data[$operation] as $role) {
            if (Yii::$app->user->can($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Yii::t('maddoger/elfinder', 'File Manager Module');
    }

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * @inheritdoc
     */
    public function getNavigation()
    {
        return [
            [
                'label' => Yii::t('maddoger/elfinder', 'File manager'),
                'icon' => 'fa fa-files-o',
                'url' => ['/' . $this->id . '/files/index'],
                'activeUrl' => '/' . $this->id . '/files/*',
                'roles' => ['elfinder.access'],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRbacItems()
    {
        return [
            'elfinder.access' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/elfinder', 'File manager. Access'),
                ],
            'elfinder.upload' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/elfinder', 'File manager. Upload files'),
                ],
            'elfinder.manager' =>
                [
                    'type' => Item::TYPE_ROLE,
                    'description' => Yii::t('maddoger/elfinder', 'File manager. Manager'),
                    'children' => [
                        'elfinder.access',
                        'elfinder.upload',
                    ],
                ],
        ];
    }

    /**
     * @return array
     */
    public function getSearchSources()
    {
        return [
            [
                'class' => '\maddoger\core\search\ArraySearchSource',
                'data' => [
                    [
                        'label' => Yii::t('maddoger/elfinder', 'File manager'),
                        'url' => ['/' . $this->id . '/filemanager/index'],
                    ],
                ],
                'roles' => ['elfinder.access'],
            ],
        ];
    }
}
