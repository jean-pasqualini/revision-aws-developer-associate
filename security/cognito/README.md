### Objectifs
- Assumer un role via facebook sans passé par cognito
- Assumer un role via cognito en mode non authentifié
- Assumer un rôle via cognito en mode authentifié
   - Obtenir l'id token via la ligne de commande
   - Obtenir l'id token via l'url de l'ui (redirect_uri + response_type=token, client_id)
- Authentication via cognito pour obtenir un jeton permettant d'accèder à une liste d'item en dynamo

### Resources
https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-authentication-flow.html
https://stackoverflow.com/questions/37941780/what-is-the-rest-or-cli-api-for-logging-in-to-amazon-cognito-user-pools
https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/Welcome.html
https://docs.aws.amazon.com/cognito/latest/developerguide/authorization-endpoint.html
https://aws.amazon.com/fr/blogs/mobile/understanding-amazon-cognito-user-pool-oauth-2-0-grants/
https://docs.aws.amazon.com/cognito/latest/developerguide/authentication-flow.html
https://blog.appsecco.com/exploiting-weak-configurations-in-amazon-cognito-in-aws-471ce761963
https://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles_providers_oidc_resources.html
https://bobbyhadz.com/blog/aws-cdk-iam-principal
https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_policies_elements_principal.html
https://www.facebook.com/v13.0/dialog/oauth?client_id=1417574295340505&redirect_uri=https://www.domain.com/login&state={st=state123abc,ds=123456789}
https://www.domain.com/login?#access_token=EAAUJRrxRBdkBACg5iNegTfneD0sIAHOfHCS6qWgEVDVkZA4UZAOmTNYy0b4Fvxew9PX4S6ZC0M0ijbfdhWKYxp5nZC6KZAYhQJZCZAgZCHcmG7kqTHaU70POHfJinQFZAtyuETtNwZCf2IqZCQgydd1iqhd30ka4VFjpzhdPT2OgBXuzyp2JmEHbMYMeryiVQGY0y74moUBzDnnw1ePvTDG9qvLf7zd2gDw118ZD&data_access_expiration_time=1654975693&expires_in=5507&long_lived_token=EAAUJRrxRBdkBAEIwOmcIlM00gZADzLFfBc7Bh4ZBMwXnouZB8uWC4PCKClbcfmuFMSK2LWeyOKyqEe69cgErooFqfKL9YJ5ZBTQqoZC2PUylgynd45leZCPuSJ2xIiKd0m6fV130CzExvTUlIUdFIgTnNzv8PgYOa8qu8KyZA3LdmFYaRinFZAxZBX3a0NJoxEWKxrPuiBVJfXQZDZD&state=%7Bst%3Dstate123abc%2Cds%3D123456789%7D
https://jean95130.auth.eu-west-3.amazoncognito.com/login?client_id=1sukanjpfbq6j0hmg299ue6fdu&redirect_uri=https://www.example.com&response_type=token
