exports.lambda = async function (event, context) {
    console.log(JSON.stringify(event))
    console.log(JSON.stringify(context))
    console.log('aaa')

    if(event.case === "retry") {
        await useCaseSleep(3 * 1000)
        useCaseError()
    }
    if(event.case === "concurency") {
        await useCaseSleep(20 * 1000)
    }

    if(event.case === "apitimeout") {
        await useCaseSleep(35 * 1000)
    }

    if (event.Records[0].body == "retry") {
        console.log(event.Records[0].body)
        useCaseError()
    }

    if (event.Records[0].Sns.Message == "retry") {
        console.log(event.Records[0].Sns.Message)
        useCaseError()
    }

    return {"fromLambda": true}
}

function useCaseError() {
    throw new Error("an error")
}

function useCaseSleep(t) {
    return new Promise(resolve => setTimeout(resolve, t));
}