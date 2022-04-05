STACKNAME := "code-deploy-revision"

package:
	aws --profile treezor-playground cloudformation package --template-file template.yaml --output-template packaged.yaml --s3-bucket revision-935529178062-cloudformation

create-stack: package
	aws --profile treezor-playground cloudformation create-stack --stack-name $(STACKNAME) --template-body file://packaged.yaml --capabilities CAPABILITY_NAMED_IAM

update-stack: package
	aws --profile treezor-playground cloudformation update-stack --stack-name $(STACKNAME) --disable-rollback --template-body file://packaged.yaml --capabilities CAPABILITY_NAMED_IAM CAPABILITY_AUTO_EXPAND
	aws --profile treezor-playground cloudformation wait stack-update-complete --stack-name $(STACKNAME)

create-source-bucket:
	aws --profile treezor-playground s3 mb s3://revision-codebuild-935529178062
update-source-bucket:
	aws --profile treezor-playground s3 sync src/ s3://revision-codebuild-935529178062/src/

list-stack-events:
	aws --profile treezor-playground cloudformation describe-stack-events --stack-name $(STACKNAME)
delete-stack:
	aws --profile treezor-playground cloudformation delete-stack --stack-name $(STACKNAME)

build:
	rm codedeploy-onpremise.zip || true
	cd src/codedeploy/onpremise/ && zip -r ../../../codedeploy-onpremise.zip ./* && cd -
	zipinfo codedeploy-onpremise.zip

upload-s3: build
	aws --profile treezor-playground s3 cp ./codedeploy-onpremise.zip s3://revision-codebuild-935529178062/codedeploy-onpremise.zip

test-lambda:
	 aws --profile treezor-playground lambda invoke --qualifier prod --function-name codedeploy-revision-lambda /dev/stderr 1> /dev/null

create-lambda-deploy:
	./create-deploy-lambda.sh ${DEPLOYMENT}