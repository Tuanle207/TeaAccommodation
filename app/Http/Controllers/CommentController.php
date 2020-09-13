<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CreateCommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(CreateCommentRequest $req, $id) {
        
        // get required infos
        $input = $req->all();

        // save image

        $commentInfo = [
            'text' => $input['text'],
            'idApartment' => $id,
            'idUser' =>  $req->input('user')->id
        ];


        // save to DB
        $comment = Comment::create($commentInfo);

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ], 200);
    }

    public function deleteComment(Request $req, $id, $comment_id) {
        // check if comment exists?
        $comment = Comment::find($comment_id);
        if (!$comment) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Bình luận này không tồn tại' 
            ], 404);
        }

        // check if the user is the owner of this comment? 
        if ($comment->idUser != $req->input('user')->id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Chỉ chủ bình luận mới có thể thực hiện thao tác này' 
            ], 403);
        }
        

        // delete that comment
        $comment->delete();

        return response()->json([
            'status' => 'success',
            'data' => null
        ], 204);
    }

    public function getComments(Request $req, $id) {

        $comments = Comment::where('idApartment', $id)->with('user')->get(); // need pagination

        return response()->json([
            'status' => 'success',
            'data' => $comments
        ], 200);
    } 
}
