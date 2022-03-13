### Objectifs
- Authentication via cognito pour obtenir un jeton permettant d'accèder à une liste d'item en dynamo

https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-authentication-flow.html
https://stackoverflow.com/questions/37941780/what-is-the-rest-or-cli-api-for-logging-in-to-amazon-cognito-user-pools
https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/Welcome.html
https://docs.aws.amazon.com/cognito/latest/developerguide/authorization-endpoint.html
https://aws.amazon.com/fr/blogs/mobile/understanding-amazon-cognito-user-pool-oauth-2-0-grants/
https://docs.aws.amazon.com/cognito/latest/developerguide/authentication-flow.html
https://blog.appsecco.com/exploiting-weak-configurations-in-amazon-cognito-in-aws-471ce761963


#https://jean95130.auth.eu-west-3.amazoncognito.com/login?client_id=1sukanjpfbq6j0hmg299ue6fdu&redirect_uri=https://www.example.com&response_type=token

aws --profile treezor-playground cognito-idp initiate-auth --client-id 1sukanjpfbq6j0hmg299ue6fdu --auth-flow USER_PASSWORD_AUTH --auth-parameters USERNAME=demo,PASSWORD=$PASSWORD