<?php

namespace App\Http\Controllers;

use App\BillBook;
use App\Course;
use App\Fee;
use App\FeeCollection;
use App\FeeType;
use App\Franchisee;
use App\Level;
use App\Program;
use App\Student;

use Illuminate\Http\Request;
use App\Source as Source;
use App\Status as Status;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Source $source, Status $status)
    {
        $this->status           =   $status;
        $this->sources          =   $source->allSources();
        $this->student_status   =   $status->allStudentStatus();
    }

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $output     = array();
            if($request->input("status")=="")
                $students   =   Student::all();
            else
                $students   =   Student::all()->where("status",$this->status->getStudentStatusIndex($request->input("status")));
            foreach ($students as $obj) {
                $output[] = array(
                    'student_no'    => Level::find($obj->level_id)->student_no,
                    'name'          => $obj['name'],
                    'course'        => Level::find($obj->level_id)->course->program->program_name.'-'. Level::find($obj->level_id)->course->course_name,
                    'mobile'        => $obj->father_mobile,
                    'email'         => $obj->father_email,
                    'status'        => $this->student_status[$obj['status']],
                    'fee_pay'       => route('fee_collect.pay',['level_id='.$obj->level_id]),
                    'view_student'  => route('student.edit', $obj['id']),
                );
            }
            return datatables($output)->make(true);
        }
        else
        {
            $franchisees    =   Franchisee::all();
            $student_status =   $this->student_status;
            return view('student.allStudents',compact('franchisees','student_status'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sourcess       =   $this->sources;
        $programs       =   Program::all()->where("status","=",1);
        $franchisees    =   Franchisee::all()->where("status","=",1);
        $admission_fees =   Fee::all()->where("status","=",1)->where("fee_type_id","=",1);
        return view('student.studentRegister',compact('sourcess','programs','franchisees','admission_fees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bill_book    =   BillBook::all()->where('status','1')->where('franchisee_id', $request->input('franchisee_id'))->first();
        $this->validate($request,[
            'path'                          =>  'sometimes|nullable|image|mimes:gif,jpeg,png|max:2048',
            'franchisee_id'                 =>  ['required',
                                                    Rule::exists("franchisees","id")->where(function($query){
                                                        $query->where("status",1);
                                                    })
                                                ],
            'program_id'                    =>  ['required',
                                                    Rule::exists("programs","id")->where(function($query){
                                                        $query->where("status",1);
                                                    })
                                                ],
            'physical_receipt_no'           =>  ['required', function($attribute, $value, $fail) use ($bill_book) {
                                                    if(empty($bill_book))
                                                        return $fail("Franchisee has no active bill book set a bill book as active or apply new");
                                                    else
                                                        if(!($value>=$bill_book->from&&$value<=$bill_book->till))
                                                            return $fail("Receipt number not in range.");
                                                },
                                                Rule::unique('fee_collections','physical_receipt_no')->where(function($query) use ($bill_book){
                                                    $query->where('bill_book_id',$bill_book->id);
                                                }),
                                                ],
            'physical_receipt_date'         =>  'required|date',
            'fee_id'                        =>  [
                                                "required",
                                                    Rule::exists('fees','id')->where(function ($query) {
                                                        $query->where('status', 1);
                                                    }),
                                                ],
            'name'                          =>  'required|regex:/^[a-zA-Z ]+$/u|max:255',
            'gender'                        =>  'required|in:Male,Female',
            'dob'                           =>  'required|date',
            'school_name'                   =>  'required|regex:/^[a-zA-Z ]+$/u',
            'standard'                      =>  'required|regex:/^[a-zA-Z0-9 ]+$/u',
            'monthly_income'                =>  'sometimes|nullable|numeric|min:1',
            'father_name'                   =>  'required|regex:/^[a-zA-Z ]+$/u',
            'mother_name'                   =>  'required|regex:/^[a-zA-Z ]+$/u',
            'father_occupation'             =>  'required|regex:/^[a-zA-Z ]+$/u',
            'mother_occupation'             =>  'required|regex:/^[a-zA-Z ]+$/u',
            'office_address'                =>  'sometimes|nullable',
            'residence_address'             =>  'required|',
            'father_mobile'                 =>  'required|regex:/^[0-9]+$/u|min:10|max:10',
            'mother_mobile'                 =>  'sometimes|nullable|regex:/^[0-9]+$/u|min:10|max:10',
            'father_email'                  =>  'required|email',
            'mother_email'                  =>  'sometimes|nullable|email',
            'sibling_1_name'                =>  'sometimes|nullable|regex:/^[a-zA-Z ]+$/u',
            'sibling_1_dob'                 =>  'sometimes|nullable|date',
            'sibling_2_name'                =>  'sometimes|nullable|regex:/^[a-zA-Z ]+$/u',
            'sibling_2_dob'                 =>  'sometimes|nullable|date',
            'source'                        =>  'required|numeric|min:0|max:7',
            'comments'                      =>  'required_if:source,==,6,7',
            'about_child'                   =>  'required'
        ],[
            'franchisee_id.*'               =>  'Please select a Franchisee branch.',
            'program_id.*'                  =>  'Please select a valid program.',
            'receipt_no.numeric'            =>  'Please enter a valid receipt no.',
            'receipt_no.min'                =>  'Please enter a valid receipt no.',
            'standard.regex'                =>  'The current grade of the school provided is invalid.',
            'father_mobile.regex'           =>  'The father mobile must be a valid mobile number.',
            'father_mobile.min'             =>  'The father mobile must be a valid mobile number.',
            'father_mobile.max'             =>  'The father mobile must be a valid mobile number.',
            'mother_mobile.regex'           =>  'The mother mobile must be a valid mobile number.',
            'mother_mobile.min'             =>  'The mother mobile must be a valid mobile number.',
            'mother_mobile.max'             =>  'The mother mobile must be a valid mobile number.',
            'comments.required_if'          =>  'This field is required.',
            'fee_id.*'                      =>  'Please select a valid fee.',
            'dob.required'                  =>  'The date of birth field is required.'
        ]);
        $data = $request->all();
        $course     =   Course::where("status","=",1)->where("program_id","=",$data['program_id'])->orderBy('sequence_number')->first();
        $data['status']         =   1;
        $data['level_id']       =   0;
        $student = Student::create([
            'name'              =>  $data['name'],
            'dob'               =>  $data['dob'],
            'gender'            =>  $data['gender'],
            'school_name'       =>  $data['school_name'],
            'standard'          =>  $data['standard'],
            'father_name'       =>  $data['father_name'],
            'mother_name'       =>  $data['mother_name'],
            'residence_address' =>  $data['residence_address'],
            'father_mobile'     =>  $data['father_mobile'],
            'father_email'      =>  $data['father_email'],
            'source'            =>  $data['source'],
            'level_id'          =>  $data['level_id'],
            'status'            =>  $data['status'],
            'about_child'       =>  $data['about_child'],
            'comments'          =>  $data['comments'],
            'father_occupation' =>  $data['father_occupation'],
            'office_address'    =>  $data['office_address'],
            'monthly_income'    =>  $data['monthly_income'],
            'mother_occupation' =>  $data['mother_occupation'],
            'mother_mobile'     =>  $data['mother_mobile'],
            'mother_email'      =>  $data['mother_email'],
            'sibling_1_name'    =>  $data['sibling_1_name'],
            'sibling_1_dob'     =>  $data['sibling_1_dob'],
            'sibling_2_name'    =>  $data['sibling_2_name'],
            'sibling_2_dob'     =>  $data['sibling_2_dob']
        ]);

        if($request->file('path'))
        {
            $file               =   $request->file('path');
            $name               =   "STU".$student->id.'.'.$file->clientExtension();
            $file->move('images',$name);
            $data['path']       =   $name;
        }
        else
            $data['path']       =   'initial_profile_pic.jpg';
        $student->update(['path'=>$data['path']]);

        $level      =   new Level();
        $level_obj = Level::create([
            'student_id'    =>  $student->id,
            'student_no'    =>  $level->getNextStudentNumber($data['franchisee_id']),
            'course_id'     =>  $course->id,
            'franchisee_id' =>  $data['franchisee_id'],
            'status'        =>  0
        ]);

            FeeCollection::create([
            'student_id'            =>  $student->id,
            'level_id'              =>  $level_obj->id,
            'fee_id'                =>  $data['fee_id'],
            'bill_book_id'          =>  BillBook::select('id')->where('status','1')->where('franchisee_id', $request->input('franchisee_id'))->first()->id,
            'physical_receipt_no'   =>  $data['physical_receipt_no'],
            'physical_receipt_date' =>  $data['physical_receipt_date'],
            'fee_type_id'           =>  1,
            'status'                =>  1,
            'royalty_status'        =>  0,
            'fee_description'       =>  "ADMISSION FEE"
        ]);
        for ($i=1;$i<=$course->duration;$i++)
        {
            FeeCollection::create([
                'student_id'        =>  $student->id,
                'level_id'          =>  $level_obj->id,
                'fee_description'   =>  strtoupper(\Common::ordinalValue($i)) . " MONTH MONTHLY FEE",
                'status'            =>  0,
                'fee_type_id'       =>  2,
                'royalty_status'    =>  0,
            ]);
        }

        FeeCollection::create([
            'student_id'        =>  $student->id,
            'level_id'          =>  $level_obj->id,
            'fee_description'   =>  "EXAM FEE",
            'status'            =>  0,
            'fee_type_id'       =>  3,
            'royalty_status'    =>  0,
        ]);
        $student->update(['level_id'=>$level_obj->id]);
        return back()->with(['message'  =>  'New student admitted to '.$level_obj->course->program->program_name.' - '.$level_obj->course->course_name.' with ID '.$level_obj->student_no]);
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
    public function edit($id, Request $request)
    {
        switch ($request->input('button_action'))
        {
            case "student_promotion_form":
                $data['id']     =   $id;
                $validate   =   Validator::make($data,[
                    'id'    =>  'required|exists:levels,id,status,3'
                ]);
                if(!$validate->fails())
                {
                    $student        =   Level::findorfail($id);
                    $current_course =   Course::findorfail($student->course->id);
                    $next_course    =   Course::where('program_id','=',$current_course->program_id)
                                                ->where('sequence_number','>',$current_course->sequence_number)
                                                ->first();
                    if($current_course->program_id  ==  1 && $next_course   ==  null)
                        $next_course    =   Course::findorfail(7);
                    return view('student.promoteStudentModal',compact('student','next_course'));
                }
                break;
            case "edit_profile":
                $student        =   Student::findorfail($id);
                $level          =   Level::findorfail($student->level_id);
                $sourcess       =   $this->sources;
                return view('student.studentEdit', compact('student','level', 'sourcess'));
                break;
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
        $data['level_id']   =   $id;
        $level  =   Level::findorfail($data['level_id']);
        if($request->input('button_action') == 'update_student_progress')
        {
            $validate   =   Validator::make($data,[
                'level_id'      =>  ['required',
                                    Rule::exists('levels','id')->where(function ($query){
                                        $query->where('status',0)
                                              ->orWhere('status',1)
                                              ->orWhere('status',3);
                                        })
                                    ],
                'marks'         =>  ['nullable','integer','min:0',
                                        function($attribute, $value, $fail) use ($data,$level) {
                                            if($level->start_date == null || $level->end_date == null)
                                                return $fail("The marks cannot be updated unless end date is declared.");
                                        },
                                        function($attribute, $value, $fail) use ($data,$level) {
                                            if($data['start_date'] == null || $data['end_date'] == null)
                                                return $fail("The end date field is required.");
                                        }
                                    ],
                'start_date'    =>  'nullable|date',
                'end_date'      =>  ['nullable','date',
                                        function($attribute, $value, $fail) use ($data,$level) {
                                            if($data['start_date'] == null)
                                                return $fail("The start date field is required.");
                                        },
                                        function($attribute, $value, $fail) use ($data,$level) {
                                            if($data['end_date'] <= $data['start_date'])
                                                return $fail("The end date is invalid.");
                                        }
                                    ]
            ],[
                'marks.integer' =>  'The marks field is invalid.',
                'marks.min'     =>  'The marks field is invalid.',
            ]);
            if($validate->fails())
                return response()->json(['errors'   =>  $validate->errors()]);
            else
            {
                $level      =   Level::findorfail($id);
                $level->update([
                    'start_date'    =>  $data['start_date'],
                    'end_date'      =>  $data['end_date'],
                    'marks'         =>  $data['marks'],
                ]);
                if($level->start_date   !=  null && $level->end_date    !=  null)
                    $level->update([
                       'status'     =>  3
                    ]);
                if($level->start_date   ==  null)
                    $level->update([
                        'status'     =>  0
                    ]);
                if($level->start_date   !=  null && $level->end_date    ==  null)
                    $level->update([
                        'status'     =>  1
                    ]);
                return response()->json(['success'  =>  'Student details updated successfully']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function getPrimaryCourse($program_id){
        $course=new Course();
        return $courses = $course->getPrimaryCourse($program_id);
    }

    public function updateProfileImage(Request $request, $id){
        $validate = Validator::make($request->all(),[
            'path'                  =>      'required|image|mimes:gif,jpeg,png|max:2048',
        ],[
            'path.required'         =>      'No image selected',
            'path.image'            =>      'Please select a image',
            'path.mime'             =>      'Please select a image file of type GIF, JPEG or PNG',
            'path.max'              =>      'Image size is too large select a image of size less than 2MB',
            'path.*'                =>      'Please select a image of size less than 2MB of type GIF, JPEG or PNG'
        ]);

        if($validate->fails()){
            return response()->json(['errors' => $validate->errors()->all()]);
        }else{
            $file               = $request->file('path');
            $student            = Student::findorfail($id);
            $name               = str_random(16) . '.' . $file->clientExtension();
            $file->move('images', $name);
            $student->path      = $name;
            $student->save();
            return response()->json(['success' => 'Profile Image Updated Successfully..!!']);
        }
    }
}