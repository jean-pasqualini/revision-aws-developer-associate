exports.lambda = async function (event, context) {
    console.log(JSON.stringify(event))
    console.log(JSON.stringify(context))
    throw new Error("an error")
}