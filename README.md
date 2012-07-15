# Mapito

Mapito is opensource project for editing and sharing spatial data. It is map manager, which create new map portal with any WMS layers in a few seconds.
Mapito is made available under a BSD-license.


## Getting Mapito

Mapito is available at https://github.com/jkdubr/Mapito.

## Requirments

PHP, MySQL, PostGIS, Geoserver

## Installing Mapito

Download Mapito from GitHub https://github.com/jkdubr/Mapito/

Prepare backend - PHP, MySQL, PostGIS and Geoserver

### Install PHP and MySQL on OpenShift
Mapito runs on PHP and MySQL. You can use your PHP server or free opensource solution OpenShift.

A How-to Guide for Hosting on OpenShift http://cloud.dzone.com/articles/how-guide-hosting-openshift

### Install PostGIS
PostGIS can be install on OpenShift. Unfortunatelly now OpenShift does not support connecting to database from one OpenShift application to another. So, you have to run PostGIS somewhere else :(
How to install PostGIS http://postgis.refractions.net/documentation/manual-1.3/ch02.html

### Install Geoserver on OpenShift
For installing Geoserver look at my previous blogpost http://dubrovsky.posterous.com/geoserver-on-openshift

### Push Mapito to server

Transfer Mapito on your PHP server or PUSH it to OpenShift.

## Settings Mapito

### First settings

<screen>
<screen confirm>

### Enjoy first map portal

<screen example>

## Create your first map portal

<screen>

### Create map portal

### Add public layers
### Add any WMS
### Add PostGIS layer


