<?php
/**
 * Instgram Media plugin for Craft CMS 3.x
 *
 * Plugin to retrieve instagram feed from personal account using Facebook's Instagram
 *
 * @link      https://heyblackmagic.com
 * @copyright Copyright (c) 2020 Black Magic
 */

namespace blackmagic\instagrammedia;

use blackmagic\instagrammedia\models\InstagramMediaModel;
use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\helpers\UrlHelper;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use blackmagic\instagrammedia\services\InstagramMediaService;
use blackmagic\instagrammedia\variables\InstagramMediaVariable;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Black Magic
 * @package   InstagramMedia
 * @since     1.0.0
 *
 * @property  InstagramMediaServiceService $instagramMediaService
 */
class InstagramMedia extends Plugin
{
    public $hasCpSettings = true;

    // Public Properties
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->setComponents([
            'InstagramMediaService' => InstagramMediaService::class,
        ]);


        // Register our variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('instagram', InstagramMediaVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_BEFORE_SAVE_PLUGIN_SETTINGS,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    $this->InstagramMediaService->longLiveToken();
                }
            }
        );
    }

     /**
     * @inheritDoc
     */
    public function afterInstall()
    {
        parent::afterInstall();

        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            return;
        }

        Craft::$app->getResponse()->redirect(
            UrlHelper::cpUrl('settings/plugins/instagrammedia')
        )->send();
    }

    /**
    * @inheritDoc
    */
    protected function createSettingsModel()
    {
        return new InstagramMediaModel();
    }

    /**
     * @inheritDoc
     */
    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('instagrammedia/settings', [
                'settings' => $this->getSettings()
            ]
        );
    }

}
