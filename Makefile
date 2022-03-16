update-policy-inline:
	aws --profile treezor-playground iam put-user-policy --user-name jean.pasqualini --policy-name=RestrictedJeanPasqualini --policy-document file://policy.json
update-policy:
	#aws --profile treezor-playground iam delete-policy-version --version-id v8 --policy-arn arn:aws:iam::935529178062:policy/RestrictedJeanPasqualini
	aws --profile treezor-playground iam create-policy-version --policy-arn arn:aws:iam::935529178062:policy/RestrictedJeanPasqualini --policy-document file://policy.json --set-as-default
attach-policy:
	aws --profile treezor-playground iam attach-user-policy --user-name jean.pasqualini --policy-arn arn:aws:iam::935529178062:policy/RestrictedJeanPasqualini
