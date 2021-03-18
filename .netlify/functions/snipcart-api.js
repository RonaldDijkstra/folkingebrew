exports.handler = async function(event, context) {
    return {
        statusCode: 200,
        body: JSON.stringify({api: process.env.SNIPCART_API_KEY})
    };
}
