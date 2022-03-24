create-bucket:
	aws --profile treezor-playground s3 mb s3://revision-ec2
push-file:
	aws --profile treezor-playground s3 cp ./template.yaml s3://revision-ec2/template.yaml
create-stack:
	aws --profile treezor-playground cloudformation create-stack --stack-name ec2-revision --template-body file://template.yaml
update-stack:
	aws --profile treezor-playground cloudformation update-stack --stack-name ec2-revision --template-body file://template.yaml
list-stack-events:
	aws --profile treezor-playground cloudformation describe-stack-events  --stack-name ec2-revision
delete-stack:
	aws --profile treezor-playground cloudformation delete-stack --stack-name ec2-revision