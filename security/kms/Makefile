include .env
SHELL := /bin/bash

# https://enlear.academy/data-encryption-on-aws-part-02-ecb5b1e15451
# https://mechanicalrock.github.io/2020/01/20/kms-assym-encryption.html
# https://wiki.openssl.org/index.php/Command_Line_Utilities
# https://tls.mbed.org/kb/cryptography/asn1-key-structures-in-der-and-pem
# https://www.zimuel.it/blog/sign-and-verify-a-file-using-openssl
# https://awscloudfeed.com/whats-new/security/how-to-verify-aws-kms-asymmetric-key-signatures-locally-with-openssl
# openssl rsa -pubin -inform DER \ -outform PEM -in SamplePublicKey.der \ -pubout -out SamplePublicKey.pem# ORIGIN: AWS_KMS, EXTERNAL, AWS_CLOUDHSM
# KEYSPECS:
## SYMETRIC: SYMMETRIC_DEFAULT
## ASYMMETRIC RSA key material: RSA_2048, RSA_3072, RSA_4096
## ASYMMETRIC ECC key material: ECC_NIST_P256, ECC_NIST_P384, ECC_NIST_P521, ECC_SECG_P256K1
# USAGE
## SYMETRIC: ENCRYPT_DECRYPT
## ASSYMETRIC RSA key material: ENCRYPT_DECRYPT or SIGN_VERIFY
## ASSYMETRIC ECC key material: SIGN_VERIFY

### CREATE.KEY

create-key-symetric:
	aws --profile treezor-playground kms create-key --query KeyMetadata.KeyId --output text | ./addenv.sh SYMETRIC_KEY_ID
create-key-assymetric-for-encrypt:
	aws --profile treezor-playground kms create-key --key-usage ENCRYPT_DECRYPT --key-spec RSA_4096 --origin AWS_KMS --tags TagKey=Name,TagValue=asym-key-jp --query KeyMetadata.KeyId --output text | ./addenv.sh ASSYMETRIC_KEY_ID

create-key-assymetric-for-sign:
	aws --profile treezor-playground kms create-key --key-usage SIGN_VERIFY --key-spec RSA_4096 --origin AWS_KMS --query KeyMetadata.KeyId --output text | ./addenv.sh ASSYMETRIC_SIGN_KEY_ID

create-alias-key-assymetric-for-sign:
	aws --profile treezor-playground kms create-alias --alias-name alias/asym-verify-key-jp --target-key-id $(ASSYMETRIC_SIGN_KEY_ID)
# 223959b6-d52a-45e6-a4cf-c7f5a1fcd6ad
list-keys:
	aws --profile treezor-playground kms list-keys

### SYMETRIC.KEY
sym-encrypt-in-kms:
	aws --profile treezor-playground kms encrypt --key-id $(SYMETRIC_KEY_ID) --plaintext fileb://source.txt --query CiphertextBlob --output text | base64 --decode > source.txt.encrypted.symetric

sym-decrypt-in-kms:
	aws --profile treezor-playground kms decrypt --ciphertext-blob fileb://source.txt.encrypted.symetric --encryption-algorithm SYMMETRIC_DEFAULT --query Plaintext --output text | base64 --decode > source.txt.decrypted.symetric

sym-show-encrypted-with-kms:
	echo "encrypted content : $$(cat source.txt.encrypted.symetric)"
sym-show-decrypted-with-kms:
	echo "encrypted content : $$(cat source.txt.decrypted.symetric)"

### SYMETRIC.DATAKEY

sym-encrypt-with-data-key:
	aws --profile treezor-playground kms generate-data-key-without-plaintext --key-id $(SYMETRIC_KEY_ID) --key-spec AES_256 --query CiphertextBlob --output text  | base64 --decode > datakey.encrypted
	aws --profile treezor-playground kms decrypt --ciphertext-blob fileb://datakey.encrypted --encryption-algorithm SYMMETRIC_DEFAULT --query Plaintext --output text | base64 --decode > datakey.decrypted
	openssl enc -in ./source.txt -out ./source.txt.encrypted.datakey -e -aes256 -k fileb://./datakey.decrypted

sym-decrypt-with-data-key:
	aws --profile treezor-playground kms decrypt --ciphertext-blob fileb://./datakey.encrypted --query Plaintext --output text | base64 --decode > datakey.decrypted
	openssl enc -in ./source.txt.encrypted.datakey -out ./source.txt.decrypted.datakey -d -aes256 -k fileb://./datakey.decrypted

sym-show-encrypted-with-data-key:
	echo "decruypted content : $$(cat source.txt.encrypted.datakey)"
sym-show-decrypted-with-data-key:
	echo "decruypted content : $$(cat source.txt.decrypted.datakey)"

### SYMETRIC.DATAKEY.ASSYMETRIC

sym-encrypt-with-data-key-pair:
	aws --profile treezor-playground kms generate-data-key-pair-without-plaintext --key-id $(SYMETRIC_KEY_ID) --key-pair-spec RSA_4096 --output json > datakeypair.json
	echo -e "-----BEGIN PUBLIC KEY-----\n$$(cat datakeypair.json | jq -r ".PublicKey" | fold -w60)\n-----END PUBLIC KEY-----" > datakeypair.public.decrypted
	openssl pkeyutl -encrypt -pubin -inkey datakeypair.public.decrypted -in source.txt -out source.txt.encrypted.datakeypair

sym-decrypt-with-data-key-pair:
	echo -e "-----BEGIN PRIVATE KEY-----\n$$(aws --profile treezor-playground kms decrypt --ciphertext-blob fileb://<(jq -r '.PrivateKeyCiphertextBlob' datakeypair.json | base64 --decode) --output text --query Plaintext | fold -w60)\n-----END PRIVATE KEY-----" > datakeypair.private.decrypted
	openssl pkeyutl -decrypt -inkey datakeypair.private.decrypted -in source.txt.encrypted.datakeypair -out source.txt.decrypted.datakeypair

### ASSYMETRIC.KEY.ENCRYPT

assym-encrypt-in-kms:
	aws --profile treezor-playground kms encrypt --key-id $(ASSYMETRIC_KEY_ID) --encryption-algorithm RSAES_OAEP_SHA_256 --plaintext fileb://source.txt --query CiphertextBlob --output text | base64 --decode > source.txt.encrypted.assymetric

assym-decrypt-in-kms:
	aws --profile treezor-playground kms decrypt --key-id $(ASSYMETRIC_KEY_ID) --ciphertext-blob fileb://source.txt.encrypted.assymetric --encryption-algorithm RSAES_OAEP_SHA_256 --query Plaintext --output text | base64 --decode > source.txt.decrypted.assymetric

assym-show-encrypted-with-kms:
	echo "encrypted content : $$(cat source.txt.encrypted.assymetric)"
assym-show-decrypted-with-kms:
	echo "encrypted content : $$(cat source.txt.decrypted.assymetric)"

### ASSYMETRIC.KEY.SIGN

assym-sign-in-kms:
	aws --profile treezor-playground kms sign --key-id $(ASSYMETRIC_SIGN_KEY_ID) --message fileb://source.txt --message-type RAW --signing-algorithm RSASSA_PKCS1_V1_5_SHA_512 --query Signature --output text | base64 --decode > memory.txt.sign

assym-verify-in-kms:
	aws --profile treezor-playground kms verify --key-id $(ASSYMETRIC_SIGN_KEY_ID) --message fileb://source.txt --message-type RAW --signature fileb://memory.txt.sign --signing-algorithm RSASSA_PKCS1_V1_5_SHA_512

assym-get-public-key:
	aws --profile treezor-playground kms get-public-key --key-id $(ASSYMETRIC_SIGN_KEY_ID) --query PublicKey --output text | base64 --decode > publickey.verify.txt.der

assym-verify-with-public-key:
	openssl dgst -sha512 -verify publickey.sign.txt.der -keyform DER -signature memory.txt.sign source.txt
