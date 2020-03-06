# InstagramMedia

Craft 3 CMS Plugin to retrieve instagram media from personal account using Facebook's "Instagram Basic API"

I created this plugin with the intension to make a more streamline and easy way to retrieve data from an instagram account from which I have access. This plugin as of now, will not auto renew long live token. In order to renew it you have to re-save the plugin before the token expires.

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
In order to work with Facebook's Instagram Basic API you need to have a application created with a Facebook Developer account. Please follow intructions [here](https://developers.facebook.com/docs/instagram-basic-display-api/) if you are not sure how to achieve this.

After the application set up, you should have a Instagram App Id, Instagram App Secret and Instagram App Redirect URI. 

 1. Switch to the settings page in the control panel and enter the
    values mention above and **click save**.
    If all the fields are entered after saving you should now see a Get Auth Link red button and an empty field for Instagram API Token.
    
 2. Click "Get Auth Link", this will redirect you to a 404 page (this is intended) inside your site. 
 The URL should look something like this:
 `https://mywebsite.com/auth/?code=AQBx-hBsH3...#_`
 
 
 3.	Copy the code between (exclusive) the `=` and `#_`
 Note that `#_` will be appended to the end of the redirect URI, but it is not part of the code itself, so strip it out.
 Paste this code in the Instgram API Token field in the plugin's settings and **click save**.

If everything was set up correctly you should now see a **Conection succesfull to Instgram's API** message and an expiration day equal to 60 days.
In order to renew the token before it expires you just have to save the plugin and it will renew the long-live token. 

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

Here is an example if you need to check if the item has a caption and also if the item is a video or an image, you need to make this check because images and videos have different sources for image url and video thumbnail respectively:
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
