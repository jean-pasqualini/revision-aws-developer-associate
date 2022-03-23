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

## Cloud init

https://docs.aws.amazon.com/fr_fr/AWSEC2/latest/UserGuide/amazon-linux-ami-basics.html#amazon-linux-cloud-init

### Format de données
- Gzip
- Fichier mime
- Décodage base64
- script de données utilisateur
- fichier d'inclusions
- donndée de configuration cloud
- tache de démarrage
- cloud boothook