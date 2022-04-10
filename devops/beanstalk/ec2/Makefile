STACKNAME := "beanstalk-revision"

package:
	aws --profile treezor-playground cloudformation package --template-file template.yaml --output-template packaged.yaml --s3-bucket revision-935529178062-cloudformation

create-stack: package
	aws --profile treezor-playground cloudformation create-stack --stack-name $(STACKNAME) --template-body file://packaged.yaml --capabilities CAPABILITY_NAMED_IAM

update-stack: package # --disable-rollback
	aws --profile treezor-playground cloudformation update-stack --stack-name $(STACKNAME) --template-body file://packaged.yaml --capabilities CAPABILITY_NAMED_IAM CAPABILITY_AUTO_EXPAND
	aws --profile treezor-playground cloudformation wait stack-update-complete --stack-name $(STACKNAME)

delete-stack:
	aws --profile treezor-playground cloudformation delete-stack --stack-name $(STACKNAME)

create-bucket:
	aws --profile treezor-playground s3 mb s3://revision-935529178062-cloudformation

create-version:
	rm /tmp/source.zip || true
	zip /tmp/source.zip -r ./
	aws --profile treezor-playground s3 cp /tmp/source.zip s3://revision-935529178062-cloudformation/source.zip
	aws --profile treezor-playground elasticbeanstalk create-application-version --application-name LevelUpBeanstalk --version-label v1 --source-bundle S3Bucket="revision-935529178062-cloudformation",S3Key="source.zip"

eb-init:
	eb init --region "eu-west-3" --platform "PHP 8.0 running on 64bit Amazon Linux 2" --profile treezor-playground -- LevelUpBeanstalk

check-availability:
	aws --profile treezor-playground elasticbeanstalk check-dns-availability --cname-prefix levelup2107

cli-create-env:
	aws --profile treezor-playground elasticbeanstalk create-environment --environment-name levelup2107 --application-name LevelUpBeanstalk --cname-prefix levelup2107 --version-label v1 --solution-stack-name "64bit Amazon Linux 2 v3.3.12 running PHP 8.0"

#--service-role arn:aws:iam::935529178062:role/BeanstalkServiceRole
eb-create-env:
	eb create --cname levelup1811 --instance_profile revision-beanstalk-ip --database --instance-types t2.micro -- LoadBalancinhWebEC2Env