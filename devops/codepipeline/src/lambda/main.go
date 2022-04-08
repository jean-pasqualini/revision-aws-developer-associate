package main

import (
	"fmt"
	"github.com/aws/aws-lambda-go/lambda"
)

func main() {
	fmt.Println("Hello")
	lambda.Start(func() (string, error) {
		return "hello", nil
	})
}
