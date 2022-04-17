STACKNAME := "cloudfront-revision"

package:
	aws --profile treezor-playground cloudformation package --template-file template.yaml --output-template packaged.yaml --s3-bucket revision-935529178062-cloudformation

create-stack: package
	aws --profile treezor-playground cloudformation create-stack --stack-name $(STACKNAME) --template-body file://packaged.yaml --capabilities CAPABILITY_NAMED_IAM

update-stack: package
	aws --profile treezor-playground cloudformation update-stack --stack-name $(STACKNAME) --template-body file://packaged.yaml --capabilities CAPABILITY_NAMED_IAM CAPABILITY_AUTO_EXPAND
	aws --profile treezor-playground cloudformation wait stack-update-complete --stack-name $(STACKNAME)

create-source-bucket:
	aws --profile treezor-playground s3 mb s3://revision-codebuild-935529178062
update-source-bucket:
	aws --profile treezor-playground s3 sync src/ s3://revision-codebuild-935529178062/src/

list-stack-events:
	aws --profile treezor-playground cloudformation describe-stack-events --stack-name $(STACKNAME)
delete-stack:
	aws --profile treezor-playground cloudformation delete-stack --stack-name $(STACKNAME)

test-s3:
	curl -i https://d3e408dinn8c6g.cloudfront.net/s3/index.html