<!-- https://docs.aws.amazon.com/codedeploy/index.html -->

target:
<!-- https://docs.aws.amazon.com/codedeploy/latest/userguide/deployment-steps.html -->
- aws lambda
- aws ECS
- acs ec2/on-premise

deploiement mode:
<!-- https://docs.aws.amazon.com/codedeploy/latest/userguide/applications-create.html -->
- In-place deployment
  - ec2/onpremise
- Blue/Green
  - EC2/onpremise
  - AWS Lambda or ECS
  - Through AWS CloudFormation

## References
- sudo aws deploy install --region eu-west-3 --config-file /home/darkilliant/codedeploy.onpremises.yml
- aws --profile treezor-playground deploy register --instance-name MyComputer --region eu-west-3
- wget https://aws-codedeploy-eu-west-3.s3.eu-west-3.amazonaws.com/latest/install 
- https://docs.aws.amazon.com/codedeploy/latest/userguide/reference-appspec-file-example.html
- https://docs.aws.amazon.com/codedeploy/latest/userguide/deployments-view-logs.html
- https://docs.aws.amazon.com/codedeploy/latest/userguide/reference-appspec-file-structure-hooks.html
- 
## Apprentisages

### Environement variables
- APPLICATION_NAME
- DEPLOYMENT_ID
- DEPLOYMENT_GROUP_NAME
- DEPLOYMENT_GROUP_ID
- LIFECYCLE_EVENT

## Scenarios

### InPlace Deploy

![](images/lifecycle-event-order-in-place.png)

### BlueGreen Deploy

![](images/lifecycle-event-order-blue-green.png)