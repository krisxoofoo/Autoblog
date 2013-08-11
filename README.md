# Autoblog #

This is a very simple tool which replicates articles from another blog. If the source article is removed, or even the whole source website disappears, the articles will remain readable on the autoblog. The whole idea is to setup as many autoblogs as possible, so that, when an article is published, it is replicated all over the place, making much more difficult to censor it.

### Getting Started ###
1.  Unpack the zip file on your server
2.  Tweak mirror settings in `vvb.ini`
3.  Define non mandatory settings in `config.php`
4.  That's all Folks!

### Requirement ###
*	HTTP server.
*	PHP version 5.3.
*	SQLite3.

### Requirement for source feed ##
*	Source feed MUST be a valid RSS 2.0, RDF 1.0 or ATOM 1.0 feed.
*	Source feed MUST be valid UTF-8.
*	Source feed MUST contain article body.
*	Only media from the hosts declared in `DOWNLOAD_MEDIA_FROM=` in `vbb.ini` will be downloaded.

### Credit ###
*	The autoblog original concept is an idea of Mitsukarenai (aka Arkados), the webmaster of fansub-streaming.eu
*	The initial version of VroumVroumBlog was made by an anonymous.
*	The v.0.1 (no database) of VroumVroumBlog was made by Sebsauvage.
*	The v.0.2 (SQLite) of VroumVroumBlog was made by Bohwaz

Licence: Public Domain

### References ###
*	http://sebsauvage.net/streisand.me/
*	http://bohwaz.net/p/Auto-blog-VroumVroumBlog-et-effet-Streisand
*	http://streisand.me/

