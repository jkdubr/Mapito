# Mapito

Mapito is opensource project for editing and sharing spatial data. It is map manager, which create new map portal with any WMS layers in a few seconds.
Mapito is made available under a MIT-license.

Contact information: Jakub Dubrovsky (jkdubr@hotmail.com)


## Getting Mapito

Mapito is available at (https://github.com/jkdubr/Mapito) .

## Requirments

PHP, MySQL, PostGIS, Geoserver


create PostGIS template database in PostGIS - template database generally created by the Postgis installer to ease the process of  creating Postgis-enabled databases.

set chmod for Mapito folder - linux: sudo chmod -R 755 /

install CURL for PHP - ubuntu: apt-get install php5-curl



## Installing Mapito

Download Mapito from GitHub (https://github.com/jkdubr/Mapito/) and prepare backend - PHP, MySQL, PostGIS and Geoserver

### Install PHP and MySQL on OpenShift
Mapito runs on PHP and MySQL. You can use your PHP server or free opensource solution OpenShift.

A How-to Guide for Hosting on OpenShift (http://cloud.dzone.com/articles/how-guide-hosting-openshift)

### Install PostGIS
PostGIS can be install on OpenShift. Unfortunatelly now OpenShift does not support connecting to database from one OpenShift application to another. So, you have to run PostGIS somewhere else :(

How to install PostGIS (http://postgis.refractions.net/documentation/manual-1.3/ch02.html)

### Install Geoserver on OpenShift
For installing Geoserver look at my previous blogpost (http://dubrovsky.posterous.com/geoserver-on-openshift) , it is easy.

### Push Mapito to server

Transfer Mapito on your PHP server or PUSH it to OpenShift.

## Getting started with Mapito

### Account set up

You can set up Mapito in short time and run your map portal. Fill in your MySQL account for storing metadata, PostGIS account for storing GIS data, Geoserver account and URL where Mapito is. Mapito admin page URL is URL where is root Mapito page. Mapito viewer url is where Mapito map portal is. Default is http://admin.your_domain.com/module/viewer, but you can change it.

![alt](https://public.sn2.livefilestore.com/y1p-AgzKs3qub4iWtxRbfVptGHu5coVxRYaGyFNgNPtcKq8DLTkZPDCLyVFVL84L4eVL6CdmbQGCjcRV8RG7ZuW1w/settings.png?psid=1)

After setting up basic information is send to your mail. Your new Mapito login is your email, password is your email. 

### Enjoy map portal
If your account is set up correctly, go to admin page and disable Mapito setup page. Enjoy map portal.  

If you cannot login into Mapito admin page or have an error in database connection you can go again to setup page /install/settings.php and set up Mapito again.  

![alt](https://public.sn2.livefilestore.com/y1pX5_CiWXrj_9hOT7IYo9ZNhSufRCTgJkP1QBorRnaJnoyKfodJs2EbciMCV8BVlItHGbOZx25CCsbDFQQaik8jw/map.png?psid=1)


## Create first map portal

### Create map portal

![alt](https://public.sn2.livefilestore.com/y1prtplwo0v1Xkzl7dTln8YLfsahEFpts55nMKQBuzuDS-XilyZLSNkR-v5WSA1wsGw3HXyQR4Nshkx3d_XwybO9w/3_planSetting.PNG?psid=1)

### Add remote WMS

![alt](https://public.sn2.livefilestore.com/y1pHvpPZuJNxKmrS3PG3DoipYs2hKDi2lCkaNLvuW8-3BtDhlGLnEiiDenQfE63rw8Z7zx-QyE8MOM7d-rIogBf4w/remote%20WMS.png?psid=1)

### Layer settings

![alt](https://public.sn2.livefilestore.com/y1p-C7oq3YQ3TJIOxWrX4mqkX9eWih2hFSyvbdjVh6y_aPd0sBM3vVuru61WJvKr8Qo4EpQvqnQuiQpClQ4_cCEfA/6_WMS_layerSetting.png?psid=1)

### Your new meteo portal in few steps

![alt](https://public.sn2.livefilestore.com/y1pEmU2TOtR3PorW7_FJnADevcl1t-ZX3kS0ofvnWC-8-duTup-r318P9nzwXNyTsAvW7VECH0GjPTOaLgd8t6XsA/7_metheoMap.PNG?psid=1)

example: (http://admin.mapy.mostar.cz/module/viewer/usa_radar/)

