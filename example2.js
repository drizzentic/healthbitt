var bitcoin = require('bitcoinjs-lib');
var request = require('request');
var http = require('http');
var express = require('express');
var app = express();
var organization_id="";
app.get('/', function (req, res) {
  res.send('Hello World!');
});

app.post('/healthbit',function(req,res){
var organization_id=req.param('organization_id');
var data_reference=req.param('data_reference');
var asset = {
    'issueAddress': 'mzhXGXuKvW4j34yJNszVRwkhd7XSFiG37h',
    'amount': 100,
    'divisibility': 0,
    'fee': 10000,
    'reissueable':false,
    'transfer': [{
    	'address': 'mzhXGXuKvW4j34yJNszVRwkhd7XSFiG37h',
    	'amount': 100
    }],
    'metadata': {
        'assetName': organization_id,
        'issuer': organization_id,
        'description':data_reference 
    }
};

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
			//console.log("signedTxHex: ["+signedTxHex+"]");
			
			});

        res.setHeader('Content-Type', 'application/json');
        res.end(JSON.stringify({ response: body }));
		});

});

app.listen(1337, function () {
  console.log('app listening on port 1337!');
});


//key = bitcoin.ECKey.makeRandom();
//new_address = key.pub.getAddress(bitcoin.networks.testnet).toString();
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
        console.log('Status for : ', response.statusCode);
        console.log('Body: ', JSON.stringify(body));
        return callback(null, body);
    });
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