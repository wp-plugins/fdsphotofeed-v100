=== fdsPhotoFEED v1.0.0 ===
Contributors: Nurul Ferdous
Donate link: http://www.sadiqsoft.com/donate/
Tags: Smugmug, Flickr, Picsa, Photo, Slideshow
Requires at least: 2.0.2
Tested up to: 2.3
Stable tag: 1.0.0

A WordPress plugin for grabbing images and image info from SmugMug, Flickr, Picasa etc RSS 

feed.

== Description ===

The fdsPhotoFEED class uses the lastRSS class to grab images from a
SmugMug, Flickr, Picasa etc RSS feed to display on any wordpress
hosted website. It provides access to the image’s title, description,
SmugMug, Flickr, Picasa etc page URL, and the image in any size that
SmugMug, Flickr, Picasa etc offers.

=== Features ===

  1. It can fetch photos from SmugMug.
  2. It can fetch photos from Flickr.
  3. It can fetch photos from Picasa & any other RSS feed.
  4. It supports image caching
  5. It supports lightbox2 slideshow with navigation button Prev &
Next.
  6. Image Caption is fetched and shown in lightbox2 show

=== Installation ====

   * Download and unzip the latest version of the plug-in.
   * If you have a previous version of fdsPhotoFEED installed,
disable it on the WordPress Plugins page.
   * Place the “fdsPhotoFEED” folder in your “wp-content/plugins/”
directory (over write the old folder if it exists).
   * Activate fdsPhotoFEED on the WordPress Plugins page.
   * Insert “{rss uri=yourFeedURLgoesHere limit=10}” (minus the
quotes and spaces) in the source of any page or post you want to
display the form on.

=== Requirements ===

   * wp-lightbox2 plugin for lightbox2 show. You may get it from here: 

http://zeo.unic.net.my/notes/lightbox2-for-wordpress/


=== sample formats of the {rss} tag ===

1.{rss uri=http://seismictalk.smugmug.com/hack/feed.mg?
Type=nicknameRecentPhotos&Data=SeismicTalk&format=rss200 limit=5}

there are 2 attribute in this {rss} tag which we look for

1. uri = rss feed location

2. limit = how many photo to show