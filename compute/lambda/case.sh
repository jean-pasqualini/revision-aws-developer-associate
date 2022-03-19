#!/bin/bash

function timeout() {
  aws --profile treezor-playground lambda update-function-configuration --function-name bye-lambda --timeout $1
}

function invoke() {
  aws --profile treezor-playground lambda invoke --qualifier prod --cli-binary-format raw-in-base64-out --payload "{ \"case\": \"$2\" }" --invocation-type $1 --log-type Tail --function-name bye-lambda /dev/stdout
}


case "$1" in
  timeout)
    timeout $2
    ;;
  invoke-request)
    invoke RequestResponse $2
    ;;
  invoke-event)
    invoke Event $2
    ;;
  *)
      echo "$0 is not a choice"
      exit 1
esac
