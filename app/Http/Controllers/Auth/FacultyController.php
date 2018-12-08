<?php

namespace App\Http\Controllers\Auth;

use App\Course;
use App\Faculty;
use App\FacultyAccount;
use App\FacultyTraining;
use App\Franchisee;
use App\Program;
use App\User;
use App\Http\Controllers\Controller;
use function foo\func;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Status as Status;
use Illuminate\Validation\Rule;
use function PHPSTORM_META\elementType;

class FacultyController extends Controller
{
//    protected $redirectTo='/home';

    public function __construct(Status $status)
    {
        $this->status   =   $status->allFacultyStatus();
    }

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $output     =   array();
            $faculties  =   FacultyAccount::all();
            foreach ($faculties as $faculty)
            {
                $output[]   =   array(
                    'faculty_code'      =>  $faculty->faculty_code,
                    'name'              =>  $faculty->faculty->name,
                    'email'             =>  $faculty->faculty->email,
                    'mobile'            =>  $faculty->faculty->contact_no1,
                    'branch'            =>  $faculty->franchisee->franchisee_name,
                    'status'            =>  $this->status[$faculty->status],
                    'view'              =>  route('faculty.show',$faculty->id),
                    'edit'              =>  route('faculty.edit',$faculty->id),
                );
            }
            return datatables($output)->make(true);
        }
        else
        {
            return view('faculty.allFaculties',compact('faculties'));
        }

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
            if($request->get("button_action") == "add_new")
            {
                $franchisee_obj     =   Franchisee::all()->where("status","=",1);
                $faculty_status     =   $this->status;
                echo view('faculty.facultyAddNew',compact("franchisee_obj","faculty_status"));
            }
            elseif ($request->get("button_action") == "add_existing")
                echo view('faculty.facultyAddExisting');
        }
        else
            return view('faculty.facultyRegister');
    }

    public function registerExistingFaculty($id, Request $request)
    {
        $data                   =   $request->all();
        $data['faculty_id']     =   $id;
        $validate               =   Validator::make($data,[
            'faculty_id'        =>  [
                'exists:faculties,id',
                Rule::unique('faculty_accounts','faculty_id')->where(function ($query) use ($data){
                    $query->where("franchisee_id", $data['franchisee_id']);
                })
            ],
            'franchisee_id'     =>  'required|exists:franchisees,id,status,1'
        ],[
            'franchisee_id.required'    =>  'The franchisee branch field is required.',
            'franchisee_id.exists'      =>  'The franchisee branch selected is invalid.',
            'faculty_id.exists'         =>  'The faculty selected is invalid.',
            'faculty_id.unique'         =>  'The faculty already has a account in the selected franchisee.'
        ]);
        if($validate->fails())
            echo json_encode(["errors" =>  $validate->errors()]);
        else
        {
            $faculty                =   new FacultyAccount();
            $faculty_account    =   FacultyAccount::create([
                'faculty_id'        =>  $data['faculty_id'],
                'faculty_code'      =>  $faculty->getNextFacultyNumber($data['franchisee_id']),
                'franchisee_id'     =>  $data['franchisee_id'],
                'status'            =>  1
            ]);
            User::create([
                'user_name'     =>  $faculty_account->faculty_code,
                'password'      =>  Hash::make($faculty_account->faculty_code),
                'acc_type'      =>  3,
                'status'        =>  1
            ]);
            echo json_encode(["success" =>  "Faculty added to the franchisee with code ".$faculty_account->faculty_code]);
        }
    }

    public function searchFaculty(Request $request)
    {
        $output =   [];
        $faculty_accounts       =   FacultyAccount::where('faculty_code','LIKE','%'.$request->get('search').'%')->get();
        foreach ($faculty_accounts as $faculty_account)
        {
            $output[]   =   ["id"   =>  route('faculty.show',$faculty_account->faculty->id),"text" =>  $faculty_account->faculty_code." - ".$faculty_account->faculty->name];
        }
        return json_encode($output);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    protected function store(Request $request)
    {
        $faculty                =   new FacultyAccount();
        $data                   =   $request->all();
        $validate               =   $this->validator($request->all());
        if ($validate->fails())
            return response()->json(['errors'   =>  $validate->errors()]);
        else
        {
            $data['faculty_code']   =   $faculty->getNextFacultyNumber($data['franchisee_id']);
            $faculty_obj = Faculty::create([
                'name' => $data['name'],
                'father_name' => $data['father_name'],
                'permanent_address' => $data['permanent_address'],
                'contact_no1' => $data['contact_no1'],
                'contact_no2' => $data['contact_no2'],
                'email' => $data['email'],
                'qualification' => $data['qualification'],
                'occupation' => $data['occupation'],
                'dob' => $data['dob'],
                'family_income' => $data['family_income'],
                'gender' => $data['gender'],
                'married' => $data['married'],
                'languages_known' => $data['languages_known'],
                'hobbies' => $data['hobbies'],
                'special_at' => $data['special_at'],
                'past_experience' => $data['past_experience'],
            ]);

            if ($data['married']) {
                $faculty_obj->update([
                    'spouse_name' => $data['spouse_name'],
                    'spouse_dob' => $data['spouse_dob'],
                    'spouse_occupation' => $data['spouse_occupation'],
                    'spouse_qualification' => $data['spouse_qualification'],
                    'wedding_anniversary' => $data['wedding_anniversary'],
                    'child1_name' => $data['child1_name'],
                    'child1_dob' => $data['child1_dob'],
                    'child2_name' => $data['child2_name'],
                    'child2_dob' => $data['child2_dob'],
                    'child3_name' => $data['child3_name'],
                    'child3_dob' => $data['child3_dob'],
                ]);
            }
            $account_obj    = FacultyAccount::create([
                'faculty_id'        => $faculty_obj->id,
                'faculty_code'      => $data['faculty_code'],
                'franchisee_id'     => $data['franchisee_id'],
            ]);
            if ($file = $request->file('path')) {
                $name = "FAC".$faculty_obj->id . '.' . $file->clientExtension();
                $file->move('images', $name);
                $faculty_obj->update([
                    'path' => $name,
                ]);
            }
            else
                $faculty_obj->update([
                    'path' => "initial_profile_pic.jpg",
                ]);

            User::create([
                'user_name'     =>  $account_obj->faculty_code,
                'password'      =>  Hash::make($account_obj->faculty_code),
                'acc_type'      =>  3,
                'status'        =>  $data['status']
            ]);
            echo json_encode(['success' =>  "New faculty registered with code ".$account_obj->faculty_code]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Http\Response
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'path'                  =>      'sometimes|nullable|image|mimes:gif,jpeg,png|max:2048',
            'name'                  =>      'required|regex:/^[a-zA-Z ]+$/u|max:255',
            'father_name'           =>      'required|regex:/^[a-zA-Z ]+$/u|max:255',
            'permanent_address'     =>      'required|max:255',
            'contact_no1'           =>      'required|regex:/^[0-9]+$/u|max:10|min:10',
            'contact_no2'           =>      'sometimes|nullable|regex:/^[0-9]+$/u|max:10|min:10',
            'email'                 =>      'required|string|email|max:255',
            'qualification'         =>      'required|regex:/^[0-9a-zA-Z., ]+$/u|max:255',
            'occupation'            =>      'required|regex:/^[0-9a-zA-Z., ]+$/u',
            'dob'                   =>      'required|date',
            'family_income'         =>      'required|regex:/^[0-9]+$/u',
            'gender'                =>      'required|in:Female',
            'married'               =>      'required|in:0,1',
            'languages_known'       =>      'required',
            'hobbies'               =>      'required',
            'special_at'            =>      'required',
            'past_experience'       =>      'required',
            'spouse_name'           =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
            'spouse_dob'            =>      'required_if:married,==,1|date',
            'spouse_occupation'     =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
            'spouse_qualification'  =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
            'wedding_anniversary'   =>      'required_if:married,==,1|date',
            'child1_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
            'child1_dob'            =>      'sometimes|nullable|date',
            'child2_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
            'child2_dob'            =>      'sometimes|nullable|date',
            'child3_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
            'child3_dob'            =>      'sometimes|nullable|date',
            'franchisee_id'         =>      ['required',
                                            Rule::exists('franchisees','id')->where(function ($query){
                                                $query->where('status',1);
                                            }),
                                            ]
        ],[
            'contact_no1.required'                  =>      'The contact number 1 field is required',
            'contact_no1.regex'                     =>      'Contact number 1 is not valid',
            'contact_no1.min'                       =>      'Contact number 1 is not valid',
            'contact_no1.max'                       =>      'Contact number 1 is not valid',
            'contact_no2.regex'                     =>      'Contact number 2 is not valid',
            'contact_no2.min'                       =>      'Contact number 2 is not valid',
            'contact_no2.max'                       =>      'Contact number 2 is not valid',
            'path.image'                            =>      'Please select a image',
            'path.mime'                             =>      'Please select a image file of type GIF, JPEG or PNG',
            'path.max'                              =>      'Image size is too large select a image of size less than 2MB',
            'path.*'                                =>      'Image upload failed',
            'family_income.regex'                   =>      'The family income should be numeric',
            'married.in'                            =>      'The selected marrital status is invalid',
            'married.required'                      =>      'The marrital status is required',
            'spouse_name.required_if'               =>      'The spouse name field is required',
            'spouse_dob.required_if'                =>      'The spouse date of birth field is required',
            'spouse_occupation.required_if'         =>      'The spouse occupation field is required',
            'spouse_qualification.required_if'      =>      'The spouse qualification field is required',
            'wedding_anniversary.required_if'       =>      'The wedding anniversary field is required',
            'child1_name.regex'                     =>      'Child 1 name format is not valid',
            'child1_dob'                            =>      'Child 1 date of birth is not valid',
            'child2_name.regex'                     =>      'Child 2 name format is not valid',
            'child2_dob'                            =>      'Child 2 date of birth is not valid',
            'child3_name.regex'                     =>      'Child 3 name format is not valid',
            'child3_dob'                            =>      'Child 3 date of birth is not valid',
            'franchisee_id.*'                       =>      'Please select a valid Franchisee branch'
        ]);
    }


    public function show($id)
    {
        $faculty        =   Faculty::findorfail($id);
        $franchisees    =   Franchisee::all()->where('status','=',1);
        echo view('faculty.facultyAddExisting',compact('faculty','franchisees'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request ,$id)
    {
        $facultyAccount =   FacultyAccount::findorfail($id);
        $faculty        =   $facultyAccount->faculty;
        $status         =   $this->status;
        $programs       =   Program::all()->where('status','=',1);
        $output         =   array();
        if($request->ajax())
        {
            if($request->input("button_action") == "personal")
                echo view("faculty.facultyPersonalProfileEdit",compact('facultyAccount','faculty','status'));
            else if($request->input("button_action") == "training")
            {
                $programs       =   Program::all()->where('status','=',1);
                foreach ($programs as $program)
                {
                    $result     =   DB::select("SELECT *FROM faculty_trainings WHERE course_id in (SELECT courses.id FROM courses WHERE courses.program_id =".$program->id.")");
                    $courses    =   FacultyTraining::hydrate($result);
                    $output[]   =   array(
                        'program'       =>  $program,
                        'courses'       =>  $courses
                    );
                }
                echo view("faculty.facultyTrainingProfileEdit", compact('output'));
            }
        }
        else
            return view('faculty.facultyEdit', compact('facultyAccount','faculty','programs','courses'));
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
        if($request->input('button_action') == "update_profile")
        {
            $validate       =       Validator::make($request->all(), [
                'name'                  =>      'required|regex:/^[a-zA-Z ]+$/u|max:255',
                'father_name'           =>      'required|regex:/^[a-zA-Z ]+$/u|max:255',
                'permanent_address'     =>      'required|max:255',
                'contact_no1'           =>      'required|regex:/^[0-9]+$/u|max:10|min:10',
                'contact_no2'           =>      'sometimes|nullable|regex:/^[0-9]+$/u|max:10|min:10',
                'email'                 =>      'required|string|email|max:255',
                'qualification'         =>      'required|regex:/^[a-zA-Z. ]+$/u|max:255',
                'occupation'            =>      'required',
                'dob'                   =>      'required',
                'family_income'         =>      'required|regex:/^[0-9]+$/u',
                'married'               =>      'required|in:0,1',
                'languages_known'       =>      'required',
                'hobbies'               =>      'required',
                'gender'                =>      'required|in:Female',
                'special_at'            =>      'required',
                'past_experience'       =>      'required',
                'spouse_name'           =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
                'spouse_dob'            =>      'required_if:married,==,1|date',
                'spouse_occupation'     =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
                'spouse_qualification'  =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
                'wedding_anniversary'   =>      'required_if:married,==,1|date',
                'child1_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
                'child1_dob'            =>      'sometimes|nullable|date',
                'child2_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
                'child2_dob'            =>      'sometimes|nullable|date',
                'child3_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
                'child3_dob'            =>      'sometimes|nullable|date',
                'status'                =>      'required|integer|between:0,'.(count($this->status)-1),
            ],[
                'contact_no1.required'                  =>      'The contact number 1 field is required',
                'contact_no1.regex'                     =>      'Contact number 1 is not valid',
                'contact_no1.min'                       =>      'Contact number 1 is not valid',
                'contact_no1.max'                       =>      'Contact number 1 is not valid',
                'contact_no2.regex'                     =>      'Contact number 2 is not valid',
                'contact_no2.min'                       =>      'Contact number 2 is not valid',
                'contact_no2.max'                       =>      'Contact number 2 is not valid',
                'family_income.regex'                   =>      'The family income should be numeric',
                'married.in'                            =>      'The selected marrital status is invalid',
                'married.required'                      =>      'The marrital status is required',
                'spouse_name.required_if'               =>      'The spouse name field is required',
                'spouse_dob.required_if'                =>      'The spouse date of birth field is required',
                'spouse_occupation.required_if'         =>      'The spouse occupation field is required',
                'spouse_qualification.required_if'      =>      'The spouse qualification field is required',
                'wedding_anniversary.required_if'       =>      'The wedding anniversary field is required',
                'child1_name.regex'                     =>      'Child 1 name format is not valid',
                'child1_dob'                            =>      'Child 1 date of birth is not valid',
                'child2_name.regex'                     =>      'Child 2 name format is not valid',
                'child2_dob'                            =>      'Child 2 date of birth is not valid',
                'child3_name.regex'                     =>      'Child 3 name format is not valid',
                'child3_dob'                            =>      'Child 3 date of birth is not valid',
                'status.integer'                        =>      'The status field selected is invalid',
                'status.between'                        =>      'The status field selected is invalid.',
            ]);
            if($validate->fails())
                return response()->json(['errors' => $validate->errors()]);
            else
            {
                $data                               =       $request->all();
                $faculty_account                    =       FacultyAccount::findorfail($id);
                $faculty                            =       $faculty_account->faculty;
                $faculty_account->update([
                    'status'            =>      $data['status']
                ]);
                $faculty->update([
                    'name'              =>      $data['name'],
                    'father_name'       =>      $data['father_name'],
                    'permanent_address' =>      $data['permanent_address'],
                    'contact_no1'       =>      $data['contact_no1'],
                    'contact_no2'       =>      $data['contact_no2'],
                    'email'             =>      $data['email'],
                    'qualification'     =>      $data['qualification'],
                    'occupation'        =>      $data['occupation'],
                    'dob'               =>      $data['dob'],
                    'family_income'     =>      $data['family_income'],
                    'married'           =>      $data['married'],
                    'languages_known'   =>      $data['languages_known'],
                    'hobbies'           =>      $data['hobbies'],
                    'special_at'        =>      $data['special_at'],
                    'past_experience'   =>      $data['past_experience'],
                ]);
                if ($data['married']) {
                    $faculty->update([
                        'spouse_name'           =>      $data['spouse_name'],
                        'spouse_dob'            =>      $data['spouse_dob'],
                        'spouse_occupation'     =>      $data['spouse_occupation'],
                        'spouse_qualification'  =>      $data['spouse_qualification'],
                        'wedding_anniversary'   =>      $data['wedding_anniversary'],
                        'child1_name'           =>      $data['child1_name'],
                        'child1_dob'            =>      $data['child1_dob'],
                        'child2_name'           =>      $data['child2_name'],
                        'child2_dob'            =>      $data['child2_dob'],
                        'child3_name'           =>      $data['child3_name'],
                        'child3_dob'            =>      $data['child3_dob'],
                    ]);
                }
                return json_encode('Changes Saved..!!');
            }
        }
        elseif ($request->input('button_action') == 'update_training_profile')
        {
            $data                   =   $request->all();
            $data['faculty_id']     =   $id;
            $validate   =   Validator::make($data,[
                'faculty_id'        =>  ["required","exists:faculties,id",],
                'course_id'         =>  ["required",
                                        Rule::exists('courses','id')->where(function($query){
                                            $query->where('status',1);
                                        }),
                                        'unique:faculty_trainings,course_id,NULL,id,faculty_id,'.$data['faculty_id'],
//                                        function($attribute, $value, $fail) use ($data){
//                                            $course             =   Course::findorfail($data['course_id']);
//                                            $previous_course    =   Course::where('program_id',$course->program_id)
//                                                                    ->where('sequence_number',($course->sequence_number - 1))
//                                                                    ->where('status',1)
//                                                                    ->get();
//                                            return $fail("The course selected is invalid.".(count($previous_course)));
//                                            if(count($previous_course)>0)
//                                                if(!FacultyTraining::where('faculty_id',$data['faculty_id'])->where('course_id',$previous_course->first()->id)->get())
//                                                    return $fail("The course selected is invalid.".($course->sequence_number - 1));
//                                        }
                                        ],
            ],[
                'faculty_id.*'          =>  'Something went wrong.',
                'course_id.required'    =>  'Course field is required.',
                'course_id.exists'      =>  'Course selected is invalid',
                'course_id.unique'      =>  'Faculty has already trained for selected course.'
            ]);
            if($validate->fails())
            {
                echo json_encode(['errors'  =>  $validate->errors()]);
            }
            else
            {
                $faculty_training   =   FacultyTraining::create([
                    'course_id'     =>  $data['course_id'],
                    'faculty_id'    =>  $data['faculty_id']
                ]);
                echo json_encode(['success' =>  'Faculty training profile updated successfully']);
            }
        }
        elseif($request->input('button_action') == 'password_reset')
        {
            $validator = Validator::make($request->all(),[
                'password' => 'required|string|min:6',
                'password-confirm'=>'same:password',
            ],[
                'password-confirm.same'=>'Passwords did not match'
            ]);

            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()->all()]);
            } else {
                $data                       =   $request->all();
                $user_obj                   =   User::all()->where("user_name",'=',FacultyAccount::findorfail($id)->faculty_code)->first();
                $user_obj->password         =   Hash::make($data['password']);
                $user_obj->save();
                return response()->json(['success' => "Password reset successfull..!!"]);
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if($request->input('button_action') == "remove_trained_course")
        {
            $faculty_training   =   FacultyTraining::findorfail($id);
            $faculty_training->delete();
            echo json_encode(['success' =>  'Removed Successfully']);
        }
    }

    public function married_register()
    {
         echo view('married');
    }

    public function married_update($id)
    {
        $faculty        =   FacultyAccount::findorfail($id)->faculty;
        echo view('married',compact('faculty'));
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
            $faculty            = FacultyAccount::findorfail($id)->faculty;
            $name               = "FAC".$faculty->id . '.' . $file->clientExtension();
            $file->move('images', $name);
            $faculty->path      = $name;
            $faculty->save();
            return response()->json(['success' => 'Profile Image Updated Successfully..!!']);
        }
    }

    public function passwordReset(Request $request, $id){

    }
}