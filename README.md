# InstagramMedia

Craft 3 CMS Plugin to retrieve Instagram media (from an account you have access to) using Facebook’s “Instagram Basic API”

I created this plugin to streamline and facilitate a way to retrieve data from an Instagram account that I have access to. As of now this plugin, will not auto-renew long live token. In order to renew it you have to re-save the plugin before the token expires.

#### Requirements: 
 - Craft 3 
 - espresso-dev/instagram-basic-display-php

#### Installation
```
cd /path/to/project
composer require heyblackmagic/instagrammedia
./craft install/plugin instagrammedia
```
#### Configuration
In order to work with Facebook's Instagram Basic API you need to have previously created an application with a Facebook Developer account. Please follow intructions [here](https://developers.facebook.com/docs/instagram-basic-display-api/) if you are not sure how to achieve this.

After the application is set up, you should have a Instagram App Id, Instagram App Secret and Instagram App Redirect URI.

 1. In Craft CMS, switch to the Settings page in the control panel and enter the values mentioned above and **click Save**. If all the fields are entered correcltly, after saving, you should now see a Get Auth Link red button and an empty field for Instagram's API Token.
    
 2. Click "Get Auth Link", this will redirect you to a 404 page (this is intended) inside your site. 
 The URL should look something like this:
 `https://mywebsite.com/auth/?code=AQBx-hBsH3...#_`
 
 
 3.	Copy the code between (exclusive) the `=` and `#_`
 Note that `#_` will be appended to the end of the redirect URI, but it is not part of the code itself, so strip it out. Paste this code in the Instgram API Token field in the plugin's settings and **click Save**.

If everything was set up correctly you should now see a **Conection succesfull to Instgram's API** message and an expiration day equal to 60 days. In order to renew the token before it expires you just have to save the plugin and it will renew the long-live token.

#### Usage
To fetch the media from your account  call `getMedia()` 
you can check all available fields on [Instagram Basic API Docs](https://developers.facebook.com/docs/instagram-basic-display-api/reference/media#fields)

Eg:
```
{% set media = craft.instagram.getMedia() %}
{% for item in media %}
	<li>
		...
	</li>
{% endfor %}
```	

Here is an example if you need to check if the item has a caption or if the item is a video or an image. You need to make this check because images and videos have different sources for image url and video thumbnail respectively:
```
{% set media = craft.instagram.getMedia() %}
{% for item in media %}
	{% set permalink = item.permalink %}
	{% set caption = item.caption is defined ? item.caption : 'Instgram Picture' %}
	<li>
		{% if item.media_type == 'VIDEO'%}
			<a  href="{{permalink}}"  target="_blank"><img  src="{{item.thumbnail_url}}"  alt="{{caption}}"></a>
		{% else %}
			<a  href="{{permalink}}"  target="_blank"><img  src="{{item.media_url}}"  alt="{{caption}}"></a>
		{% endif %}
	</li>
{% endfor %}
```

#### To Do:

 - Add auto renew of long live token.
 - Better error handling.
 - Add limit on how many posts to retrieve.