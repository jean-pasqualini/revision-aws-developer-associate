create-user:
	aws --profile treezor-playground-jp cognito-idp admin-create-user --user-pool-id eu-west-3_AfYojov1f --username demo --temporary-password $(PASSWORD)
permanent-password:
	aws --profile treezor-playground-jp cognito-idp admin-set-user-password --user-pool-id eu-west-3_AfYojov1f --username demo --password $(PASSWORD) --permanent
initiate-auth:
	aws --profile treezor-playground-jp cognito-idp initiate-auth --client-id 1sukanjpfbq6j0hmg299ue6fdu --auth-flow USER_PASSWORD_AUTH --auth-parameters USERNAME=demo,PASSWORD=$(PASSWORD)
initiate-auth-curl:
	curl -X POST --data @auth-data.json \
	-H 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth' \
	-H 'Content-Type: application/x-amz-json-1.1' \
	https://cognito-idp.eu-west-3.amazonaws.com/

assume-role-with-web-identity:
	aws sts assume-role-with-web-identity --web-identity-token EAAUJRrxRBdkBAEIwOmcIlM00gZADzLFfBc7Bh4ZBMwXnouZB8uWC4PCKClbcfmuFMSK2LWeyOKyqEe69cgErooFqfKL9YJ5ZBTQqoZC2PUylgynd45leZCPuSJ2xIiKd0m6fV130CzExvTUlIUdFIgTnNzv8PgYOa8qu8KyZA3LdmFYaRinFZAxZBX3a0NJoxEWKxrPuiBVJfXQZDZD --role-session-name ttt --role-arn arn:aws:iam::935529178062:role/jeanpsts --provider-id graph.facebook.com

identity-pool-unauthenticated:
	aws --profile treezor-playground-jp cognito-identity get-id --identity-pool-id eu-west-3:ebe36a37-76ec-4b6c-9af4-63da0cd69c02
	aws --profile treezor-playground-jp cognito-identity get-credentials-for-identity --identity-id eu-west-3:9bea8974-5eb4-4a4e-a2d5-4a09bb100aa9

identity-pool-authenticated:
	aws --profile treezor-playground cognito-idp initiate-auth --auth-flow USER_PASSWORD_AUTH --client-id 1sukanjpfbq6j0hmg299ue6fdu --auth-parameters USERNAME=demo,PASSWORD=$(PASSWORD)
	aws --profile treezor-playground cognito-identity get-id --identity-pool-id eu-west-3:d4535db0-d241-48ed-a660-a4d3665fa62f --logins cognito-idp.eu-west-3.amazonaws.com/eu-west-3_AfYojov1f=$(TOKEN)
	aws --profile treezor-playground cognito-identity get-credentials-for-identity --identity-id eu-west-3:ef9a3a0a-640b-47d3-a69d-c92ecd6a5634 --logins cognito-idp.eu-west-3.amazonaws.com/eu-west-3_AfYojov1f=$(TOKEN)

assume:
	aws --profile treezor-playground-jp sts assume-role-with-web-identity --role-arn arn:aws:iam::935529178062:role/jpsts-role --role-session-name jpsts --web-identity-token $(TOKEN)