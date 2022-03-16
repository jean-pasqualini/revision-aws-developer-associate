include .env
SHELL := /bin/bash

create-key-symetric:
	aws --profile treezor-playground kms create-key --query KeyMetadata.KeyId --output text | ./addenv.sh SYMETRIC_KEY_ID

# 223959b6-d52a-45e6-a4cf-c7f5a1fcd6ad
list-keys:
	aws --profile treezor-playground kms list-keys

encrypt:
	aws --profile treezor-playground kms encrypt --key-id $(SYMETRIC_KEY_ID) --plaintext fileb://source.txt --query CiphertextBlob --output text | base64 --decode > source.txt.encryped.assymetric

show-encrypt:
	echo "encrypted content : $$(cat source.txt.encryped.assymetric)"

generate-data-key: # from asym or sym ?
	aws --profile treezor-playground kms generate-data-key