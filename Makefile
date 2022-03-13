update-policy-inline:
	aws --profile treezor-playground iam put-user-policy --user-name jean.pasqualini --policy-name=RestrictedJeanPasqualini --policy-document file://policy.json
update-policy:
	#aws --profile treezor-playground iam delete-policy-version --version-id v8 --policy-arn arn:aws:iam::935529178062:policy/RestrictedJeanPasqualini
	aws --profile treezor-playground iam create-policy-version --policy-arn arn:aws:iam::935529178062:policy/RestrictedJeanPasqualini --policy-document file://policy.json --set-as-default
attach-policy:
	aws --profile treezor-playground iam attach-user-policy --user-name jean.pasqualini --policy-arn arn:aws:iam::935529178062:policy/RestrictedJeanPasqualini
create-password:
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

identity-pool-unauthenticated:
# no user pool nescessary
aws --profile treezor-playground-jp cognito-identity get-id --identity-pool-id eu-west-3:ebe36a37-76ec-4b6c-9af4-63da0cd69c02
aws --profile treezor-playground-jp cognito-identity get-credentials-for-identity --identity-id eu-west-3:9bea8974-5eb4-4a4e-a2d5-4a09bb100aa9

assume:
	aws --profile treezor-playground-jp sts assume-role-with-web-identity --role-arn arn:aws:iam::935529178062:role/jpsts-role --role-session-name jpsts --web-identity-token $(TOKEN)