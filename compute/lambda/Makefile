
deploy:
	sam build --profile treezor-playground --region eu-west-3
	sam deploy --profile treezor-playground --stack-name revision-lambda --resolve-s3 --region eu-west-3 --capabilities CAPABILITY_IAM
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