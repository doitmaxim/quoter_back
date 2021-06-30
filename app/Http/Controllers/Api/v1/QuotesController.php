<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Quotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class QuotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $quotes = Quotes::orderBy('id', 'desc')->paginate(10);
        foreach($quotes as $quote) {
            $tags_arr = explode(',',$quote->tags);
            $quote->tags = DB::table('tags')->whereIn('id', $tags_arr)->get();
            $quote->formatted_date = $quote->created_at->format('d.m.Y H:m');
        }
        return response()->json(
            [
                'items' => $quotes->items(),
                'current_page' => $quotes->currentPage(),
                'from' => $quotes->firstItem(),
                'last_page' => $quotes->lastPage(),
                'per_page' => $quotes->perPage(),
                'to' => $quotes->lastItem(),
                'total' => $quotes->total()
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'quote_text' => 'required',
                'author' => 'required',
                'tags' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $validator->messages();
        }

        Quotes::create([
            'quote_text' => $request->quote_text,
            'author' => $request->author,
            'tags' => $request->tags
        ]);

        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quote = Quotes::findOrFail($id);
        $tags_arr = explode(',',$quote->tags);
        $quote->tags = DB::table('tags')->whereIn('id', $tags_arr)->get();
        $quote->formatted_date = $quote->created_at->format('d.m.Y H:m');
        return $quote;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
