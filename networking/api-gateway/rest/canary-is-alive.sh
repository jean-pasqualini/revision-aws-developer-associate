aws apigateway update-stage                               \
    			--rest-api-id iac5c6x5se                           \
    			--stage-name production                          \
    			--patch-operations '[{                                \
    				"op": "replace",                                  \
    				"value": "0.0"                                    \
    				"path": "/canarySettings/percentTraffic",         \
    			  }, {                                                \
    				"op": "copy",                                     \
    				"from": "/canarySettings/stageVariableOverrides", \
    				"path": "/variables",                             \
    			  }, {                                                \
    				"op": "copy",                                     \
    				"from": "/canarySettings/deploymentId",           \
    				"path": "/deploymentId"                           \
    			  }]'