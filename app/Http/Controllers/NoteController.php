<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use Input;
use App\Note;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $validator = Validator::make(Input::all(), array(
            'title' => 'required|max:50',
            'note' => 'required|max:1000',
            'api_key' => 'required'
            ));

        if($validator->fails()){
            return response()->json(array('errors' => $validator->errors()));
        }

        $note = new Note();
        $note->user_id = $this->_getUserId(Input::get('api_key'));
        $note->title = Input::get('title');
        $note->note = Input::get('note');
        $saved = $note->save();

        if($saved){
            return response()->json(array('status' => 'successful'));
        } else {
            return response()->json(array('status' => 'failed'));
        }

    }

    public function get(){
        $validator = Validator::make(Input::all(), array(
            'id' => 'required|integer',
            'api_key' => 'required'
            ));
        if($validator->fails()){
            return response()->json(array('errors' => $validator->errors()));
        }
        $userId = $this->_getUserId(Input::get('api_key'));
        $note = Note::where('id', Input::get('id'))->where('user_id', $userId)->first();
        return response()->json(array('status' => 'successful', 'note' => $note));
    }

    public function edit(){
        $validator = Validator::make(Input::all(), array(
            'id' => 'required|integer',
            'title' => 'required|max:50',
            'note' => 'required|max:1000',
            'api_key' => 'required'
            ));
        if($validator->fails()){
            return response()->json(array('errors' => $validator->errors()));
        }
        $this->_isOwner();
        $note = Note::find(Input::get('id'));
        if($note){
            $note->title = Input::get('title');
            $note->note = Input::get('note');
            $saved = $note->save();
            if($saved){
                return response()->json(array('status' => 'successful'));
            }
        }
        return response()->json(array('status' => 'failed'));
    }

    /**
     * Remove the specified resource from storage.     
     * @return Response
     */
    public function delete(){
        $validator = Validator::make(Input::all(), array(
            'id'=> 'required|integer',
            'api_key' => 'required'
            ));
        if($validator->fails()){
            return response()->json(array('errors' => $validator->errors()));
        }
        $this->_isOwner();
        $note = Note::find(Input::get('id'));
        if($note){
            $deleted = $note->delete();
            if($deleted){
                return response()->json(array('status' => 'successful'));
            }
        }

        return repsonse()->json(array('status' => 'failed'));
    }

    public function getAll(){
        $validator = Validator::make(Input::all(), array(
            'api_key' => 'required'
            ));
        if($validator->fails()){
            return response()->json(array('errors' => $validator->errors()));
        }

        $userId = $this->_getUserId(Input::get('api_key'));
        if($userId){
            $notes = Note::where('user_id', $userId)->get();
            return response()->json(array('status' => 'successful', 'notes' => $notes));
        }
        return response()->json(array('status' => 'failed'));
    }

    private function _getUserId($apiKey){
        $user = User::where('api_key', $apiKey);

        if($user){
            $firstUser = $user->first();
            if($firstUser){
                $id = $firstUser->id;
                if($id){
                    return $id;
                }
            }
        }

        return response()->json(array('status' => 'failed'));
    }
    private function _isOwner(){
        $userId = $this->_getUserId(Input::get('api_key'));
        if(!$userId){
            response()->json(array('status' => 'failed'));
        }
        $isOwner = Note::where('id', Input::get('id'))->where('user_id', $userId)->count();
        if(!$isOwner){
            return response()->json(array('status' => 'failed'));
        }
        return true;
    }
}
