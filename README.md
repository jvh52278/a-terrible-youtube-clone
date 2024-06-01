A content hosting website I made because I didn't feel like installing and setting existing solutions such as Plex. I made this specifically for use on a secured local network. In other words, there are probably security flaws, so do not deploy this thing on the actual internet. As I originally created this for my own use, it might be coded in a manner that could be described as slightly crude. That said, it does work.

So how does it work? To access the site, you need to first login. This requires creating a user account, which also requires an admin password. The admin password (which you can also find in the database setup instructions) is "admin". Once you've logged in, you can upload and view videos. You can also delete uploads. As far as account management goes, you can change your password. That's about it. There are buttons and pages for other features, but those do not work. I stopped working on them when I realized that I didn't really need them.

The following dependencies were used:
<br>
ffmpeg
<br>
mysql 8.0.32
<br>
apache 2.4.52
<br>
php 8.1.2

Make sure that:
<br>
apache, php and mysql are installed
<br>
<br>
you run the database setup script to set up the database
<br>
<br>
apache/php is configured to
<br>
	- accept file uploads
<br>
	- accept file uploads that are large in size
<br>
	- accept POST upload/file sizes that are large in size
<br>
<br>
the root directory of the website is accessible to the user account that will be used to run/edit the site
<br>
	- the uploads directory and everything in that directory should be accessible -- chmod 777 is a quick and dirty way, but it is better to make it a shared folder

<br>
change the database access information on the following files: 
<br>
	- mysql_access_functions.php
<br>
<br>
Other notes:
<br>
- This was developed and tested on Ubuntu 22.04. If hosted on a non-ubuntu (or non-linux) system, you may need to adjust file paths in the upload processing pages.
<br>
- Part of the video processing is done clientside. Uploading a video with a video and/or audio codec that your browser does not recognize may result in a failed upload.