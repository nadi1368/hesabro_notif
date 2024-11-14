<?php

namespace hesabro\notif;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $this->registerTranslation($app);
        $app->params['bsVersion'] = 4;
    }

    private function registerTranslation(Application $app): void
    {
        $app->i18n->translations['hesabro/notif*'] = [
            'class' => PhpMessageSource::class,
            'basePath' => '@hesabro/notif/messages',
            'sourceLanguage' => 'en-US',
            'fileMap' => [
                'hesabro/notif/module' => 'module.php'
            ],
        ];
    }
}
