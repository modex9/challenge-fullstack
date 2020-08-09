<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

        $user = Auth::user();
        if($user->id !== $request->user_id)
            return response()->json(array(
                'success' => false,
                'errors' => ['forgery' => ['Do not try to impersonate another user. Continuing this activity may result in your account suspension.']]
            ));
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
        $post = Comment::create($data);
        $comment = $post;
        $comment->user = $post->user;
        return response()->json(array('success' => true, 'comment' => $comment), 200);
    }

    public function getComments() {
        $comments = Comment::all();
        foreach ($comments as $comment) {
            $comment->user = $comment->user;
        }
        $comments_tree = self::createTree($comments);
        return json_encode($comments_tree);
    }

    public function destroy(Comment $comment)
    {
        $user = Auth::user();
        if(!$comment)
            return response()->json(array(
                'success' => false,
                'errors' => ['not-found' => ['The comment you are trying to delete does not exist.']]
            ));
        if($user->id !== $comment->user->id)
            return response()->json(array(
                'success' => false,
                'errors' => ['forgery' => ['The comment you are trying to delete is not yours.']]
            ));
        $comment_id = $comment->id;
        $comment->delete();
        return response()->json(array('success' => true, 'comment_id' => $comment_id), 200);
    }

    protected static function createTree($elements, $parentId = 0) {
        $tree = [];
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = self::createTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $tree[] = $element;
            }
        }

        return $tree;
    }
}