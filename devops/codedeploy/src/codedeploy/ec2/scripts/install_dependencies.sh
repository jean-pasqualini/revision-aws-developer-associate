#!/bin/bash
apt install -y apache2
cp /var/www/html/project-onpremise/config/vhost.conf /etc/apache2/sites-enabled/onpremise-codedeploy.local.conf || true