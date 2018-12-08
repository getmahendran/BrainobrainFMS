<?php

namespace App\Http\Controllers;

use App\Batch;
use App\FacultyAccount;
use App\Franchisee;
use App\Level;
use App\Program;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            if($request->input('franchisee_id') == null)
            {
                $franchisees    =   Franchisee::all()->where('status','=',1);
                return view('batch.manageBatchesForm', compact('franchisees'));
            }
            else
            {
                $data['franchisee_id']  =   $request->input('franchisee_id');
                $validate   =   Validator::make($data,[
                    'franchisee_id'     =>  'required|exists:franchisees,id,status,1'
                ]);
                if(!$validate->fails())
                {
                    $batches        =   array();
                    $batch_obj      =   Batch::all()->where('franchisee_id','=',$data['franchisee_id']);
                    foreach ($batch_obj as $batch)
                    {
                        $batches[]  =   array(
                            'batch_id'      =>  $batch->id,
                            'batch_name'    =>  $batch->batch_name,
                            'batch_strength'=>  count($batch->level),
                            'start_date'    =>  $batch->start_date==null?"N/A":date('d-m-Y', strtotime($batch->start_date)),
                            'end_date'      =>  $batch->end_date==null?"N/A":date('d-m-Y', strtotime($batch->end_date)),
                        );
                    }
                    return view('batch.manageBatchesForm', compact('batches'));
                }
                else
                    return view('batch.manageBatchesForm');
            }
        }
        else
            return view('batch.manageBatches');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->ajax())
        {
            if($request->input('franchisee_id') == null)
            {
                $franchisees         =  Franchisee::select('id','franchisee_name','center_code')->where('status','=',1)->get();
                return view('batch.batchRegistrationForm',compact('franchisees'));
            }
            else
            {
                $data['franchisee_id']      =   $request->input('franchisee_id');
                $validate                   =   Validator::make($data,[
                    'franchisee_id'     =>  'exists:franchisees,id,status,1'
                ]);
                if($validate->fails())
                    return "NOT FOUND";
                else
                {
                    if($request->input('button_action') == null)
                    {
                        $faculties      =   FacultyAccount::all()->where('franchisee_id','=',$request->input('franchisee_id'));
                        $button_action  =   "add_batch";
                        return view('batch.batchRegistrationForm',compact('faculties','button_action'));

                    }
                    elseif  ($request->input('button_action') == "add_students")
                    {
                        $button_action  =   $request->input('button_action');
                        $students       =   array();
                        $levels         =   Level::where('franchisee_id','=',$request->input('franchisee_id'))
                                                   ->where('batch_id','=',null)
                                                   ->where(function ($query){
                                                       $query->where('status',0)
                                                             ->orWhere('status',1);
                                                   })
                                                   ->get();
                        $batches        =   Batch::all()->where('franchisee_id','=',$request->input('franchisee_id'));
                        foreach ($levels as $level)
                        {
                            $students[] =   array(
                                'level_id'      =>  $level->id,
                                'student_no'    =>  $level->student_no,
                                'student_name'  =>  $level->student->name,
                                'program_name'  =>  $level->course->program->program_name,
                                'course_name'   =>  $level->course->course_name
                            );
                        }
                        return view('batch.batchRegistrationForm',compact('students','faculties','button_action','batches'));
                    }
                    else
                        return "NOT FOUND";
                }
            }
        }
        else
        {
            return view('batch.batchRegistration');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->get('button_action') == "add_batch")
        {
            $validator      =   Validator::make($request->all(),
                [
                    'batch_name'        =>  'required|unique:batches,batch_name,NULL,id,franchisee_id,'.$request->get('franchisee_id'),
                    'franchisee_id'     =>  ['required',
                                                Rule::exists('franchisees','id')->where(function ($query){
                                                    $query->where("status",1);
                                                })
                                            ],
                    'faculty_account_id'=>  'required|exists:faculty_accounts,id,franchisee_id,'.$request->get('franchisee_id')
                ],[
                    'franchisee_id.*'               =>  'Franchisee selected is invalid',
                    'faculty_account_id.required'   =>  'Faculty field is required',
                    'faculty_account_id.exists'     =>  'Faculty selected is invalid'
                ]
            );
            if($validator->fails())
                return response()->json(['errors'=>$validator->errors()->all()]);
            else
            {
                $batch      =   Batch::create([
                    'batch_name'            =>  $request->get('batch_name'),
                    'franchisee_id'         =>  $request->input('franchisee_id'),
                    'faculty_account_id'    =>  $request->get('faculty_account_id'),
                ],[
                    'franchisee_id.*'       =>  "Please select a valid franchisee",
                    'course.*'              =>  "Please select a valid course"
                ]);
                return response()->json([
                    'success'   =>  $batch->batch_name." created successfully..!!"
                ]);
            }
        }
        elseif ($request->get('button_action') == "add_students")
        {
            $validator           =   Validator::make($request->all(), [
                'batch_id'      =>  'required|exists:batches,id,franchisee_id,'.$request->input('franchisee_id'),
                'franchisee_id' =>  'required|exists:franchisees,id,status,1',
//                'level_id'      =>  'required|exists:levels,id,status,0,franchisee_id,'.$request->input('franchisee_id')
                'level_id'      =>  ['required',
                                        Rule::exists('levels','id')->where(function ($query) use ($request){
                                            $query->where('franchisee_id',$request->input('franchisee_id'))
                                                  ->where('batch_id',null)
                                                  ->where('status',0)
                                                  ->orWhere('status',1);
                                        })
                                    ]
            ],[
                'batch_id.required'         =>  'Batch field is required.',
                'batch_id.exists'           =>  'Select a valid batch.',
                'franchisee_id.exists'      =>  'Franchisee selected is invalid.',
                'franchisee_id.required'    =>  'Franchisee field is required.',
                'level_id.required'         =>  'No students selected.',
                'level_id.exists'           =>  'Students selected is invalid.'
            ]);
            if($validator->fails())
                return response()->json(['errors'=>$validator->errors()->all()]);
            else
            {
                foreach ($request->get('level_id') as $value)
                {
                    $level              =   Level::findorfail($value);
                    $level->batch_id    =   $request->get('batch_id');
                    $level->save();
                }
                return response()->json(['success'=>'Student(s) admitted to the batch successfully..!!']);
            }
        }
        else
        {
            echo "failed";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $data                   =   $request->all();
        $data['batch_id']       =   $id;
        $validate               =   Validator::make($data,[
            'franchisee_id'     =>  'required|exists:franchisees,id,status,1',
            'batch_id'          =>  'required|exists:batches,id,franchisee_id,'.$data['franchisee_id'],
        ]);
        if($validate->fails())
            return view('errors.404');
        else
        {
            $batch      =   Batch::findorfail($id);
            $faculties  =   FacultyAccount::all()->where('franchisee_id','=',$data['franchisee_id']);
            if($request->ajax())
            {
                $button_action  =   $data['button_action'];
                return view('batch.batchEditForm', compact('batch','faculties','button_action'));
            }
            else
                return view('batch.batchEdit',compact('batch','faculties'));
        }

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
        $data               =   $request->all();
        $data['batch_id']   =   $id;
        if($request->input('button_action') == "update_batch")
        {
            $validate           =   Validator::make($data,[
                'franchisee_id'     =>  'required|exists:franchisees,id,status,1',
                'batch_name'        =>  'required|unique:batches,batch_name,'.$id.',id,franchisee_id,'.$data['franchisee_id'],
                'faculty_account_id'=>  'required|exists:faculty_accounts,id,status,1,franchisee_id,'.$data['franchisee_id'],
                'batch_id'          =>  'required|exists:batches,id,franchisee_id,'.$data['franchisee_id']
            ]);
            if($validate->fails())
                echo json_encode(['errors'  =>  $validate->errors()]);
            else
            {
                $batch      =   Batch::findorfail($id);
                $batch->update($request->all());
                echo json_encode(['success' =>  'Batch details updated successfully.']);
            }
        }
        elseif ($request->input('button_action') == "update_student_progress")
        {

        }
        else
            echo "NOT FOUND";
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data['batch_id']   =   $id;
        $validate      =   Validator::make($data, [
            'batch_id'      =>  'required|exists:batches,id|unique:levels,batch_id'
        ],[
            'batch_id.required' =>  'Select a valid batch.',
            'batch_id.exists'   =>  'Select a valid batch.',
            'batch_id.unique'   =>  'Batch cannot be deleted when having live students.'
        ]);
        if($validate->fails())
            return response()->json(['errors'   =>  $validate->errors()]);
        else
        {
            $batch              =   Batch::findorfail($id);
            $batch->delete();
            echo json_encode(["success" => "Batch removed successfully..!!"]);
        }
    }

}