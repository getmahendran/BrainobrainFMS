<?php

namespace App\Http\Controllers;

use App\BillBook;
use App\Fee;
use App\FeeCollection;
use App\FeeType;
use App\Level;
use App\Receipt;
use Illuminate\Http\Request;
use App\Status as Status;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FeeCollectionController extends Controller
{
    public function __construct(Status $status)
    {
        Artisan::call('fee:refresh');
        $this->status           =  $status->allFeeCollectionStatus();
        $this->payment_type     =  $status->allFeePaymentTypes();
        $this->status_obj       =  $status;
    }

    public function show($id)
    {
        $fee_collection     =   FeeCollection::findorfail($id);
        $output             =   array(
            'fee_description'       =>  $fee_collection->fee_description,
            'physical_receipt_no'   =>  $fee_collection->physical_receipt_no,
            'comments'              =>  $fee_collection->comments,
            'fee_payment_type'      =>  $this->status_obj->getFeePaymentType($fee_collection->fee_payment_type),
            'status'                =>  $this->status[$fee_collection->status]
        );
        echo json_encode(["success"  =>  $output]);
    }

    public function pay(Request $request)
    {
        $status = $this->status;
        $validate   =  Validator::make($request->all(),[
            'level_id'  =>  'required'
        ],[
            'level_id.required' =>  "Student no is required"
        ]);

        if(!$validate->fails())
        {
            session(['level_id' => $request->input('level_id')]);

            $fees = FeeCollection::all()->where('level_id', '=', $request->input('level_id'));
            $level = Level::findorfail($request->input('level_id'));
            $monthly_fees       =   $fees->filter(function ($item){
                if ($item['fee_type_id'] == 2 && ($item['status']==0||$item['status']==1||$item['status']==4||$item['status']==5))
                    return $item;
            })->values();

            $exam_fees          = $fees->filter(function ($item) {
                if ($item['fee_type_id'] == 3 && ($item['status']==0||$item['status']==1||$item['status']==4||$item['status']==5))
                    return $item;
            })->values();

            $fees_buffer        =   $fees->filter(function($item){
                if($item['status'] == 2)
                    return $item;
            });

            $drafted_fees       =   $fees->filter(function($item){
                if($item['status'] == 3)
                    return $item;
            });
            $sum        =   0;
            foreach ($drafted_fees as $drafted_fee)
                $sum        +=  $drafted_fee->fee['price'];

            $active_monthly_fees     =   Fee::all()->where('status','=',1)->where('area_id','=',$level->franchisee->area_id)->where('fee_type_id','=',2);
            $active_exam_fees        =   Fee::all()->where('fee_type_id','=',3)->where('status','=',1);

            if($request->ajax())
            {
                $stage  =   $request->input('stage');
                echo  view("fee_collection.feeCollectAjax", compact('level', 'monthly_fees', 'status', 'exam_fees','fees_buffer','drafted_fees','active_monthly_fees','active_exam_fees','sum','stage'));
            }
            else
                return view("fee_collection.feeCollect", compact('level', 'monthly_fees', 'status', 'exam_fees','fees_buffer','drafted_fees','active_monthly_fees','active_exam_fees','sum'));

        }
        elseif ($request->ajax() && $validate->fails())
        {
            return response()->json(['errors' => $validate->errors()]);
        }
        else
        {
            session()->forget('level_id');
            return view("fee_collection.feeCollect",compact('status'));
        }
    }

    public function edit($id)
    {
        $data['fee_collection_id']      =   $id;
        $validate       =   Validator::make($data, [
            'fee_collection_id'     =>  [
                                            'required',
                                            Rule::exists('fee_collections','id')->where(function ($query) {
                                                $query->where('level_id', session()->get('level_id'));
                                            }),
                                        ]
                                        ]);
        if(!$validate->fails())
        {
            $fee_collection     =   FeeCollection::findorfail($id);
            if($fee_collection->fee_type_id == 2)
            {
                $fee_ids   =   DB::table('fees as fs')
                                ->whereRaw('fee_type_id=2 AND status=1 AND area_id in (SELECT area_id FROM franchisees WHERE id='.$fee_collection->level->franchisee_id.')')
                                ->get();
            }
            else
                $fee_ids        =   Fee::all()->where('status','=',1)->where('fee_type_id','=',$fee_collection->fee_type_id);
            $output           =   [
                'fee_collection'=>  $fee_collection,
                'fee_ids'       =>  $fee_ids,
                'action-url'    =>  route('fee_collect.update',$id),
                'payment_types' =>  $this->payment_type
            ];
            echo json_encode($output);
        }
    }

    public function update($id, Request $request)
    {
        $data                           =   $request->all();
        if($request->input("button_action") == "add_fee")
        {
            $data['fee_collection_id']      =   $id;
            $validate                       =   Validator::make($data,[
                'fee_collection_id'     =>  [
                    'required',
                    Rule::exists('fee_collections','id')->where(function ($query) {
                        $query->where('level_id', session()->get('level_id'))->where('status',0)->orWhere('status',4);
                    }),
                ],
            ]);
            if($validate->fails())
                echo json_encode($validate->errors());
            else
            {
                $fee_collection =   FeeCollection::findorfail($id);
                if($fee_collection->fee_type_id == 3)
                    FeeCollection::where('level_id',session()->get('level_id'))->where('id','<=',$id)->where('status',0)->orWhere('status',4)->update(['status' => 2]);
                else
                    $fee_collection->update(['status'   =>  2]);
                $pending_monthly_fees_count   =   FeeCollection::where('level_id','=',session()->get('level_id'))->where("fee_type_id",'=',2)->where("status",'=',0)->orWhere('status','=',4)->count();
                if($pending_monthly_fees_count == 0)
                    FeeCollection::create([
                        'fee_type_id'       =>  2,
                        'level_id'          =>  session()->get('level_id'),
                        'student_id'        =>  Level::findorfail(session()->get('level_id'))->student->id,
                        'fee_description'   =>  "EXTRA MONTHLY FEE",
                        'royalty_status'    =>  1,
                        'status'            =>  0
                    ]);

                echo json_encode("Added successfully..!!");
            }
        }
        else if($request->input('button_action') == "draft")
        {
            $data                       =   $request->all();
            $data['fee_collection_id']  =   $id;
            $fee_collection             =   FeeCollection::findorfail($id);
            $level                      =   Level::findorfail(session()->get('level_id'));
            $bill_book                  =   BillBook::where('fee_type_id','=',$fee_collection->fee_type_id)
                                            ->where('franchisee_id','=',$level->franchisee_id)
                                            ->where('status','=',1)
                                            ->first();
            $validate                   =   Validator::make($data, [
                'fee_collection_id'     =>  [
                    'required',
                    Rule::exists('fee_collections','id')->where(function ($query) {
                        $query->where('level_id', session()->get('level_id'))->where('status',2)->first();
                    }),
                ],
                'fee_payment_type'      =>  'required|integer|between:0,'.count($this->payment_type),
                'physical_receipt_no'   =>  [
                                                'nullable','required_if:fee_payment_type,==,0',
                                                function($attribute, $value, $fail) use ($bill_book, $fee_collection){
                                                    if(empty($bill_book))
                                                        return $fail('Franchisee doesn\'t have a active '.$fee_collection->fee_type->name.' bill book');
                                                    else
                                                    {
                                                        if(!($value>=$bill_book->from&&$value<=$bill_book->till))
                                                            return $fail("Receipt number entered is invalid");
                                                    }
                                                },
                                                Rule::unique('fee_collections','physical_receipt_no')->where(function($query) use ($bill_book){
                                                    $query->where('bill_book_id',$bill_book->id);
                                                }),
                                            ],
                'comments'              =>  'required_if:fee_payment_type,==,1',
                'fee_id'                =>  'required_if:fee_payment_type,==,0',
                'physical_receipt_date' =>  'required_if:fee_payment_type,==,0|nullable|date'
            ],[
                'fee_payment_type.required'         =>  'Select Payment type',
                'fee_payment_type.between'          =>  'Select a valid fee type',
                'fee_payment_type.integer'          =>  'Select a valid fee type',
                'comments.required_if'              =>  'Reason field is a required',
                'fee_id.required_if'                =>  'Select the fee price',
                'physical_receipt_no.required_if'   =>  'Receipt number field is required',
                'physical_receipt_date.required_if' =>  'Receipt date is required'

            ]);
            if($validate->fails())
                echo json_encode(['errors'  =>  $validate->errors()]);
            else
            {
                if ($data['fee_payment_type'] == 0) {
                    $fee_collection->update([
                        'fee_payment_type'      =>  0,
                        'status'                =>  3,
                        'bill_book_id'          =>  $bill_book->id,
                        'physical_receipt_no'   =>  $data['physical_receipt_no'],
                        'physical_receipt_date' =>  $data['physical_receipt_date'],
                        'fee_id'                =>  $data['fee_id']
                    ]);
                }
                if ($data['fee_payment_type'] == 1)
                    $fee_collection->update([
                        'fee_payment_type'      =>  1,
                        'status'                =>  3,
                        'physical_receipt_no'   =>  "FC",
                        'comments'              =>  $data['comments']
                    ]);
                echo json_encode(["success"     =>  "Receipt moved for final submission"]);
            }
        }
        else if($request->input('button_action') == 'remove_from_draft')
        {
            $fee_collection =   FeeCollection::findorfail($id);
            $fee_collection->update([
                'fee_id'                =>  null,
                'bill_book_id'          =>  null,
                'physical_receipt_no'   =>  null,
                'physical_receipt_date' =>  null,
                'comments'              =>  null,
                'status'                =>  2,
                'fee_payment_type'      =>  null
            ]);
            echo json_encode(["success"     =>  "Receipt removed from draft"]);
        }
        else if($request->input('button_action') == 'remove_from_buffer')
        {
            $fee_collection =   FeeCollection::findorfail($id);
            if($fee_collection->fee_type_id == 2)
                FeeCollection::where('level_id', session()->get('level_id'))->where('fee_description','EXTRA MONTHLY FEE')->where('status',0)->delete();
            $fee_collection->update([
                'fee_id'                =>  null,
                'bill_book_id'          =>  null,
                'physical_receipt_no'   =>  null,
                'physical_receipt_date' =>  null,
                'comments'              =>  null,
                'status'                =>  0,
                'fee_payment_type'      =>  null
            ]);
            echo json_encode(["success"     =>  "Receipt removed from buffer"]);
        }
        else if($request->input('button_action') == 'final_submit')
        {
            $receipt    =   Receipt::create();
            FeeCollection::where('level_id',$id)->where('status',3)->update([
                                                                                'status'        =>  1,
                                                                                'receipt_id'    =>  $receipt->id
                                                                            ]);
            echo  json_encode("Submitted successfully..!!");
        }
        else if($request->input('button_action') == 'admin_update_fee')
        {
            $data                       =   $request->all();
            $data['fee_collection_id']  =   $id;
            $fee_collection             =   FeeCollection::findorfail($id);
            $level                      =   Level::findorfail(session()->get('level_id'));
            $bill_book                  =   BillBook::where('fee_type_id','=',$fee_collection->fee_type_id)
                                            ->where('franchisee_id','=',$level->franchisee_id)
                                            ->where('status','=',1)
                                            ->first();
            $validate                   =   Validator::make($data, [
                'fee_collection_id'     =>  [
                    'required',
                    Rule::exists('fee_collections','id')->where(function ($query) {
                        $query->where('level_id', session()->get('level_id'))->where('status',1)->first();
                    }),
                ],
                'fee_payment_type'      =>  'required|integer|between:0,'.(count($this->payment_type)-1),
                'physical_receipt_no'   =>  [
                    'nullable','required_if:fee_payment_type,==,0',
                    function($attribute, $value, $fail) use ($bill_book, $fee_collection, $data){
                        if($data['fee_payment_type'] == 1)
                            return true;
                        if(empty($bill_book))
                            return $fail('Franchisee doesn\'t have a active '.$fee_collection->fee_type->name.' bill book');
                        else
                            if(!($value>=$bill_book->from&&$value<=$bill_book->till))
                                return $fail("Receipt number entered is invalid");
                    },
                    Rule::unique('fee_collections','physical_receipt_no')->where(function($query) use ($bill_book, $fee_collection){
                        $query->where('bill_book_id',$bill_book->id)->where('id','!=',$fee_collection->id);
                    }),
                ],
                'comments'              =>  'required_if:fee_payment_type,==,1|required_if:status,==,4',
                'fee_id'                =>  'required_if:fee_payment_type,==,0',
                'physical_receipt_date' =>  'required_if:fee_payment_type,==,0|nullable|date',
                'status'                =>  'required|integer|between:0,'.(count($this->status)-1)
            ],[
                'fee_payment_type.required'         =>  'Select Payment type',
                'fee_payment_type.between'          =>  'Select a valid fee type',
                'fee_payment_type.integer'          =>  'Select a valid fee type',
                'comments.required_if'              =>  'Reason field is a required',
                'fee_id.required_if'                =>  'Select the fee price',
                'physical_receipt_no.required_if'   =>  'Receipt number field is required',
                'physical_receipt_date.required_if' =>  'Receipt date is required'

            ]);

            if($validate->fails())
                echo json_encode(["errors"  =>  $validate->errors()]);
            else
            {
                if($data['fee_payment_type'] == 1)
                    $fee_collection->update([
                        'fee_payment_type'      =>  $data['fee_payment_type'],
                        'physical_receipt_no'   =>  "FC",
                        'physical_receipt_date' =>  null,
                        'comments'              =>  $data['comments'],
                        'status'                =>  $data['status']
                    ]);
                else
                    $fee_collection->update([
                        'fee_payment_type'      =>  $data['fee_payment_type'],
                        'fee_id'                =>  $data['fee_id'],
                        'physical_receipt_no'   =>  $data['physical_receipt_no'],
                        'physical_receipt_date' =>  $data['physical_receipt_date'],
                        'comments'              =>  $data['comments'],
                        'status'                =>  $data['status']
                    ]);
                echo json_encode(['success' =>  'Fee details updated successfully..!!']);
            }

        }
        else
            echo json_encode(["success" =>  "Not a valid request"]);
    }

    public function search(Request $request)
    {
        $output=[];
        if($request->input("search"))
        {
            $result=Level::where('student_no', 'like', strtoupper($request->input('search')) . '%')->get();
            foreach ($result as  $item)
            {
                $output[]=["id"=>$item->id,"text"=>$item->student_no];
            }
        }
        echo json_encode($output);
    }
}
