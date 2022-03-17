include .env
SHELL := /bin/bash

# https://enlear.academy/data-encryption-on-aws-part-02-ecb5b1e15451
# https://mechanicalrock.github.io/2020/01/20/kms-assym-encryption.html
# https://wiki.openssl.org/index.php/Command_Line_Utilities
# ORIGIN: AWS_KMS, EXTERNAL, AWS_CLOUDHSM
# KEYSPECS:
## SYMETRIC: SYMMETRIC_DEFAULT
## ASYMMETRIC RSA key material: RSA_2048, RSA_3072, RSA_4096
## ASYMMETRIC ECC key material: ECC_NIST_P256, ECC_NIST_P384, ECC_NIST_P521, ECC_SECG_P256K1
# USAGE
## SYMETRIC: ENCRYPT_DECRYPT
## ASSYMETRIC RSA key material: ENCRYPT_DECRYPT or SIGN_VERIFY
## ASSYMETRIC ECC key material: SIGN_VERIFY


create-key-symetric:
	aws --profile treezor-playground kms create-key --query KeyMetadata.KeyId --output text | ./addenv.sh SYMETRIC_KEY_ID
create-key-assymetric:
	aws --profile treezor-playground kms create-key

### SYMETRIC.KEY

# 223959b6-d52a-45e6-a4cf-c7f5a1fcd6ad
list-keys:
	aws --profile treezor-playground kms list-keys

encrypt-in-kms:
	aws --profile treezor-playground kms encrypt --key-id $(SYMETRIC_KEY_ID) --plaintext fileb://source.txt --query CiphertextBlob --output text | base64 --decode > source.txt.encrypted.assymetric

decrypt-in-kms:
	aws --profile treezor-playground kms decrypt --ciphertext-blob fileb://source.txt.encrypted.assymetric --encryption-algorithm SYMMETRIC_DEFAULT --query Plaintext --output text | base64 --decode > source.txt.decrypted.assymetric

show-encrypted-with-kms:
	echo "encrypted content : $$(cat source.txt.encrypted.assymetric)"
show-decrypted-with-kms:
	echo "encrypted content : $$(cat source.txt.decrypted.assymetric)"

### SYMETRIC.DATAKEY

encrypt-with-data-key:
	aws --profile treezor-playground kms generate-data-key-without-plaintext --key-id $(SYMETRIC_KEY_ID) --key-spec AES_256 --query CiphertextBlob --output text  | base64 --decode > datakey.encrypted
	aws --profile treezor-playground kms decrypt --ciphertext-blob fileb://datakey.encrypted --encryption-algorithm SYMMETRIC_DEFAULT --query Plaintext --output text | base64 --decode > datakey.decrypted
	openssl enc -in ./source.txt -out ./source.txt.encrypted.datakey -e -aes256 -k fileb://./datakey.decrypted

decrypt-with-data-key:
	aws --profile treezor-playground kms decrypt --ciphertext-blob fileb://./datakey.encrypted --query Plaintext --output text | base64 --decode > datakey.decrypted
	openssl enc -in ./source.txt.encrypted.datakey -out ./source.txt.decrypted.datakey -d -aes256 -k fileb://./datakey.decrypted

show-encrypted-with-data-key:
	echo "decruypted content : $$(cat source.txt.encrypted.datakey)"
show-decrypted-with-data-key:
	echo "decruypted content : $$(cat source.txt.decrypted.datakey)"

### SYMETRIC.DATAKEY.ASSYMETRIC

encrypt-with-data-key-pair:
	aws --profile treezor-playground kms generate-data-key-pair-without-plaintext --key-id $(SYMETRIC_KEY_ID) --key-pair-spec RSA_4096 --output json > datakeypair.json
	echo -e "-----BEGIN PUBLIC KEY-----\n$$(cat datakeypair.json | jq -r ".PublicKey" | fold -w60)\n-----END PUBLIC KEY-----" > datakeypair.public.decrypted
	openssl pkeyutl -encrypt -pubin -inkey datakeypair.public.decrypted -in source.txt -out source.txt.encrypted.datakeypair

decrypt-with-data-key-pair:
	echo -e "-----BEGIN PRIVATE KEY-----\n$$(aws --profile treezor-playground kms decrypt --ciphertext-blob fileb://<(jq -r '.PrivateKeyCiphertextBlob' datakeypair.json | base64 --decode) --output text --query Plaintext | fold -w60)\n-----END PRIVATE KEY-----" > datakeypair.private.decrypted
	openssl pkeyutl -decrypt -inkey datakeypair.private.decrypted -in source.txt.encrypted.datakeypair -out source.txt.decrypted.datakeypair

### ASSYMETRIC