<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use File;
use GuzzleHttp\Client;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PatientDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
       $patientData=$request->json()->all();

       //Obtain data from the json array posted
        
       $prescriptionData=$patientData['patient_history']['prescriptions'];
       $labData=$patientData['patient_history']['lab_test'];
       $imagingData=$patientData['patient_history']['imaging'];
       $diagnosesData=$patientData['patient_history']['diagnosis'];
       $observationData=$patientData['patient_history']['observation'];
       $organizationData=$patientData['patient_metadata']['organization_code'];
       $telecomData=$patientData['patient_metadata']['telecom'];


       $healthData=array(
            'patient_history'=>array(
                'prescriptions'=>$prescriptionData,
                'lab_test'=>$labData,
                'imaging'=>$imagingData,
                'diagnoses'=>$diagnosesData,
                ),
            'patient_metadata'=>array(
                'organization_id'=>$organizationData
                )
            );
    /**
     * Invoke command to send the health reference id to the patient.
     * @var [type]
     */
    
       $reference=md5(base64_encode(json_encode($healthData)));

       File::put($reference,json_encode($healthData));
        $process = new Process('curl -F "file=@'.$reference.'" http://127.0.0.1:5001/api/v0/add?encoding=json');
        $process->run();
       //File::delete($reference);
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);

        }

        $response=$process->getOutput();
        
        //Obtain the hash from the command output
        
        //echo substr($response, 59,-3) .$response;
        $ipfs_hash=substr($response, 59,-3);
        //return $ipfs_hash;
        $client = new Client(['base_uri' => 'http://127.0.0.1:1337']);       
        $response = $client->request('POST', '/healthbit', ['query' => ['organization_id' => 'Mater','data_reference'=>$ipfs_hash]]);
       
        return $response->getBody();
   }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Retrieve data stored by ipfs
        $process = new Process('curl -F "file=@'.$reference.'" http://127.0.0.1:5001/api/v0/add?encoding=json');
        $process->run();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}