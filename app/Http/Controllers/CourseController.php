<?php

namespace App\Http\Controllers;

use App\Course;
use App\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Status as Status;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Status $status)
    {
        $this->courseStatus     =   $status->allCourseStatus();
        $this->programStatus    =   $status->allProgramStatus();
    }

    public function index($program_id, Request $request)
    {
        $data                   =   $request->all();
        $data['program_id']     =   $program_id;
        if($request->ajax())
        {
            $validate       =   Validator::make($data,[
                'program_id'        =>  [
                    'required',
                    Rule::exists('programs','id')->where(function($query){
                        $query->where('status',1);
                    })
                ]
            ]);
            if(!$validate->fails())
            {
                $courses    =   Course::all()->where('status','=',1)->where('program_id','=',$program_id);
                return response()->json(['courses'  =>  $courses]);
            }
        }
        else
        {
            $validate       =   Validator::make($data,[
                'program_id'        =>  [
                    'required',
                    Rule::exists('programs','id')->where(function($query){
                        $query->where('status',0);
                    })
                ]
            ]);
            if(!$validate->fails())
            {
                $program        =   Program::findorfail($program_id);
                $courseStatus   =   $this->courseStatus;
                return view("course.manageCourses",compact('program','courseStatus'));
            }
            else
                return view('errors.404');
        }
    }

    /**
     * Show the form fomanar creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $course_types           =   Program::all()->where("status","=",1);
        $courseStatus           =   $this->courseStatus;
        $programStatus          =   $this->programStatus;
        return view('course.addCourse', compact('course_types','courseStatus','programStatus'));
    }

    public function getCourses($program_id)
    {
        $courses        =   Course::select('id','course_name')->where('status',1)->where('program_id', $program_id)->get();
        if(count($courses)){
            echo json_encode($courses);
        }else{
            return response()->json(['errors'   =>  'Select a valid program']);
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
        if($request->isMethod('POST')){
            $this->validate(
                $request,[
                'program_id' => [
                        'required',
                        Rule::exists('programs','id')->where(function ($query) {
                            $query->where('status', 1);
                        }),
                    ],
                    'course_name'           =>  'required|unique:courses,course_name,NULL,id,status,1,program_id,'.$request->get("program_id").'|regex:/^[a-zA-Z0-9 ]+$/u',
                    'duration'              =>  'required|integer|min:1',
                    'sequence_number'       =>  'required|integer|min:1|unique:courses,sequence_number,NULL,id,status,1,program_id,'.$request->get("program_id"),
                ],
                [
                    'program_id.*'                  =>  'The program selected is invalid.',
                    'duration.*'                    =>  'The duration is invalid.',
                    'sequence_number.unique'        =>  'The course with this sequence number is already active.',
                ]
            );
            Course::create($request->all());
            return back()->with(['success'=>'New course created successfully']);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($program_id, $id)
    {
        return "2";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($program_id, $id)
    {
        $course     =   Course::findorfail($id);
        if($course->program_id == $program_id)
            echo json_encode(["course" =>  $course, "update-url"   =>  route("course.update",[$program_id,$id])]);
        else
            echo json_encode(["error"=>"Something went wrong..! Please try again"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $program_id, $id)
    {
        if(Program::findorfail($program_id)->status == 0)
        {
            $validate   =   Validator::make($request->all(),[
                'course_name'           =>  'required|unique:courses,course_name,'.$id.',id,status,1,program_id,'.$program_id.'|regex:/^[a-zA-Z0-9 ]+$/u',
                'duration'              =>  'required|integer|min:1',
                'sequence_number'       =>  'required|integer|min:1|unique:courses,sequence_number,'.$id.',id,status,1,program_id,'.$program_id,
            ],[
                'duration.*'            =>  "The duration is invalid."
            ]);
            if($validate->fails())
            {
                echo json_encode(["errors"  =>  $validate->errors()]);
            }
            else
            {
                $course     =   Course::findorfail($id);
                $course->update($request->all());
                echo json_encode(["success"  =>  "Course updated successfully"]);
            }
        }
        else
            echo json_encode(["failed"  =>  "Something went wrong, course cannot be updated. Please try again later"]);
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
}
