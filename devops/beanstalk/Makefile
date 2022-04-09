STACKNAME := "beanstalk-revision"

package:
	aws --profile treezor-playground cloudformation package --template-file template.yaml --output-template packaged.yaml --s3-bucket revision-935529178062-cloudformation

create-stack: package
	aws --profile treezor-playground cloudformation create-stack --stack-name $(STACKNAME) --template-body file://packaged.yaml --capabilities CAPABILITY_NAMED_IAM

update-stack: package # --disable-rollback
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

update-source:
	rm source.zip || true
	cd src && zip -r ../source.zip ./ && cd -
	aws --profile treezor-playground s3 cp ./source.zip s3://revision-beanstalk-935529178062/source.zip

list-solution-stacks:
	aws --profile treezor-playground elasticbeanstalk list-available-solution-stacks
install-ebcli:
	pip install awsebcli --upgrade --user