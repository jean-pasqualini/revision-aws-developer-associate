## InputTest

### StateInput
```json
{
  "input_payload": {
    "character": "mario"
  }
}
```

### InputPath | $.input_payload
```json
{
  "character": "mario"
}
```

### Parameters | {"param": {"character.$": "$.character"}}
```json
{
  "params": {
    "character": "mario"
  }
}
```

### StateOutput
```json
{
  "params": {
    "character": "mario"
  }
}
```

## OutputTest
- https://docs.aws.amazon.com/step-functions/latest/dg/input-output-inputpath-params.html#input-output-resultselector

### InitialInput

```json
{
  "params": {
    "character": "mario"
  }
}
```

### InitialOutput

```json
{
  "result_payload": {
    "success": "true"
  }
}
```

### ResultSelector | {"result_selector.$":"$.result_payload"}

```json
{
  "result_selector": {
    "success": "true"
  }
}
```

### ResultPath | $.result_path

```json
{
  "params": {
    "character": "mario"
  },
  "result_path": {
    "result_selector": {
      "success": "true"
    }
  }
}
```

### OutputPath | $.result_path

```json
{
  "result_selector": {
    "success": "true"
  }
}
```