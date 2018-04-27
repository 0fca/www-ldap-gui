# Rose
Simple WWW GUI for OpenLDAP server.

# What is this?

This is a simple server-side application for managing LDAP server. 
I developed it while I was working in corporation: infinite IT Solutions as a quick solution for internal management's purposes.
It is built using pure PHP 7.0 and HTML5 with CSS3.
All code settled here is done by myself, however it is *dirty*, it was refactored only once, I am __NOT__ a front-end even a back-end developer of PHP language. I've implemented pseudo-MVC there without any other design pattern as there was no need of it. 


# Some technical info

Software was tested on Debian 9 with OpenLDAP server accessible via Stretch repos. LDAP was in its v.3.0 with SSL. 
All credentials and connection info are stored in constants.php. 
The app consists of one public view for password change accessible for any user registered in the system and the admin control panel in which there are views(there is no authorization implemented in the system, it needs to be implemented by hand or via .htaccess file):
* edit user data view,
* add user view,
* delete user view,
* group add view,
* add user to a group view.

Most of mentioned features are not implemented at the moment right now. 

*Note about language*: Because I am a Polish guy and the corporation is Polish to I was supposed to use Polish lanugage in the app. 

# Images - a quick glance
![Welcome page of an admin panel](https://i.imgur.com/xYGt80l.png)
![Password change view](https://i.imgur.com/WBTxhU4.png)
![List of users view](https://i.imgur.com/WBTxhU4.png)
