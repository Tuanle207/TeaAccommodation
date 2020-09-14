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
            'idUser' =>  $req->input('user')->id
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
        unset($comment->idUser);
        $comment->user = self::filterUser($req->input('user'));

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
        // $queryStr = (object)$req->query();
        // $currentPage = property_exists($queryStr,'page') ? (int) $queryStr->page : 1;
        // $limit = property_exists($queryStr,'limit') ? (int) $queryStr->limit : 10;
        // $skip = ($currentPage - 1) * $limit;
        // $totalPages = (int) ceil(Comment::count() / $limit);

        // $comments = Comment::where('idApartment', $id)->orderBy('commentedAt', 'asc')
        //     ->skip($skip)->limit($limit)->with(['user' => function($query) {
        //     $query->select(['id', 'name', 'photo']);
        // }])->get(); 
       
        // test

        $queryStr = (object)$req->except(['user', 'apartment']);
        $query = Comment::query();
        $apiHandler = new ApiFeaturesHandler($query, $queryStr, 'comment', $id);
        $apiHandler->filter();

        // test
        // return response()->json([
        //     'status' => 'success',
        //     'meta' => $meta,
        //     'data' => $comments
        // ], 200);
    } 


    private static function filterUser($srcUser) {
        $user = (object)[];
        $user->id = $srcUser->id;
        $user->name = $srcUser->name;
        $user->photo = $srcUser->photo;
        return $user;
    }
}
