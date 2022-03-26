## Note
- https://learn.hashicorp.com/tutorials/terraform/lambda-api-gateway
- https://learn.hashicorp.com/tutorials/terraform/blue-green-canary-tests-deployments?in=terraform/aws

## Objectif
- Est-ce que le timeout inclu le temps de coldstart ???? non
- Comprendre les mécanisme de retry
  - Dans le cadre d'un appel direct
  - Dans le cadre d'un event dynamodb
  - Dans le cadre d'un event kinesis
  - Dans le cadre d'un event sqs
  - Dans le cadre d'un event sns
  - Dans le cadre d'un event bridge 
  - Quand est t-il des retry sur les destination ?
- Comprendre les mécaniques de DLQ
- Faire un invoke en mode request/response
- Faire un invoke en mode async
- Connecter au vpc et joindre une machine ec2
- Faire fonctionner le montage nfs
- Regarder du coté de la signature de code
- Regarder du coté des préférences de déployment
- Regarder pour décrypter les variables d'env
- Faire un process qui créer une concurency de 20
  - il faut créer une version et un alias car la version $LATEST n'est pas un target valable
  - Créer un process qui dure 20 secondes
  - Poper un process toute les 1 secondes
  - Le concurency devrai monté à 20 puis redescendre
  - Resultat
    - Dans le cadre reserved 1 / provisioned 1 
      - Si async: chaque lambda attend son tour
      - Si pas async: les autres sont throttle
# https://www.rajeshbhojwani.co.in/2020/01/aws-lambda-destinations-vs-step.html
- Configurer une destination (rien à voir avec les DLQ, il est possible de conditionné l'echec ou la réussite)
  - La DLQ recoit l'input
  - La destination recoit l'output

## Apprentissage

#### VPC & EFS

Il est néscésssaire avec un moint point par vpc/subnet pour monter un efs existant sur lambda ou ec2.
Les lambdas ont besoin de permission pour monter/lire/écrire sur un efs.

Il est possible d'utiliser le type de systeme efs ou nfs pour monter un efs.

Il est possible d'utiliser l'accès point pour compartimenter les accèss.

#### Event Mapping

L'event mapping est une ressource et il utilise les permissions de la lambda pour intéragir avec sqs, sns, ...

#### Abort

Dans le cadre d'un appel de type request/response de la part d'un api gateway ou aws sdk/cli
Une HTTP 500 est retourné au bout du timeout de l'intégration mais la lambda elle continue de s'éxécuter.

Seul le timeout de la lambda décide de s'il faut abord ou non l'exécution. vous êtes donc facturez pendant ce temps.
Le throttle ou le publish de nouvelle version va seulement impacter les futures invocations.

#### Concurrency

A noter la reserve concurency est globale, la provided concurency est lié à l'alias.
Donc veillez à avoir suffisament de reserved concurency car imaginons si vous avez une réserve concurency de 1
et une provisione concurency de 1 sur l'alias prod et que vous voulez appelez la version $LATEST vous ne pourez pas.

Quand la concurency est atteninte, si vous appelez la lambda en mode RequestReponse vous aurez un HTTP 429 Too Many Request
Cependant si vous l'appelez en mode Event, votre event sera mis en attente 

Prenons le premier cas,
Vous n'utiliez pas l'aws sdk ou cli donc pas de retry client.
Vous faite un appel RequestResponse donc votre appel n'est pas mis en quue
Vous avez une réserve de 1.
Vous avez une provision de 1.
Votre lambda met 20 secondes à s'éxécuter.
Vous faire 1 appels par secondes.

Dans ce cas, vous aurez 1 appel OK avec du coldstart et 19 appel KO. 

Prenons le second cas.
Vous n'utiliez pas l'aws sdk ou cli donc pas de retry client.
Vous faite un appel RequestResponse donc votre appel n'est pas mis en quue
Vous avez une réserve de 20.
Vous avez une provision de 10.
Votre lambda met 20 secondes à s'éxécuter.
Vous faire 1 appels par secondes.

Dans ce cas vous aurez 10 appels OK avec du coldstart et 10 appels KO.

### Dead Letter Queue

La dead letter queue est utilisé une fois tout les retry épuisé et envoie l'input de la réponse.
Elle ne peut s'appliquer qu'au fails

### Destination

La destination est utilisé une fois tous les retry épuise et envoie l'output de la réponse. 
Elle peut s'appliquer au succèss ou fail selon votre choix.

### Retry mechanism

#### Est considérer comme un echec
- Un timeout de la part de la lambda
- ...

#### Time distribution

AWS use the expodential backoff pour déterminer le temps avant de retry
Le délais est de 1mn puis 2mn (on à donc un facteur multiplicatif de 2 et un délai de départ de 1mn)
Si vous avez une lambda qui prend 30 seconds à s'éxécuter avec le try + les 2 retries qui échoue la timeline sera la suivante. (format minutes:seconds)

- 1er) 00:00 -> 00:30
- 2eme) 01:30 -> 02:00
- 3eme) 04:00 -> 04:30

#### Documentations
- https://docs.aws.amazon.com/lambda/latest/dg/invocation-retries.html
- https://docs.aws.amazon.com/lambda/latest/dg/invocation-retries.html
- https://epsagon.com/observability/how-to-handle-aws-lambda-errors-like-a-pro/#:~:text=Lambda%20functions%20can%20fail%20in,seconds'%20message.
- https://enrico-portolan.medium.com/how-aws-lambda-retry-really-works-fb823e78b4a1

#### Async
L'event mapping est une ressource

- L'invoke d'une fonction en mode event utilise bien la stratégie de retry de la lambda elle même
- SQS
  - En mode standard / En mode FIFO
    - C'est l'event mapping qui appelle la lambda et c'est un appel de type request, le message n'est delete que si la lambda success
    - C'est la visibility timeout de SQS qui détermine le retry
    - C'est la dead letter queuue de SQS qui détermine le nombre maximum de receive et la destination après
    - Si pas de dead letter queue ce sera le max retention period qui supprimera le message
- SNS/EventBridge
  - La politique de retry complexe de SNS ne rentre pas en ligne de compte sur du lambda car c'est une invocation de type event
  - C'est donc la politique de retry de la lambda qui s'applique
- Dyanmo stream / Kinesis Stream
  - Le retry est expodentiel et le nombre de retry est défini l'ors de la définition de l'event source mapping, par défaut il est sur infini
- Dans l'invocation d'une lambda en tant que destination
  - Cette ci est invoké de manière async c'est comme la lambda de destination qui décide de son retry

#### Sync
Dans le cadre d'un appel sync le retry n'est pas fait automatiquement par le service lambda.
Seul le client peut décider de retry ou non. L'api gateway par example ne fait pas de retry.
Mais l'appel d'une lambda via aws sdk/cli va retry dans certaines conditions (client timeouts, throttling and services errors)
