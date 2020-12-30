<?php

namespace App\Http\Controllers;

use App\Http\Contract\ContactsContract;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ContactsController extends Controller
{

    public function getContactList(ContactsContract $contactsContract,$userId){
        if(Auth::user()->id != $userId){
            return Redirect::to("login")->withErrors(['authError' => 'Opps! You do not have access. Please login']);
        }
        $data = $contactsContract->getContactList($userId);
        return view('contact',compact('data'));
    }

    public function seachContactList(ContactsContract $contactsContract,Request $request){

        $searchData = $request->all();
        $data = $contactsContract->searchContactList($searchData);
        return view('contact',compact('data','searchData'));
    }

    public function addUserFriend(Request $request, ContactsContract $contactsContract){
        return $contactsContract->addUserFriend($request->all());

    }
}
