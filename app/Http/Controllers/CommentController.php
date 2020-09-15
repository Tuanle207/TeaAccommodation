<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CreateCommentRequest;
use App\Utils\ApiFeaturesHandler;
use App\Utils\ImageHandler;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(CreateCommentRequest $req, $id) {
        
        // get required infos
        $body = $req->all();

        $commentInfo = [
            'idApartment' => (int)$id,
            'commentedBy' =>  $req->input('user')->id
        ];
        foreach ($body as $key => $value) {
            // store photo
            if ($key === 'photo') {
                $commentInfo[$key] = ImageHandler::storeImage($value, 'comment');
            }// store text
            else if ($key === 'text') {
                $commentInfo[$key] = $value;
            }
        }

        // save to DB
        $comment = Comment::create($commentInfo);

        // attach user info to comment
        unset($comment->commentedBy);
        $comment->commentedBy = $this->filterUser($req->input('user'));

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ], 201);
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
        
        // delete comment's photo
        if ($comment->photo != null) 
           ImageHandler::deleteImage($comment->photo);
        
           // delete that comment
        $comment->delete();

        return response()->json([
            'status' => 'success',
            'data' => null
        ], 204);
    }


    public function getComments(Request $req, $id) {

        $queryStr = (object)$req->except(['user', 'apartment']);
        $apiHandler = new ApiFeaturesHandler(Comment::query(), $queryStr, 'comment', $id);
        $result = $apiHandler->useIdentifier()->sort()->limitFields()->paginate()->populate()->getWithMetadata();

        return response()->json([
            'status' => 'success',
            'meta' => $result->meta,
            'data' => $result->data
        ], 200);
    } 


    private function filterUser($srcUser) {
        $user = (object)[];
        $user->id = $srcUser->id;
        $user->name = $srcUser->name;
        $user->photo = $srcUser->photo;
        return $user;
    }
}
