<!--
https://docs.aws.amazon.com/fr_fr/AWSEC2/latest/UserGuide/amazon-linux-ami-basics.html#amazon-linux-cloud-init
cat /etc/image-id
cat /etc/system-release
cat /etc/os-release
-->

## Outils inclus
- aws-amitools-ec2
- aws-apitools-as
- aws-apitools-cfn
- aws-apitools-elb
- aws-apitools-mon
- aws-cfn-bootstrap
- aws-cli

Les outils installé sont dispo dans /opt/aws

- cfn-elect-cmd-leader
- cfn-get-metadata
  -  /opt/aws/bin/cfn-get-metadata --region eu-west-3 --stack=ec2-revision --resource=Instance
- cfn-hup
  - /opt/aws/bin/cfn-hup --no-daemon
- cfn-init
  - https://docs.aws.amazon.com/fr_fr/AWSCloudFormation/latest/UserGuide/deploying.applications.html
  - https://s3.amazonaws.com/cloudformation-examples/BoostrappingApplicationsWithAWSCloudFormation.pdf
  - https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/aws-resource-init.html
  - sudo /opt/aws/bin/cfn-init --region=eu-west-3 --stack=ec2-revision --resource=Instance --configsets="nameconfigset"
  - Dans les metadata vous retrouverez
    - AWS::CloudFormation::Init
      - configSets
        - nameconfigset: ["step1", "step2"]
      - step1 # orders respected
        - packages: ...
        - users: ...
        - groups: ...
        - sources: ...
        - files: ...
        - commands: ...
        - services: ...
      - ...
- cfn-send-cmd-event
- cfn-send-cmd-result
- cfn-signal
  - /opt/aws/bin/cfn-signal --region=eu-west-3 --stack=ec2-revision --resource=Instance
- ec2-metadata
  -  /opt/aws/bin/cfn-get-metadata --region=eu-west-3 --stack=ec2-revision --resource=Instance
- eic_curl_authorized_keys
- eic_harvest_hostkeys
- eic_parse_authorized_keys
- eic_run_authorized_keys

## Dossiers
/var/lib/cloud
/etc/cloud

## Cloud init
https://cloudinit.readthedocs.io/en/latest/topics/boot.html
https://aws.amazon.com/fr/premiumsupport/knowledge-center/execute-user-data-ec2/
https://aws.amazon.com/fr/premiumsupport/knowledge-center/execute-user-data-ec2/
https://cloudinit.readthedocs.io/en/latest/topics/boot.html
https://docs.aws.amazon.com/fr_fr/AWSEC2/latest/UserGuide/amazon-linux-ami-basics.html#amazon-linux-cloud-init
https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/user-data.html#user-data-cloud-init
https://docs.aws.amazon.com/fr_fr/AWSEC2/latest/UserGuide/user-data.html
https://drewlearns.com/learn-cloudformation-with-drew/#cloudformation-init-and-ec2-user-data

### Format de données
- Gzip
- Fichier mime
- Décodage base64
- script de données utilisateur
- fichier d'inclusions
- donndée de configuration cloud
- tache de démarrage
- cloud boothook