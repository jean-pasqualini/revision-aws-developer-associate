create-stack:
	aws --profile treezor-playground cloudformation create-stack --stack-name websocket-revision --template-body file://template.yaml --capabilities CAPABILITY_NAMED_IAM
update-stack:
	aws --profile treezor-playground cloudformation update-stack --stack-name websocket-revision --template-body file://template.yaml --capabilities CAPABILITY_NAMED_IAM --query StackId --output text
	aws --profile treezor-playground cloudformation wait stack-update-complete --stack-name websocket-revision
list-stack-events:
	aws --profile treezor-playground cloudformation describe-stack-events --stack-name websocket-revision
delete-stack:
	aws --profile treezor-playground cloudformation delete-stack --stack-name websocket-revision