STACKNAME := "rest-revision"

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

deploy-canary:
	aws --profile treezor-playground apigateway create-deployment --rest-api-id iac5c6x5se --stage-name production --description 'Deployed from CLI' --canary-settings percentTraffic=50

deploy-standard:
	aws --profile treezor-playground apigateway create-deployment --rest-api-id iac5c6x5se --stage-name production --description 'Deployed from CLI'

canary-is-alive:
	aws --profile treezor-playground apigateway update-stage --rest-api-id iac5c6x5se --stage-name production --patch-operations file://canary-is-alive.json

canary-is-dead:
	aws --profile treezor-playground apigateway update-stage --rest-api-id iac5c6x5se --stage-name production --patch-operations file://canary-is-dead.json

canary-destroy:
	aws --profile treezor-playground apigateway update-stage --rest-api-id iac5c6x5se --stage-name production --patch-operations '[{"op":"remove", "path":"/canarySettings"}]'