STACKNAME := "step-function-revision"

package:
	aws --profile treezor-playground cloudformation package --template-file template.yaml --output-template packaged.yaml --s3-bucket revision-935529178062-cloudformation

create-stack: package
	aws --profile treezor-playground cloudformation create-stack --stack-name $(STACKNAME) --template-body file://packaged.yaml --capabilities CAPABILITY_NAMED_IAM

update-stack: package
	aws --profile treezor-playground cloudformation update-stack --stack-name $(STACKNAME) --template-body file://packaged.yaml --capabilities CAPABILITY_NAMED_IAM CAPABILITY_AUTO_EXPAND
	aws --profile treezor-playground cloudformation wait stack-update-complete --stack-name $(STACKNAME)

list-stack-events:
	aws --profile treezor-playground cloudformation describe-stack-events --stack-name $(STACKNAME)

delete-stack:
	aws --profile treezor-playground cloudformation delete-stack --stack-name $(STACKNAME)

.PHONY: validate
validate:
	./validate

# https://docs.aws.amazon.com/step-functions/latest/dg/sfn-local-test-sm-exec.html
run-mock-step-server:
	docker-compose up

create-state-machine:
	aws --profile treezor-playground stepfunctions create-state-machine --endpoint http://localhost:8083 --definition file://step_definition.json --name "Demo" --role-arn "arn:aws:iam::123456789012:role/service-role/LambdaSQSIntegration"

update-state-machine:
	aws --profile treezor-playground stepfunctions update-state-machine --endpoint http://localhost:8083 --definition file://step_definition.json --state-machine-arn="arn:aws:states:us-east-1:123456789012:stateMachine:Demo"

start-execution:
	aws --profile treezor-playground stepfunctions start-execution --endpoint http://localhost:8083 --name ${EXECUTION_ID} --state-machine arn:aws:states:us-east-1:123456789012:stateMachine:Demo#HappyPath

history-execution:
	aws --profile treezor-playground stepfunctions get-execution-history --endpoint http://localhost:8083 --execution-arn arn:aws:states:us-east-1:123456789012:execution:Demo:${EXECUTION_ID}