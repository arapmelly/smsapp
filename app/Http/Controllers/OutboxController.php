<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;

use App\Outbox;
use App\Contact;

use AfricasTalking\SDK\AfricasTalking;

class OutboxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return Outbox::all();
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
            'send_date'=>'required'
        ]);

       $datetime = explode(' ', $request->send_date);

       $contacts = Contact::all();

        foreach($contacts as $contact){

            $outbox = new Outbox;
            $outbox->contact = $contact->phone;
            $outbox->text = $request->text;
            $outbox->send_date = $datetime[0];
            $outbox->send_time = $datetime[1];
            $outbox->save();

        }
        

        return $outbox;
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

        return Outbox::find($id);
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
            'send_time'=>'required'
        ]);


        $outbox = Outbox::find($id);
        $outbox->text = $request->text;
        $outbox->send_date = $request->send_date;
        $outbox->send_time = $request->send_time;
        $outbox->update();

        return $outbox;


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
        return Outbox::destroy($id);
    }


    public function single(Request $request)
    {
        //



        $request->validate([
            'text'=>'required',
            'contact'=>'required'
        ]);
        
        $now = Carbon::now();

        $datetime = explode(' ', $now);

        $outbox = new Outbox;
        $outbox->contact = $request->contact;
        $outbox->text = $request->text;
        $outbox->send_date = $datetime[0];
        $outbox->send_time = $datetime[1];
        $outbox->save();


        $username = 'kemstwo';
        $apiKey     = '1f838716c74f469ebcb5a9ac1c9c1de28dd82a12cd9b4251a0c14d8efa2c4e0f'; 
        
        $AT = new AfricasTalking($username, $apiKey);

        $SMS = $AT->sms();

        $options = [
            'message'=>$request->text,
            'to'=>$request->contact,
            'enqueue'=>false
        ];

        $response = $SMS->send($options);

       
        if($response['status'] == 'success'){

            $ob = Outbox::find($outbox->id);
            $ob->is_send = true;
            $ob->update();

            return ['status'=>true, 'data'=>$response];

        } else {

             return ['status'=>false, 'data'=>$response];
        }
        
    }


    public function sendBulk(){

        //get all sms scheduled to be sent at this minute and this date
        $now = Carbon::now();
        $datetime = explode(' ', $now);

        $date = $datetime[0];
        $time = $datetime[1];

        //get all outboxes for date and time
        $outboxes = Outbox::where('send_date', '=', $date)->get();

        Log::info($time);

        dd($outboxes);

        
    }
}
