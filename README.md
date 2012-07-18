# Mapito

Mapito is opensource project for editing and sharing spatial data. It is map manager, which create new map portal with any WMS layers in a few seconds.
Mapito is made available under a BSD-license.


## Getting Mapito

Mapito is available at https://github.com/jkdubr/Mapito.

## Requirments

PHP, MySQL, PostGIS, Geoserver

## Installing Mapito

Download Mapito from GitHub https://github.com/jkdubr/Mapito/ and prepare backend - PHP, MySQL, PostGIS and Geoserver

### Install PHP and MySQL on OpenShift
Mapito runs on PHP and MySQL. You can use your PHP server or free opensource solution OpenShift.

A How-to Guide for Hosting on OpenShift http://cloud.dzone.com/articles/how-guide-hosting-openshift

### Install PostGIS
PostGIS can be install on OpenShift. Unfortunatelly now OpenShift does not support connecting to database from one OpenShift application to another. So, you have to run PostGIS somewhere else :(

How to install PostGIS http://postgis.refractions.net/documentation/manual-1.3/ch02.html

### Install Geoserver on OpenShift
For installing Geoserver look at my previous blogpost http://dubrovsky.posterous.com/geoserver-on-openshift , it is easy.

### Push Mapito to server

Transfer Mapito on your PHP server or PUSH it to OpenShift.

## Getting started with Mapito

### Account set up

You can set up Mapito in short time and run your map portal. Fill in your MySQL account for storing metadata, PostGIS account for storing GIS data, Geoserver account and URL where Mapito is. Mapito admin page URL is URL where is root Mapito page. Mapito viewer url is where Mapito map portal is. Default is http://admin.your_domain.com/module/viewer, but you can change it.


<screen>

After setting up basic information is send to your mail. Your new Mapito login is your email, password is your email. 

### Enjoy map portal
If your account is set up correctly, go to admin page and disable Mapito setup page. Enjoy map portal.  

If you cannot login into Mapito admin page or have an error in database connection you can go again to setup page /install/settings.php and set up Mapito again.  

<screen example>


## Create first map portal

<screen>

### Create map portal

### Add predefined layers

### Add any public WMS

### Add PostGIS layer



