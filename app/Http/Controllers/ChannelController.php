<?php

namespace App\Http\Controllers;

use App\Channel;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
 // Return All data from Channel Table using Yajra Data Table

        if($request->ajax())
        {
            $data = Channel::latest()->get();
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('channel\index');
        }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate User Inputed Data
        $rules = array(
            'name'    =>  'required',
            'url'     =>  'required',
            'rating'     =>  'required',
            'description'     =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        // Collecting User Inputed Data

        $form_data = array(
            'name'        =>  $request->name,
            'url'         =>  $request->url,
            'rating'         =>  $request->rating,
            'description'         =>  $request->description,
        );
// Creating New Item .......
        Channel::create($form_data);

        return response()->json(['success' => 'Data Added successfully.']);

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        // Return Specific data baed on Id
        if(request()->ajax())
        {
            $data = Channel::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Channel $channel)
    {
        // Validate User Inputed Data
        $rules = array(
            'name'    =>  'required',
            'url'     =>  'required',
            'rating'     =>  'required',
            'description'     =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
  //   Collecting Form Data
        $form_data = array(
            'name'        =>  $request->name,
            'url'         =>  $request->url,
            'rating'         =>  $request->rating,
            'description'         =>  $request->description,
        );
    // Update data based on Id
        Channel::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete specific data based on id
        $data = Channel::findOrFail($id);
        $data->delete();
    }
}
