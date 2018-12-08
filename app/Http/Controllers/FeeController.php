<?php

namespace App\Http\Controllers;

use App\Area;
use App\Fee;
use App\FeeType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Status as Status;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Status $status)
    {
        $this->status       =   $status;
        $this->feeStatus    =   $status->allFeeStatus();
    }

    public function index(Request $request)
    {
        $status         =   $this->status;
        if($request->ajax())
        {
            if($request->get("fee_type_id"))
            {
                $validate   =   Validator::make($request->all(), [
                    "fee_type_id"      => [
                        "required",
                        Rule::exists('fee_types','id'),
                    ],
                ]);
                if($validate->fails())
                    echo json_encode([
                        "errors"  =>  "Something went wrong please try again :(",
                    ]);
                else
                {
                    $fee_details    =   array();
                    $fees           =   Fee::orderBy("id","desc")->where("fee_type_id","=",$request->get("fee_type_id"))->get();
                    foreach ($fees as $fee)
                    {
                        $fee_details[]          =   array(
                            "fee_id"            =>  $fee->id,
                            "area"              =>  $fee->area['name'],
                            "price"             =>  $fee->price,
                            "status"            =>  $status->getFeeStatus($fee->status),
                            "effective_from"    =>  date('d-m-Y',strtotime($fee->effective_from)),
                            "effective_till"    =>  $fee->effective_till == null ? null : date('d-m-Y',strtotime($fee->effective_till)),
                            "edit-url"          =>  route("fee.edit", $fee->id)
                        );
                    }
                    $fee_type       =   FeeType::findorfail($request->get("fee_type_id"))->name;
                    if(count($fee_details))
                        echo json_encode([
                            "fee_type"      =>  $fee_type,
                            "fee_details"   =>  $fee_details,
                        ]);
                    else
                        echo json_encode([
                            "errors"  =>  "No fee records found :(",
                        ]);
                }
            }
        }
        else
        {
            $feeTypes       =   FeeType::all()->where("status","=",1);
            $areas          =   Area::all();
            $fee_status     =   $status->allFeeStatus();
            return view('fee.manageFees', compact('feeTypes','areas','fee_status'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas          =   Area::all();
        $fee_types      =   FeeType::all()->where('status','=',1);
        return view('fee.addFee',compact('fee_types','areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
                "fee_type_id"      =>  [
                                        "required",
                                        Rule::exists('fee_types','id')->where(function ($query) {
                                            $query->where('status', 1);
                                        }),
                                    ],
            "area_id"               =>  "required_if:fee_type_id,==,2|exists:areas,id",
            "effective_from"        =>  "required|date|date_format:Y-m-d|after:".date("Y-m-d",strtotime(' -1 day')),
            "price"                 =>  "required|integer|min:0"
        ],[
            "area_id.required_if"   =>  "The Urban/Rural is required.",
            "effective_from.after"  =>  "The effective from field is invalid",
        ]);

        $fee    =   Fee::create([
                        "fee_type_id"       =>  $request->get("fee_type_id"),
                        "price"             =>  $request->get("price"),
                        "effective_from"    =>  $request->get("effective_from"),
                        "status"            =>  2,
                    ]);
        if($request->get("effective_from") == date("Y-m-d"))
        {
            $fee->status        =   1;
            $fee->save();
        }
        if($request->get("fee_type_id") == 2)
        {
            $fee->area_id       =   $request->get("area_id");
            $fee->save();
        }
        return back()->with(['success'      =>  'Fee added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fee        =   Fee::findorfail($id);
        echo json_encode(["fee" =>  $fee, "action-url"  =>  route("fee.update",$id)]);
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
        $fee                    =   Fee::findorfail($id);
        $data                   =   $request->all();
        $data["fee_type_id"]    =   $fee->fee_type_id;
        $validate       =   Validator::make($data,[
            "area_id"               =>  "required_if:fee_type_id,==,2|exists:areas,id",
            "effective_from"        =>  "required|date|date_format:Y-m-d",
            "price"                 =>  "required|integer|min:0",
            'status'                =>   'required|integer|min:0|max:'.(count($this->feeStatus)-1),
        ],[
            "area_id.required_if"   =>  "The Urban/Rural is required.",
            "effective_from.after"  =>  "The effective from field is invalid.",
        ]);
        if($validate->fails())
        {
            echo json_encode(["errors"  =>  $validate->errors()]);
        }
        else
        {
            if($data["fee_type_id"] == 2)
            {
                $fee->area_id       =   $request->get("area_id");
            }
            $fee->price             =   $request->get("price");
            $fee->effective_from    =   $request->get("effective_from");
            $fee->status            =   $request->get("status");
            if($data["status"] == 0)
                $fee->effective_till=   date("Y-m-d");
            else
                $fee->effective_till=   null;
            $fee->save();
            echo json_encode("Fee details updated successfully");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if($request->ajax()) {
            FeeStructure::destroy($id);
            echo json_encode('Record deleted successfully');
        }else{
            FeeStructure::destroy($id);
            return back()->withErrors(['Deleted successfully']);
        }
    }
}