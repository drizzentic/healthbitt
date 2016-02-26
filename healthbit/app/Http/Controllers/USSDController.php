<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\USSD;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class USSDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $text=$request->text;
        $phonenumber=$request->phoneNumber;

        $level = explode("*", $text);

        if ( $text == "" ) {
            $response="CON Welcome to the registration portal.\nPlease enter you full name";
        }

        if(isset($level[0]) && $level[0]!="" && !isset($level[1])){

          $response="CON Hi ".$level[0].", enter your ward name";
             
        }
        else if(isset($level[1]) && $level[1]!="" && !isset($level[2])){
                $response="CON Please enter you national ID number\n"; 

        }
        else if(isset($level[2]) && $level[2]!="" && !isset($level[3])){
            //Save data to database
            $data=array(
                'phonenumber'=>$phonenumber,
                'fullname' =>$level[0],
                'electoral_ward' => $level[1],
                'national_id'=>$level[2]
                );

            USSD::create($data);

            $response="END Thank you ".$level[0]." for registering.\nWe will keep you updated"; 
    }

        header('Content-type: text/plain');
        echo $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
