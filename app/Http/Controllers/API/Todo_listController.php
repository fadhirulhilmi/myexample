<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Todo_list;
use Illuminate\Http\Request;
use App\Http\Resources\Todo_listResource;
use Validator;
use Illuminate\Support\Facades\Auth;

class Todo_listController extends BaseController
{
    public function index()
    {
        $todo_lists = Todo_list::all()->where('user_id', Auth::user()->id);

        // return $this->sendResponse($todo_lists, 'Todo lists retrieved successfully.');
        return $this->sendResponse(Todo_listResource::collection($todo_lists), 'Todo lists retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
     
        $validator = Validator::make($input, [
            'body' => 'required',
            'is_complete' => 'required'
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
     
        // $todolist = Todo_list::create($input);
        $data = [
            'body' => $request->body,
            'is_complete' => $request->is_complete,
            'user_id' => Auth::user()->id,
        ];

        $todolist = Todo_list::create($data);
     
        return $this->sendResponse(new Todo_listResource($todolist), 'Todo list created successfully.');
    } 
   
    public function show($id)
    {
        $todolist = Todo_list::where('user_id', Auth::user()->id)->find($id);
    
        if (is_null($todolist)) {
            return $this->sendError('Todo list not found.');
        }
     
        return $this->sendResponse(new Todo_listResource($todolist), 'Todo list retrieved successfully.');
    }
    
    public function update(Request $request, $id)
    {
        $todolist = Todo_list::where('user_id', Auth::user()->id)->find($id);

        if($todolist){

            $input = $request->all();
        
            $validator = Validator::make($input, [
                'body' => 'required',
                'is_complete' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
        
            $todolist->body = $input['body'];
            $todolist->is_complete = $input['is_complete'];
            $todolist->save();
        
            return $this->sendResponse(new Todo_listResource($todolist), 'Todo list updated successfully.');
        }
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
   
    public function destroy($id)
    {
        $todolist = Todo_list::where('user_id', Auth::user()->id)->find($id);

        if($todolist){
            $todolist->delete();
        
            return $this->sendResponse([], 'Todo list deleted successfully.');
        }
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
