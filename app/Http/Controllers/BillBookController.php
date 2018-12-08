<?php

namespace App\Http\Controllers;

use App\BillBook;
use App\FeeCollection;
use App\FeeType;
use App\Franchisee;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Validation\Rule;
use App\Status as Status;
use Illuminate\Support\Facades\Validator;

class BillBookController extends Controller
{
    public function __construct(Status $status)
    {
        $this->status       =   $status->allBillBookStatus();
    }

    public function create()
    {
        $franchisees    =   Franchisee::select('franchisee_name','id','center_code')->where('status','=',1)->get();
        $fee_types      =   FeeType::all()->where('status','=',1);
        return view('bill_book.bill_book_register', compact('franchisees','fee_types'));
    }

    public function store(Request $request)
    {
        $data   =   $request->all();
        $this->validate($request,[
            'franchisee_id'     =>  ['required',
                                        Rule::exists("franchisees","id")->where(function($query){
                                            $query->where("status",1);
                                        })
                                    ],
            'fee_type_id'       =>  ['required',
                                        Rule::exists("fee_types","id")->where(function($query) {
                                            $query->where("status",1);
                                        })
                                    ],
            'from'              =>  'required|integer|min:1',
            'till'              =>  ['required','integer','min:1',function($attribute, $value, $fail) use ($request){
                                        if($value+1-$request->input('from')!= 100)
                                        {
                                            return $fail('Receipt number is invalid');
                                        }
                                    }]
        ],[
            'franchisee_id.required'    =>  'The franchisee branch is required',
            'franchisee_id.exists'      =>  'The franchisee branch selected is invalid',
            'fee_type_id.exists'        =>  'The fee type selected is invalid',
            'fee_type_id.required'      =>  'The fee type field is required',
            'from.required'             =>  'This field is required',
            'till.required'             =>  'This field is required',
            'from.integer'              =>  'Receipt number invalid',
            'from.min'                  =>  'Receipt number invalid',
            'till.integer'              =>  'Receipt number invalid',
            'till.min'                  =>  'Receipt number invalid',
        ]);

        BillBook::create([
            'franchisee_id'     =>  $data['franchisee_id'],
            'fee_type_id'       =>  $data['fee_type_id'],
            'from'              =>  $data['from'],
            'till'              =>  $data['till'],
            'status'            =>  0
        ]);
        return back()->with(['success'=>'New bill book issued']);
    }

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data       =   $request->all();
            $validate   =   Validator::make($data, [
                'franchisee_id'     =>  ['required',
                    Rule::exists("franchisees","id")->where(function($query){
                        $query->where("status",1);
                    })
                ],
            ],[
                'franchisee_id.required'    =>  'Select the franchisee branch.',
                'franchisee_id.exists'      =>  'Select a valid franchisee'
            ]);
            if($validate->fails())
                echo json_encode(['errors'  =>  $validate->errors()]);
            else
            {
                $output     =   array();
                $fee_types      =   FeeType::all()->where('status','=',1);
                $bills          =   BillBook::all()->where("franchisee_id",'=',$data['franchisee_id']);
                foreach ($fee_types as $fee_type)
                {
                    $bill       =   array();
                    foreach ($bills->filter(function ($item) use ($fee_type){if($item->fee_type_id == $fee_type->id) return $item;}) as $value)
                    {
                        $external_link      =   '';
                        switch ($value->status)
                        {
                            case 0: $external_link  =   "<td class='text-center'><a href='".route('bill_book.update', $value->id)."' class='text-success nounderline activate-bill-book' title='Activate' onclick='activate_bill_Book($(this))'><i class='fa fa-check-circle'></i> Activate</a></td><td class='text-center'><a href='".route('bill_book.delete', $value->id)."' onclick='delete_bill_confirm_dialog(\"Are you sure\",\"Want to delete the bill book ?\",$(this))' class='text-danger nounderline delete-bill-book' title='Remove'><i class='fa fa-trash-alt'></i> Remove</a></td>";
                                    break;
                            case 1: $external_link  =   "<td class='text-center'><a href='".route('bill_book.edit', $value->id)."' class='text-primary nounderline edit-bill-book' onclick='edit_bill_details($(this))' title='Edit'><i class='fa fa-edit'></i> Edit</a></td><td class='text-center'><a href='".route('bill_book.update',$value->id)."' title='Deactivate' onclick='deactivate_bill_Book($(this))' class='text-warning deactivate-bill-book nounderline'><i class='fa fa-window-close'></i> Deactivate</a></td>";
                                    break;
                            case 2: $external_link  =   "<td class='text-center'><a href='".route('bill_book.edit', $value->id)."' class='text-primary nounderline edit-bill-book' onclick='edit_bill_details($(this))' title='Edit'><i class='fa fa-edit'></i> Edit</a></td><td><i class='fa-minus fa'></i></td>";
                                    break;
                            default:break;
                        }
                        $bill[]     =   array(
                            'id'            =>  $value->id,
                            'from'          =>  $value->from,
                            'till'          =>  $value->till,
                            'wasted_count'  =>  $value->wasted_count,
                            'remaining'     =>  $value->till-$value->from+1-count(FeeCollection::all()->where('bill_book_id','=',$value->id))-$value->wasted_count,
                            'status'        =>  $this->status[$value->status],
                            'external-link' =>  $external_link
                        );
                    }

                    $output[] =   array(
                        'fee_type'   =>   $fee_type,
                        'bills'      =>   $bill,
                        'status'     =>   $this->status,
                    );
                }
                echo json_encode(['success'=>$output]);
            }
        }
        else
        {
            $status         =   $this->status;
            $franchisees    =   Franchisee::all()->where('status','=',1);
            return view("bill_book.manageBillBooks",compact('status','franchisees'));
        }
    }

    public function edit($id)
    {
        $bill_book  =   BillBook::findorfail($id);
        $bill_book  =   array(
            'id'            =>  $bill_book->id,
            'from'          =>  $bill_book->from,
            'till'          =>  $bill_book->till,
            'wasted_count'  =>  $bill_book->wasted_count,
            'comments'      =>  $bill_book->comments,
            'fee_type'      =>  $bill_book->fee_type->name,
            'status'        =>  $this->status[$bill_book->status],
            'action_url'    =>  route('bill_book.update',$bill_book->id)
        );
        echo json_encode($bill_book);

    }

    public function update(Request $request, $id)
    {
        if  ($request->input('button_action') == "activate")
        {
            $bill_book          =   BillBook::findorfail($id);
            $data               =   $request->all();
            $data['bill_id']    =   $id;
            $validate           =   Validator::make($data,[
                'bill_id'   =>  ['required','exists:bill_books,id', function($attribute, $value, $fail) use ($bill_book){
                $count          =   count(BillBook::all()->where('franchisee_id','=',$bill_book->franchisee_id)->where('fee_type_id','=',$bill_book->fee_type_id)->where('status','=',1));
                if($count>0)
                    return $fail("You already have a active ".$bill_book->fee_type->name." bill book");
                }]
            ]);
            if(!$validate->fails())
            {
                $bill_book->update(['status'    =>  1]);
                echo json_encode("Bill book activated Successfully..!!");
            }
            else
                echo json_encode(['errors'   =>  $validate->errors()]);
        }
        elseif ($request->input('button_action') == 'deactivate')
        {
            $bill_book              =   BillBook::findorfail($id);
            $data                   =   $request->all();
            $data['bill_book_id']   =   $id;
            $validate               =   Validator::make($data,[
                'bill_book_id'      =>  'required|exists:bill_books,id|unique:fee_collections,bill_book_id'
            ],[
                'bill_book_id.unique'      =>  'Book cannot be deactivated as it is under use'
            ]);
            if($validate->fails())
                echo json_encode(['errors'  =>  $validate->errors()]);
            else
            {
                $bill_book->update(['status'    =>  0]);
                echo json_encode("Bill book De-activated Successfully..!!");
            }
        }
        elseif ($request->input('button_action') == 'wasted_receipt')
        {
            $bill_book              =   BillBook::findorfail($id);
            $data                   =   $request->all();
            $data['bill_book_id']   =   $id;
            $validate               =   Validator::make($data,[
                'bill_book_id'     =>  ['required','exists:bill_books,id'],
                'wasted_count'     =>  'nullable|integer|min:0|max:100',
                'comments'         =>  'nullable|required_with:wasted_count'
            ],[
                'wasted_count.integer'  =>  'The wasted count is invalid',
                'wasted_count.min'      =>  'The wasted count is invalid',
                'wasted_count.max'      =>  'The wasted count is invalid',
                'comments.required_with'=>  'The comments field is required'
            ]);
            if($validate->fails())
                echo json_encode(['errors'  =>  $validate->errors()]);
            else
            {
                $bill_book->update([
                    'wasted_count'  =>  $data['wasted_count'],
                    'comments'      =>  $data['comments']
                ]);
                echo json_encode("Updated Successfully..!!");
            }

        }
    }

    public function view($id)
    {

    }

    public function destroy($id)
    {
        BillBook::destroy($id);
        echo json_encode("Bill Book removed successfully..!!");
    }

}
