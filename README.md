# Healthbitt
A Blockchain based ehealth interoperability platform. The platform provides users with the capability to use bitcoin Blockchain (testnet) 
to store patient files references. The references are used to verify an audit of the records which are posted on the DFS(Distributed file system).

# Getting Started
Healthbitt has been built using several components which makes it achieve the objectives of creating a secure, anonymized and distributed ecosystem
for storing and retrieving patient history.
## Technology Stack

1. Coloured coin API.
 
Coloured coin api provided a simple API which helped in signing and propagating transactions into the bitcoin testnet. The transactions
include metadata that represents the patient data.

2. Laravel framework.

Laravel framework provides a middleware that interconnects other platforms to the blockchain and DFS.

3. IPFS.

 IPFS provides a distributed filesystem where all the respective patient's medical files are stored anonymously

## Setup

 To Setup the Healthbitt ecosystem you need to do the following:
 
 
 1. *setup nodejs. Find instructions here https://nodejs.org*

 2. *npm install --save bitcoinjs-lib@1.5.8* to install the bitcoin js dependency.



### Getting a testnet address and private key.
To obtain a testnet address and private key run the following command:

**node address.js**


To post a transaction on the bitcoin blockchain, the following steps must be followed.
 
 1. Create a raw transaction with inputs and outputs.

```javascript 
{
    //Put your testnet address
    'issueAddress': '',
    'amount': 1,
    'divisibility': 0,
    'fee': 10000,
    'reissueable':false,
    'transfer': [{
        //Put your testnet destination address
    	'address': '',
    	'amount': 1
    }],
    'metadata': {
        'assetName': organization_id,
        'issuer': organization_id,
        'description':data_reference 
    }
```

 2. Sign the raw transaction.

```javascript 
var privateKey = bitcoin.ECKey.fromWIF(wif)
    var tx = bitcoin.Transaction.fromHex(unsignedTx)
    var insLength = tx.ins.length
    for (var i = 0; i < insLength; i++) {
        tx.sign(i, privateKey)
    }
    return tx.toHex();
```

 3. Broadcast the signed transaction.

 ```javascript
 var transaction = {
		    'txHex': signedTxHex
			}

			postToApi('broadcast', transaction, function(err, body){
			    if (err) {
			        console.log('error: ', err);
			    }
			
			});
```

### Starting the services.

The three services must be started for any transaction to be successful

  1. Confirm IPFS is started by running this command on your terminal.


```
sudo ipfs daemon
```
  
  2. Start the nodejs bitcoin application server.
```javascript 
node app.js
```

  3. Laravel application server is started.
  Make sure the application server is available and the API endpoints are reachable.


### Creating a medical file
To post a medical file to the bitcoin and ipfs network, the following calls need to be made to the API with respective data. The data used here is just a 
sample of a medical record.

```
curl -X POST -H "Content-Type: application/json" -H "Cache-Control: no-cache" -H "Postman-Token: 42ad15a5-e1c5-ecd1-1f8a-f4f9b027523e" -d '{
  "patient_history": {
    "prescriptions": [
      {
        "name": "Paracetamol 20 grams"
      },{"name":"Artheremr lumefatrin"},{"name":"Piriton"},{"name":"Asprin"}
    ],
    "lab_test": [
      {
        "name": "stool test",
        "results": {
          "findings": "Giardia lamblia"
        }
      }
    ],
    "imaging": [
      {
        "type": "Leg xray",
        "image_location": "http://previews.123rf.com/images/thailoei92/thailoei921504/thailoei92150400096/39098986-Detail-of-an-x-ray-of-child-leg-Stock-Photo.jpg"
      }
    ],
    "observation": {
      "temperature": "37",
      "height": "163",
      "weight": "70",
      "respiratory_rate": "90",
      "systolic_bp": "10",
      "diastolic_bp": 23232,
      "pulse_rate": 88
    },
    "diagnosis": [
      {
        "presenting_complaints": "headache, diarrhea, fever, nausea and morning sleepiness",
        "examinations_findings": "sign of malaria very acute. might develop high fever and needs to be dewormed",
        "history_of_presenting_complaints": "started one week before visit to tanzania",
        "diagnosis_results": [
          {
            "code": "AFDF34"
          },{"code": "MATAFS23"},{"code": "Acute malaria trombositica"}
        ],
        "plan_of_management": "This is a test and not real medical data"
      }
    ]
  },
  "patient_metadata": {
    "organization_code": 432,
    "telecom": "071xxxxxx"
  }
}' "http://hostname:port/api/store_patient_data"
```

### Retrieving saved files from the network

To retrieve the files the user can access any ipfs node in the world and run the following command.

```
curl -X GET -H "Cache-Control: no-cache" -H "Postman-Token: 7a4a75f1-6c5a-ff73-8308-2ad550c91285" "http://hostname:port/api/patientdata/%7Bfile_reference%7D"

```

# Conclusion
This is an experimental project and should not be used to handle sensitive data. More improvements are being undertaken to make it production ready.

# References
ipfs.io
Coloured coin sdk