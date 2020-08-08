<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentsController extends Controller
{
    protected function validator(array $data)
    {
        $rules = [
            'user_id' => ['required', 'integer' , 'exists:App\User,id'],
            'content' => ['required', 'string', 'min:20', 'max:255'],
        ];
        if(isset($data['parent']))
            $rules['parent'] = ['integer', 'gte:0'];
        return Validator::make($data, $rules);
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());

        if($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            )); 
        }
        $data = [
            'user_id' => $request->user_id,
            'content' => $request->content,
        ];
        if($request->parent)
            $data['parent'] = $request->parent;
        $comment = Comment::create($data);
        return response()->json(array('success' => true, 'comment' => $comment), 200);
    }

    public function getComments() {
        $comments = Comment::all();
        foreach ($comments as $comment) {
            $comment->user = $comment->user();
        }
        return json_encode($comments);
    }
}