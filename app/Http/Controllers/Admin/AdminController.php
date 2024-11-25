<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Http\Request;

use function Pest\Laravel\options;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPolls = Poll::count();
        $polls = Poll::with('options', 'votes')->get();



        return view('admin.dashboard', compact('totalUsers', 'totalPolls', 'polls'));
    }


    public function toggleStatus(Request $request)
    {


        $poll = Poll::find($request->id);
        if ($poll) {
            if ($poll->status) {
                $poll->status = 0;
            } else {
                $poll->status = 1;

            }
            $poll->save();
            return response()->json([
                'poll' => $poll,
                'status' => "success",
                'message' => 'Status change'
            ]);
        }
        return response()->json([
            'status' => "error",
            'message' => 'Poll not found'
        ]);

    }

    public function delete($id)
    {
        $poll = Poll::find($id);

        if (!$poll) {
            return response()->json([
                'success' => 'false',
                'message' => 'Poll not found',
            ]);
        }

        $poll->delete();
        $polls = Poll::with('options', 'votes')->get();
        $html = view('admin.poll.table', compact('polls'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'message' => "Poll deleted successfully",
        ]);
    }
}
