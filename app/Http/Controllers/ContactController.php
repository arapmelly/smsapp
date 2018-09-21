<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Contact;
use Excel;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $contacts = Contact::with('group')->get();

        return $contacts->map(function($contact){

            return [
                'id'=>$contact->id,
                'f_name'=>$contact->f_name,
                'l_name'=>$contact->l_name,
                'name'=>$contact->f_name.' '.$contact->l_name,
                'phone'=>$contact->phone,
                'group'=>$contact->group->name
            ];
        });


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
            'f_name'=>'required',
            'l_name'=>'required',
            'phone'=>'required',
            'group_id'=>'required'
        ]);


        $contact = new Contact;
        $contact->f_name = $request->f_name;
        $contact->l_name = $request->l_name;
        $contact->phone = $request->phone;
        $contact->group_id = $request->group_id;
        $contact->save();

        return $contact;
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

        return Contact::find($id);
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
            'f_name'=>'required',
            'l_name'=>'required',
            'phone'=>'required',
            'group_id'=>'required'
        ]);


        $contact = Contact::find($id);
        $contact->f_name = $request->f_name;
        $contact->l_name = $request->l_name;
        $contact->phone = $request->phone;
        $contact->group_id = $request->group_id;
        $contact->update();

        return $contact;
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

        return Contact::destroy($id);
    }


    public function import(Request $request){

        $group_id = $request->group_id;

        $data = $request->contacts;

     

        foreach ($data as $key => $d) {
           # code...
            
             if(!Contact::isExist($d['phone'])){

                 $contact = new Contact;
                 $contact->f_name = $d['fname'];
                 $contact->l_name = $d['lname'];
                 $contact->phone = Contact::formatPhone($d['phone']);
                 $contact->group_id = $group_id;
                 $contact->save();

            }

        }

            
        return ['status'=>true, 'data'=>$data];
    }
}
