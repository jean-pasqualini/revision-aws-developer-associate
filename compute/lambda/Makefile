# https://docs.aws.amazon.com/lambda/latest/dg/invocation-retries.html
deploy:
	sam deploy --profile treezor-playground --stack-name revision-lambda --resolve-s3 --region eu-west-3 --capabilities CAPABILITY_IAM
invoke:
	aws --profile treezor-playground lambda