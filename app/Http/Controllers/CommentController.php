<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(Request $req, $id) {
        return response()->json([
            'comment' => $req->except(['user', 'apartment']),
            'id' => $id
        ]);
    }
}
