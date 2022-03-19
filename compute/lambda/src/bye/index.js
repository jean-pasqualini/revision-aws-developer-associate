exports.lambda = async function (event, context) {
    console.log(JSON.stringify(event))
    console.log(JSON.stringify(context))

    if(event.case === "retry") {
        await useCaseSleep(3 * 1000)
        useCaseError()
    }
    if(event.case === "concurency") {
        await useCaseSleep(20 * 1000)
    }

    return {"fromLambda": true}
}

function useCaseError() {
    throw new Error("an error")
}

function useCaseSleep(t) {
    return new Promise(resolve => setTimeout(resolve, t));
}