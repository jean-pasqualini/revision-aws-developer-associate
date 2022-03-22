# https://docs.aws.amazon.com/serverless-application-model/latest/developerguide/authoring-codesigning.html
create-signing-profile:
	aws --profile treezor-playground signer put-signing-profile --platform-id "AWSLambda-SHA384-ECDSA" --profile-name MySigningProfile
package:
	#sam package --profile treezor-playground --signing-profiles HelloWorld=MySigningProfile --resolve-s3 --output-template-file packaged.yaml

deploy:
	sam build --profile treezor-playground --signing-profiles HelloWorld=MySigningProfile
	sam deploy --profile treezor-playground --resolve-s3
delete:
	sam delete --profile treezor-playground --stack-name revision-lambda --region eu-west-3
sync-invoke:
	aws --profile treezor-playground lambda invoke --log-type Tail --invocation-type DryRun --function-name bye-lambda /dev/stdout
async-invoke:
	aws --profile treezor-playground lambda invoke --cli-binary-format raw-in-base64-out --payload '{ "color": "green" }' --invocation-type Event --log-type Tail --function-name bye-lambda /dev/stdout

case-retry:
	./case.sh retry 120 Event

case-concurrency-not-blocking:
	./case.sh invoke-event concurency

case-concurrency-blocking:
	./case.sh invoke-request concurency