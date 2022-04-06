#!/bin/bash
sed -i -e "s/DEPLOYMENT_ID/$DEPLOYMENT_ID/g" -e "s/APPLICATION_NAME/$APPLICATION_NAME/" /var/www/html/project-onpremise/index.html