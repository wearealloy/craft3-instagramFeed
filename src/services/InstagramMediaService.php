<?php

/**
 * instagramFeed plugin for Craft CMS 3.x
 *
 * Plugin to retrieve instagram feed from personal account using Facebook's Instagram
 *
 * @link      https://heyblackmagic.com
 * @copyright Copyright (c) 2020 Black Magic
 */

namespace blackmagic\instagrammedia\services;

use blackmagic\instagrammedia\InstagramMedia;

use Craft;
use craft\base\Component;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;


/**
 * InstagramMediaService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Black Magic
 * @package   InstagramMedia
 * @since     1.0.0
 */
class InstagramMediaService extends Component
{

    public $appId;
    public $appSecret;
    public $redirectUri;
    public $apiToken;
    public $creationTime;
    public $expirationTime;
    public $days = 60;

    public $instagram;

    public function __construct()
    {
        $this->appId = InstagramMedia::getInstance()->getSettings()->appId;
        $this->appSecret = InstagramMedia::getInstance()->getSettings()->appSecret;
        $this->redirectUri = InstagramMedia::getInstance()->getSettings()->redirectUri;
        $this->apiToken = InstagramMedia::getInstance()->getSettings()->apiToken;
        $this->creationTime = InstagramMedia::getInstance()->getSettings()->creationTime ;

        if($this->creationTime !== ''){
            $diff = time() - $this->creationTime; 
            $daysPassed = abs(round($diff / 86400)); 
            $daysLeft = $this->days - $daysPassed;
            InstagramMedia::getInstance()->setSettings(array("expirationTime" => $daysLeft));
            $this->expirationTime = $daysLeft;
        }

        $this->instagram = new InstagramBasicDisplay([
            'appId' => $this->appId,
            'appSecret' => $this->appSecret,
            'redirectUri' => $this->redirectUri
        ]);
    }

    public function getAuthLink(): string
    {
        return $this->instagram->getLoginUrl();
    }


    public function longLiveToken()
    {
        $apiToken = InstagramMedia::getInstance()->getSettings()->apiToken;
        $longToken = InstagramMedia::getInstance()->getSettings()->longAccesToken;


        if($longToken !== '' && $this->expirationTime <= 60 && $this->expirationTime > 0){
            // refresh long token on save
            $token = $this->instagram->refreshToken($longToken, true);
            InstagramMedia::getInstance()->setSettings(array("longAccesToken" => $token, "creationTime" => time()));

        }elseif($apiToken !== ''){
            // Get the short lived access token (valid for 1 hour)
            $token = $this->instagram->getOAuthToken($apiToken, true);

            // Exchange this token for a long lived token (valid for 60 days)
            $token = $this->instagram->getLongLivedToken($token, true);


            InstagramMedia::getInstance()->setSettings(array("longAccesToken" => $token, "creationTime" => time()));
        }else{
            InstagramMedia::getInstance()->setSettings(array("longAccesToken" => '', "apiToken" => '', "creationTime" => 0));
        }
        
    }


    public function refreshLongLiveToken()
    {
        $longToken = $this->apiToken = InstagramMedia::getInstance()->getSettings()->longAccesToken;
        if( $longToken !== '' && $this->creationTime < 15 && $this->creationTime > 0)
        {
            //refresh long live token
        }
    }


    /**
     * Returns the current Instagram media of the configured account.
     *
     *
     * @return array The feed data
     */
    
    public function getMedia(): array
    {
        $token = InstagramMedia::getInstance()->getSettings()->longAccesToken;

        $this->instagram->setAccessToken($token);

        $media = $this->instagram->getUserMedia();

        if (empty($media->data)) {
            Craft::warning('No Instagram account configured.', __METHOD__);

            return [];
        }

        return $media->data;
    }

    // public function getProfile(): object
    // {
    //     $instagram = new InstagramBasicDisplay([
    //         'appId' => '728864970977696',
    //         'appSecret' => '1a06195dc9fc415277250e77ba37469d',
    //         'redirectUri' => 'https://www.staging.flyleafgp.com/'
    //     ]);

    //     $token = 'IGQVJVdlZAXU05Qd0ZADaXJ2WVpnLXVPYXZAqalpSTVdScGFiWDkxSmR0amFtSm1fSGpfNFdjM0g4eDFhYzdlRHNVLXJqNU1xQW9oUmNNY0FEWE9EcWxrcTZACZA0NCOUUtS1NJNk0xSjlPRVFEVTVnLXZAfR1YzUmVaMGRqampv';

    //     // $token = $instagram->getLongLivedToken($token, true);

    //     $instagram->setAccessToken($token);

    //     $media = $instagram->getUserMedia();
    //     return $media->data;

    // }
}
