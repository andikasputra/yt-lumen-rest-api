<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return response()->json(Auth::user()->notes);
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'count' => 'required|min:1',
            'price' => 'required|min:1',
            'type' => 'required|in:income,expense',
            'date' => 'required|date'
        ]);

        $note = new Note();
        $note->title = $validated['title'];
        $note->description = $validated['description'];
        $note->count = $validated['count'];
        $note->price = $validated['price'];
        $note->type = $validated['type'];
        $note->date = date('Y-m-d', strtotime($validated['date']));
        $note->user_id = Auth::user()->id;
        $note->save();
        return response()->json($note);
    }

    public function show(Request $request, $id)
    {
        $note = Note::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (empty($note)) {
            abort(404, "Dat not found");
        }
        return response()->json($note);
    }

    public function update(Request $request, $id)
    {
        $note = Note::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (empty($note)) {
            abort(404, "Dat not found");
        }
        $validated = $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'count' => 'required|min:1',
            'price' => 'required|min:1',
            'type' => 'required|in:income,expense',
            'date' => 'required|date'
        ]);
        $note->title = $validated['title'];
        $note->description = $validated['description'];
        $note->count = $validated['count'];
        $note->price = $validated['price'];
        $note->type = $validated['type'];
        $note->date = date('Y-m-d', strtotime($validated['date']));
        $note->save();
        return response()->json($note);
    }

    public function destroy(Request $request, $id)
    {
        $note = Note::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (empty($note)) {
            abort(404, "Dat not found");
        }
        $note->delete();
        return response()->json(['message' => 'data deleted']);
    }
}
