- curl -v -d "{}" -X POST https://ltgm6y2oa9.execute-api.eu-west-3.amazonaws.com/bin
- curl -v -d "{\"MessageBody\":\"a\"}" -X POST https://ltgm6y2oa9.execute-api.eu-west-3.amazonaws.com/send-sqs
- curl -v -d "{}" -X POST https://ltgm6y2oa9.execute-api.eu-west-3.amazonaws.com/send-event-bridge
- curl -v -d "{}" -X POST https://ltgm6y2oa9.execute-api.eu-west-3.amazonaws.com/send-kinesis
- curl -v -d "{}" -X POST https://ltgm6y2oa9.execute-api.eu-west-3.amazonaws.com/state-machine
- curl -v https://ltgm6y2oa9.execute-api.eu-west-3.amazonaws.com/lambda

- AWS_PROFILE=treezor-playground-jp awscurl --region eu-west-3 -X=POST -- https://ltgm6y2oa9.execute-api.eu-west-3.amazonaws.com/state-machine

## Authentification IAM
- https://spin.atomicobject.com/2021/12/20/securing-api-gateway-aws-iam/
- https://noise.getoto.net/2018/07/03/control-access-to-your-apis-using-amazon-api-gateway-resource-policies/

## Authentification JWT
- https://aws.amazon.com/fr/blogs/security/how-to-secure-api-gateway-http-endpoints-with-jwt-authorizer/
- url check metadata issuer /.well-known/openid-configuration