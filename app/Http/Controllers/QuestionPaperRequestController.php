<?php

namespace App\Http\Controllers;

use App\FeeCollection;
use App\Franchisee;
use App\Level;
use App\QuestionPaperRequest;
use App\QuestionPaperRequestDetail;
use App\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Array_;

class QuestionPaperRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Status $status)
    {
        $this->status   =   $status;
    }

    public function index()
    {
        $qp_requests        =   QuestionPaperRequest::all();
        $status             =   $this->status;
        return view("requests.questionPaper.manageRequests", compact('qp_requests','status'));
    }

    //Get Students for question paper request using franchisee_id
    public function getStudents($franchisee_id)
    {
        $output         =   array();
        $levels         =   DB::Table("levels")
                            ->whereRaw("levels.franchisee_id=".$franchisee_id." AND levels.id NOT IN (SELECT question_paper_request_details.level_id from question_paper_request_details)")
                            ->orderBy('id')
                            ->get();
        $levels         =   Level::hydrate($levels->toArray());

        if(count($levels)>0)
        {
            foreach ($levels as $level)
            {
                $output[]   =   array(
                    "select_level_id"   =>  "<input type='checkbox' value='".$level->id."' name='level_id[]'>",
                    "student_no"        =>  $level->student_no,
                    "name"              =>  $level->student->name,
                    "batch"             =>  $level->batch->batch_name,
                    "program"           =>  $level->course->program->program_name,
                    "course"            =>  $level->course->course_name,
                    "franchisee"        =>  $level->franchisee->branch_name
                );
            }
            echo json_encode($output);
        }
        else
        {
            echo json_encode([
                "error" =>  "No students found..!!"
            ]);
        }

//        echo json_encode("Hi");
    }
    //Ends here

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $franchisees    =   Franchisee::all()->where("status","=","1");
        return view("requests.questionPaper.newRequest", compact('levels', 'franchisees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate       =   Validator::make($request->all(),[
//            'franchisee_id'                 =>  'required|exists:franchisees,id',
        ]);
        if($validate->fails())
        {
            return response()->json();
        }
        else
        {
            $questionPaperRequestObject     =   QuestionPaperRequest::create([
                'franchisee_id'     =>  $request->get('franchisee_id'),
                'user_id'           =>  Auth::user()->id,
                'status'            =>  0
            ]);
            foreach ($request->get('level_id') as $level_id)
            {
                QuestionPaperRequestDetail::create([
                    'question_paper_request_id'     =>  $questionPaperRequestObject->id,
                    'level_id'                      =>  $level_id,
                ]);
            }
            return response()->json([
                'success'   =>  'Question paper request raised for the following list of students (Reference ID: <strong>QPR'.$questionPaperRequestObject->id.'</strong>)'
            ]);
        }
//        echo json_encode("cool");
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
    public function edit(Request $request, $id)
    {
        if($request->get("button_action") == "for_admin")
        {
            $fee_obj                    =   new FeeCollection;
            $qp_request_object          =   QuestionPaperRequest::findorfail($id);
            if($qp_request_object->status == 0)
            {
                $qp_request_object->status  =   1;
                $qp_request_object->save();
            }
            $details                    =   array();
            $franchisee                 =   $qp_request_object->franchisee->branch_name;
            $status                     =   [
                                                'status'        =>  $this->status->getQuestionPaperRequestStatus($qp_request_object->status, 0),
                                                'status-class'  =>  $this->status->getQuestionPaperRequestStatus($qp_request_object->status, 1)
                                            ];
            foreach ($qp_request_object->qpRequestDetail as $student)
            {
                $fee_details            =   "<i class='font-weight-bold'>Monthly Fee</i>";
                if(count($fee_obj->getFee($student->level->id,2)))
                {
                    $fee_details         .=  "<ul class='pl-4'>";
                    $count               =   1;
                    foreach ($fee_obj->getFee($student->level->id,2) as $fee)
                    {
                        $fee_details        .=   "<li>Receipt no ".$count.":&nbsp;&nbsp;&nbsp;".$fee->id."</li>";
                        $count++;
                    }
                    $fee_details            .=  "</ul>";
                }
                else
                    $fee_details        .=  "<p class='text-muted'>No Details available</p>";
                $fee_details            .=  "<i class='font-weight-bold'>Exam Fee</i>";
                if(count(($fee_obj->getFee($student->level->id,3))))
                {
                    $fee_details            .=  "<ul class='pl-4'>";
                    foreach ($fee_obj->getFee($student->level->id,3) as $fee)
                    {
                        $fee_details        .=  "<li>Receipt no:&nbsp;&nbsp;&nbsp;".$fee->id."</li>";
                    }
                    $fee_details            .=  "</ul>";
                }
                else
                    $fee_details        .=  "<p class='text-muted'>No Details available</p>";

                $details[]         =   array(
                    'student_no'        =>  $student->level->student_no,
                    'name'              =>  $student->level->student->name,
                    'course'            =>  $student->level->course->program->program_name ." - ".$student->level->course->course_name,
                    'fee_details'       =>  $fee_details,
                );
            }
            $allStatus               =  $this->status->allQuestionPaperRequestStatus();
            echo json_encode([
                'update_url'    =>  route("questionPaperRequest.update",$id),
                'franchisee'    =>  $franchisee,
                'details'       =>  $details,
                'status'        =>  $status,
                'all_status'    =>  $allStatus,
            ]);
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
        if ($request->get("button_action")  ==  "status_update")
        {
            $validate   =   Validator::make($request->all(),[
                "status"    =>  "required"
            ]);
            if($validate->fails())
                echo "error";
            else
            {
                $qp_request_obj             =   QuestionPaperRequest::findorfail($id);
                $qp_request_obj->status     =   $request->get("status");
                $qp_request_obj->save();
            }
            return response()->json("Request status updated successfully..!!");
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
