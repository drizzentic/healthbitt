var bitcoin = require('bitcoinjs-lib');
var request = require('request');
var http = require('http');

http.createServer(function (req, res) {

    switch(req.url) { 
    	 case '/healthbit':
    	 postToApi('issue', asset, function(err, body){
		    if (err) {
		        console.log('error: ', err);
		    }

		    //Sign Transaction
			var key="KzpWk9XvAYWVERbkQzxHKKLmy1escmfjAr1PxkZLbG1UJVtdcFdt";
			var txHex=body.txHex;

		    var signedTxHex = signTx(txHex, key);
		    var transaction = {
		    'txHex': signedTxHex
			}

			postToApi('broadcast', transaction, function(err, body){
			    if (err) {
			        console.log('error: ', err);
			    }

			});

			console.log("signedTxHex: ["+signedTxHex+"]");
			res.setHeader('Content-Type', 'application/json');
            res.end(JSON.stringify({ a: body }));
		});
     
    	 break;
    	case 'default':
    	 res.setHeader('Content-Type', 'application/json');
         res.end(JSON.stringify("[404] " + req.method + " to " + req.url));
    	
    };
}).listen(1337, '127.0.0.1');

console.log('Server running at http://127.0.0.1:1337/');

key = bitcoin.ECKey.makeRandom();
new_address = key.pub.getAddress(bitcoin.networks.testnet).toString();
function postToApi(api_endpoint, json_data, callback) {
    console.log(api_endpoint+': ', JSON.stringify(json_data));
    request.post({
        url: 'http://testnet.api.coloredcoins.org:80/v3/'+api_endpoint,
        headers: {'Content-Type': 'application/json'},
        form: json_data
    }, 
    function (error, response, body) {
        if (error) {
            return callback(error);
        }
        if (typeof body === 'string') {
            body = JSON.parse(body)
        }
        console.log('Status: ', response.statusCode);
        console.log('Body: ', JSON.stringify(body));
        return callback(null, body);
    });
};

var asset = {
    'issueAddress': 'mzhXGXuKvW4j34yJNszVRwkhd7XSFiG37h',
    'amount': 100,
    'divisibility': 0,
    'fee': 1000,
    'reissueable':false,
    'transfer': [{
    	'address': 'mzhXGXuKvW4j34yJNszVRwkhd7XSFiG37h',
    	'amount': 100
    }],
    'metadata': {
        'assetId': '1',
        'assetName': 'Drizzentic Test',
        'issuer': 'Asset Issuer',
        'description': 'My Description'
    }
};




function signTx (unsignedTx, wif) {
    var privateKey = bitcoin.ECKey.fromWIF(wif)
    var tx = bitcoin.Transaction.fromHex(unsignedTx)
    var insLength = tx.ins.length
    for (var i = 0; i < insLength; i++) {
        tx.sign(i, privateKey)
    }
    return tx.toHex()
}