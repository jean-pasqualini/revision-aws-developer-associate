#!/bin/bash
aws --profile treezor-playground s3 cp src/codedeploy/lambda/appspec.yml s3://revision-codebuild-935529178062/lambda-appspec.yml

aws --profile treezor-playground deploy create-deployment \
  --deployment-group-name $1 \
  --application-name revision-codedeploy-lambda \
  --s3-location bucket=revision-codebuild-935529178062,bundleType=YAML,key=lambda-appspec.yml > /dev/null