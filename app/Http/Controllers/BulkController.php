<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Bulk;

use AfricasTalking\SDK\AfricasTalking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Contact;

class BulkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Bulk::with('group')->get();
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
        //
        $request->validate([
            'text'=>'required',
            'send_date'=>'required',
            'group_id'=>'required'

        ]);

        $datetime = explode(' ', $request->send_date);

        $bulk = new Bulk;
        $bulk->text = $request->text;
        $bulk->send_date = $datetime[0];
        $bulk->send_time = $datetime[1];
        $bulk->group_id = $request->group_id;
        $bulk->save();

        return ['status'=>true, 'data'=>$bulk];
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
        return Bulk::find($id);
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

        $request->validate([
            'text'=>'required',
            'send_date'=>'required',
            'group_id'=>'required'

        ]);

        

        $bulk = Bulk::find($id);
        $bulk->text = $request->text;
        $bulk->send_date = $request->send_date;
        $bulk->send_time = $request->send_time;
        $bulk->group_id = $request->group_id;
        $bulk->update();

        return ['status'=>true, 'data'=>$bulk];
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
        return Bulk::destroy($id);
    }



    public function sendSingle(Request $request)
    {
        //



        $request->validate([
            'text'=>'required',
            'contact'=>'required'
        ]);
        
        $now = Carbon::now();

        $datetime = explode(' ', $now);

        $contact = Contact::formatPhone($request->contact);

        $bulk = new Bulk;
        $bulk->contact = $contact;
        $bulk->text = $request->text;
        $bulk->send_date = $datetime[0];
        $bulk->send_time = $datetime[1];
        $bulk->save();


        $username = 'kemstwo';
        $apiKey     = '1f838716c74f469ebcb5a9ac1c9c1de28dd82a12cd9b4251a0c14d8efa2c4e0f'; 
        
        $AT = new AfricasTalking($username, $apiKey);

        $SMS = $AT->sms();

        $options = [
            'message'=>$request->text,
            'to'=>$contact,
            'enqueue'=>false
        ];

        $response = $SMS->send($options);

       
        if($response['status'] == 'success'){

            $ob = Bulk::find($bulk->id);
            $ob->is_send = true;
            $ob->update();

            return ['status'=>true, 'data'=>$response];

        } else {

             return ['status'=>false, 'data'=>$response];
        }
        
    }



    public function sendBulk(){

        // get the current date and time
        $datetime = Carbon::now();
        $datetime = explode(' ', $datetime);

        $date = $datetime[0];
        $time = $datetime[1];

        //set seconds to 00
        $time = Bulk::setTime($time);

        //query all sms set to be posted based on date and time.

        $outbounds = DB::table('bulks')->where('send_date', '=', $date)->where('send_time', '=', $time)->where('is_send', '=', false)->get();

        foreach ($outbounds as $key => $outbound) {
            # code...
           
            $contacts = Bulk::getContacts($outbound->group_id);
           
            $sendbulk = Bulk::sendBulk($outbound->id, $contacts, $outbound->text);
            
        }

        

        return ['status'=>true];
    }



}
