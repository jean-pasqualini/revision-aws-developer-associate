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