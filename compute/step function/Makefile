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

create-local-state-machine:
	aws --profile treezor-playground stepfunctions create-state-machine --endpoint http://localhost:8083 --definition file://machine/${MACHINE}.json --name ${MACHINE} --role-arn "arn:aws:iam::935529178062:role/StepFunctionRevisionExecutionRole"

create-aws-state-machine:
	aws --profile treezor-playground stepfunctions create-state-machine --definition file://machine/${MACHINE}.json --name ${MACHINE} --role-arn "arn:aws:iam::935529178062:role/StepFunctionRevisionExecutionRole"

update-local-state-machine:
	aws --profile treezor-playground stepfunctions update-state-machine --endpoint http://localhost:8083 --definition file://machine/${MACHINE}.json --state-machine-arn="arn:aws:states:eu-west-3:123456789012:stateMachine:${MACHINE}" > /dev/null

update-aws-state-machine:
	aws --profile treezor-playground stepfunctions update-state-machine --definition file://machine/${MACHINE}.json --state-machine-arn="arn:aws:states:eu-west-3:935529178062:stateMachine:${MACHINE}" > /dev/null

.PHONY: start-local-execution
start-local-execution: update-local-state-machine
	./start-local-execution ${MACHINE}

.PHONY: start-aws-execution
start-aws-execution:
	./start-aws-execution ${MACHINE}