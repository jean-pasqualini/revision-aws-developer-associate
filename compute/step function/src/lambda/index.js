exports.lambda = async function (event, context) {
    console.log(JSON.stringify(event))
    console.log(JSON.stringify(context))

    if(event.case === "error_sleep") {
        await useCaseSleep(3 * 1000)
        useCaseError()
    }
    if(event.case === "error") {
        useCaseError()
    }
    if(event.case === "sleep") {
        await useCaseSleep(event.sleep * 1000)
    }

    return {"fromLambda": true, "version": "v3"}
}

function useCaseError() {
    throw new Error("an error")
}

function useCaseSleep(t) {
    return new Promise(resolve => setTimeout(resolve, t));
}