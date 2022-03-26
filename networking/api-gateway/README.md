## Objectifs
- Créer une Websocket
- Créer une api gateway HTTP
- Créer une api gateway REST Public
- Créer une api gateway REST Private
- Attacher un custom domain name

## Resources
- https://docs.aws.amazon.com/apigateway/latest/developerguide/api-gateway-mapping-template-reference.html

## Apprentisage

### Websocket

La route peut être selectionner via une route selection expression qui vaut "$request.body.action" par example

Si pas de route vous pouvez implémenter la routeKye "$default" pour catcher les 404.
Vous avez des routeKey spécifique pour la connection/déconnection qui sont $connect/$disconnect, vous ne pouvez pas répondre dessus.

Le connectionId est dispo dans $context.connectionId et permet notament même en dehors de la réponse synchronome de renvoyer une réponse asynchronome depuis un backend non websocket.
Il s'agit d'une connection two ways.

#### Répondre depuis une intégration à une connection websocket en cours

AWS_PROFILE=treezor-playground awscurl --region eu-west-3 --service execute-api -X POST -d "hello world" https://jr1wemje2j.execute-api.eu-west-3.amazonaws.com/production/@connections/Pm4ogeKqCGYCGEw=