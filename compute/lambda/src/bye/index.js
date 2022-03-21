const fs = require('fs')

exports.lambda = async function (event, context) {
    console.log(JSON.stringify(event))
    console.log(JSON.stringify(context))
    console.log('aaa')

    fs.writeFileSync("/mnt/shared/myefs/lol.txt", "je suis la")

    const cont = fs.readFileSync("/mnt/shared/myefs/lol.txt")

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

    if (typeof event.Records != "undefined") {
        if (event.Records[0].body == "retry") {
            console.log(event.Records[0].body)
            useCaseError()
        } else if (event.Records[0].Sns.Message == "retry") {
            console.log(event.Records[0].Sns.Message)
            useCaseError()
        }
    }

    return {"fromLambda": true, "contentefs": cont}
}

function useCaseError() {
    throw new Error("an error")
}

function useCaseSleep(t) {
    return new Promise(resolve => setTimeout(resolve, t));
}