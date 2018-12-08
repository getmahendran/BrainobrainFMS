<?php

namespace App\Http\Controllers;

use App\Course;
use App\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Status as Status;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Status $status)
    {
        $this->status           =   $status;
        $this->courseStatus     =   $status->allCourseStatus();
        $this->programStatus    =   $status->allProgramStatus();
    }

    public function index(Request $request)
    {
        if($request->get("action") == "Dropdown_refresh")
        {
            $programs       =   Program::all()->where("status","=",1);
            $outputHtml     =   "<option disabled selected hidden>--- Select ---</option><option value='new'>New</option>";
            if(count($programs))
                foreach ($programs as $program)
                {
                    $outputHtml     .=   "<option target-url='".route("program.courses",$program->id)."' value='".$program->id."'>".$program->program_name."</option>";
                }

            echo json_encode($outputHtml);
        }
        else
        {
            $programs               =   Program::all();
            $status                 =   $this->status;
            $courseStatus           =   $this->courseStatus;
            $programStatus          =   $this->programStatus;
            return view('course.managePrograms',compact('programStatus','courseStatus','programs','status'));
        }
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
        $validate= Validator::make($request->all(), [
            'program_name' =>   'required|unique:programs,program_name,NULL,id',
            'status'       =>   'required|integer|min:0|max:'.(count($this->programStatus)-1),
        ]);
        if($validate->fails())
        {
            echo json_encode(['error' => $validate->errors()]);
        }
        else
        {
            Program::create([
                'program_name' =>   $request->get('program_name'),
                'status'       =>   $request->get('status')
            ]);
            echo json_encode(['success' => "New program added successfully"]);
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

        if($program    =   Program::findorfail($id))
        {
            $courses        =   $program->activeCourses;
            if(count($courses))
                echo json_encode(["success"     =>   $courses]);
            else
                echo json_encode(["failed"    =>  "Something went wrong. Please try again later"]);
        }
        else
        {
            echo json_encode(["error"   =>  "Something went wrong. Please try again later"]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $program        =   Program::findorfail($id);
        echo json_encode(['program'     =>  $program, 'update-url'      =>  route("program.update",$id)]);
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
        $validate= Validator::make($request->all(), [
            'program_name' =>   'required',
            'status'       =>   'required|integer|min:0|max:'.(count($this->programStatus)-1),
        ],[
            'status.max'       =>   'The status selected is invalid',
            'status.min'       =>   'The status selected is invalid',
        ]);
        if($validate->fails())
        {
            echo json_encode(['errors' => $validate->errors()]);
        }
        else
        {
            $program    =   Program::findorfail($id);
            $program->update($request->all());
            echo json_encode(['success' => "Program updated successfully"]);
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
        //
    }
}
