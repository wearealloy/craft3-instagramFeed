<?php
/**
 * Instgram Media plugin for Craft CMS 3.x
 *
 * Plugin to retrieve instagram feed from personal account using Facebook's Instagram
 *
 * @link      https://heyblackmagic.com
 * @copyright Copyright (c) 2020 Black Magic
 */

namespace blackmagic\instagrammedia\models;

use Craft;
use craft\base\Model;

/**
 * InstagramFeedModel Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Black Magic
 * @package   InstagramMedia
 * @since     1.0.0
 */
class InstagramMediaModel extends Model
{
    /**
     * @var string The Instagram app Id
     */
    public $appId = '';

    /**
     * @var string Instgram app secret
     */
    public $appSecret = '';

    /**
     * @var string Instgram app redirect uri
     */
    public $redirectUri = '';

    /**
     * @var string Instgram auth link
     */
    public $authLink = '';

    /**
     * @var string Instgram api token
     */
    public $apiToken = '';

    /**
     * @var string Instgram short live acces token
     */
    public $accesToken = '';

    /**
     * @var string Instgram long live acces token
     */
    public $longAccesToken = '';


    /**
     * @var integer Instgram long live acces token expiration time
     */
    public $creationTime = 0;

    /**
     * @var integer Instgram long live acces token expiration time
     */
    public $expirationTime = 0;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appId', 'appSecret', 'redirectUri'], 'required'],
            ['authLink', 'string'],
            ['apiToken', 'string'],
            ['accesToken', 'string'],
            ['longAccesToken', 'string'],
            ['creationTime', 'integer'],
            ['expirationTime', 'integer']
        ];
    }
}
