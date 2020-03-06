<?php
/**
 * Instagram Media plugin for Craft CMS 3.x
 *
 * Plugin to retrieve instagram feed from personal account using Facebook's Instagram
 *
 * @link      https://heyblackmagic.com
 * @copyright Copyright (c) 2020 Black Magic
 */

namespace blackmagic\instagrammedia\variables;

use blackmagic\instagrammedia\InstagramMedia;


/**
 * instagramMedia Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.instagramMedia }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Black Magic
 * @package   Instagram Media
 * @since     1.0.0
 */
class InstagramMediaVariable
{
    public function getMedia()
    {
        return InstagramMedia::getInstance()->InstagramMediaService->getMedia();
    }

    public function getAuthLink(): string
    {
        return InstagramMedia::getInstance()->InstagramMediaService->getAuthLink();
    }
}
