<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->comment_body,
        ]);

        return redirect()
            ->route('item.show', ['item_id' => $item_id])
            ->with('success', 'コメントを投稿しました')
            ->withFragment('comment-section');
    }
}
