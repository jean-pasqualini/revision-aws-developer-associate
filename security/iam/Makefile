create-stack:
	aws --profile treezor-playground cloudformation create-stack --stack-name iam-revision --template-body file://template.yaml --capabilities CAPABILITY_NAMED_IAM
update-stack:
	aws --profile treezor-playground cloudformation update-stack --parameters ParameterKey=PasswordParameter,ParameterValue=${PASSWORD} --stack-name iam-revision --template-body file://template.yaml --capabilities CAPABILITY_NAMED_IAM
list-stack-events:
	aws --profile treezor-playground cloudformation describe-stack-events --stack-name iam-revision
delete-stack:
	aws --profile treezor-playground cloudformation delete-stack --stack-name iam-revision