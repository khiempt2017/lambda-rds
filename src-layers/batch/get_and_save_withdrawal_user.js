const { LambdaClient, InvokeCommand, InvocationType } = require("@aws-sdk/client-lambda");
const region = process.env.REGION;
const client = new LambdaClient({ region: region });
async function handler() {
    try {
        // Validate input
        const withdrawalMonth = process.env.withdrawalMonth;
        const environment = process.env.ENVIRONMENT;

        let functionName = "BP-api-serverless-stage-set-withdrawal-user";
        functionName = functionName.replace("stage", environment);

        // Config options request
        const generateRequestId = (length = 20) => 
            Array.from({ length }, () => 
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
                .charAt(Math.floor(Math.random() * 62))
            ).join('');
        const requestId = generateRequestId();
        console.log("requestId: ", requestId);
        let dataReq = {
            requestContext: {
                requestId: requestId  
            },
            body: withdrawalMonth ? JSON.stringify({withdrawalMonth}) : null, 
        }
        const params = {
            FunctionName: functionName, 
            InvocationType: InvocationType.Event, 
            Payload: JSON.stringify(dataReq),
        };

        const command = new InvokeCommand(params);
        const response = await client.send(command);
        console.log("response from lambda:", response);
    }
    catch(error){
        // Error internal server
        console.log(error);
        return {
            statusCode: 500,
            message: error
        };
    }
};

// Call handler
(async () => {
    const result = await handler();
    console.log("result: ", result);
})();

