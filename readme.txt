===fdsPhotoFEED v1.0.0===

Contributors: Nurul Ferdous
Donate link: http://www.sadiqsoft.com/donate/
Tags: SmugMug, Flickr, Picasa, rss, post, links
Requires at least: 2.0.2
Tested up to: 2.6.3
Stable tag: 1.0.0

A WordPress plugin for grabbing images and image info from SmugMug, Flickr, Picasa etc RSS feeds.

==Description==
The fdsPhotoFEED plugin uses the lastRSS class to grab images from a SmugMug, Flickr, Picasa etc RSS feed to display on any wordpress hosted website. It provides access to the images title, description, SmugMug, Flickr, Picasa etc page URL, and the image in any size that SmugMug, Flickr, Picasa etc offers.

**USAGE:**
you can do it from any of your post or page. you can set any RSS feed like SmugMug, Picasa, Flickr etc with a limit of how many photos you want in your post/page. You need to

keep in mind that you need to assign to things there:

       1. rss uri = feed location
       2. limit = number of photos to be shown

the plugin will replace only the text within the post/page which is encoded with a curly brace {} with resulted photos. The remaindered text will be same.
`{rss uri=http://seismictalk.smugmug.com/hack/feed.mg?Type=nicknameRecentPhotos&Da ta=SeismicTalk&format=rss200 limit=5}`

Alternatively you can leave the limit blank like this to get all the photos

`{rss uri=flickr/picasa/smugmug_rss_feed_location limit=}`

1. Add your RSS Feed in the body of your post or page in the following format

`{rss uri=http://seismictalk.smugmug.com/hack/feed.mg?Type=nicknameRecentPhotos&Data=SeismicTalk&format=rss200 limit=5}`

2. Plublish your post/page.

[ Note: You may set a limit or leave it blank to retreive all photos ]

**FEATURES:**

* It can fetch photos from SmugMug
* It can fetch photos from Flickr
* It can fetch photos from Picasa & any other RSS feed
* It supports image caching
* It supports lightbox2 slideshow with navigation button Prev & Next
* Image Caption is fetched and shown in lightbox2 show

**REQUIREMENTS:**

* wp-lightbox2 plugin for lightbox2 show. You may get it from [HERE](http://zeo.unic.net.my/notes/lightbox2-for-wordpress/ "wplightbox2 plugin")

**COURTESY:**

Thanks goes to [Brian Brigg](http://www.seismictalk.com/ "A noble man") [Hasin Hayder](http://hasin.wordpress.com/ "My Hero") and [Lenin](http://lenin9l.wordpress.com "My Friend") for their endless support.

==Installation==
* Download and unzip the latest version of the plug-in.
* If you have a previous version of fdsPhotoFEED installed,disable it on the WordPress Plugins page.
* Place the fdsPhotoFEED folder in your `wp-content/plugins/` directory (over write the old folder if it exists).
* Activate fdsPhotoFEED on the WordPress Plugins page.
* Insert `{rss uri=yourFeedURLgoesHere limit=10}` (minus the quotes and spaces) in the source of any page or post you want to display the form on.

==Frequently Asked Questions==
* Whic RSS feeds are supported?
It support Smugmug,Flickr, Picasa and more
* Is Lightbox2 supported?
Yes Lightbox2 is supported here

==Screenshots==
1. Coming soon
