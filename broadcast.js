var request = require('request')

function postApi(api_endpoint, json_data, callback) {
    console.log(api_endpoint+': ', JSON.stringify(json_data))
    request.post({
        url: 'http://testnet.api.coloredcoins.org:80/v3/'+api_endpoint,
        headers: {'Content-Type': 'application/json'},
        form: json_data
    },
    function (error, response, body) {
        if (error) {
            return callback(error)
        }
        if (typeof body === 'string') {
            body = JSON.parse(body)
        }
        console.log('Status: ', response.statusCode)
        console.log('Body: ', JSON.stringify(body))
        return callback(null, body)
    })
}

var signedTxHex = '0100000001be45f4195591b5d9684f149faa6704b54dd51848c2533b5343c93577618b2fa7000000006b48304502210091d94cdf2b800e56c127f7af6d246cc1341a7d0d63acb67e6a5dd9414dbe7a8502205f80cf6311e390487f8ea7fca1726b0445d50de3a32f0260e361c7cebc6ec1bc012103590f345f8e5390d0475a0e338e64f2be6b11d5ae7b1cd7a9725eb8bcb5087439ffffffff04ac0200000000000047512103ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff2103d710a2b49e53c8120aed7769940dabbb3a68d44498426eba507225077abad35652ae58020000000000001976a914d26a131b9d655cf95cc7a0cce1db49cf92ec965f88ac00000000000000001e6a1c43430102a313c73ed99e1796da8f52a97953f299e111dac50101011044470900000000001976a914d26a131b9d655cf95cc7a0cce1db49cf92ec965f88ac00000000'

// sign tx param
var data_params = {
    'txHex': signedTxHex
}

// broadcast transaction
postApi('broadcast',data_params,function(err, body){

    if (err) console.log('error: ', err)
})