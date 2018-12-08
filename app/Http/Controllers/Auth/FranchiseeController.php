<?php

namespace App\Http\Controllers\Auth;

use App\Area;
use App\Franchisee;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Status;

class FranchiseeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $redirectTo='/home';

    public function __construct(Status $status)
    {
//        $this->middleware('auth');
        $this->status   =   $status->allFranchiseStatus();
    }

    public function index(Request $request)
    {

        if($request->ajax())
        {

        }
        else
        {
            $franchisee_status  =   $this->status;
            $franchisees        =   Franchisee::all();
            return view('franchisee.manageFranchisees',compact('franchisees','franchisee_status'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
            $areas              =   Area::all();
            $franchisee_status  =   $this->status;
            return view('franchisee.franchiseeRegister',compact('areas','franchisee_status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public function validator(array $data)
    {
        return Validator::make($data, [
            'center_code'           =>      'unique:franchisees,center_code',
            'path'                  =>      'sometimes|nullable|image|mimes:gif,jpeg,png|max:2048',
            'name'                  =>      'required|regex:/^[a-zA-Z ]+$/u|max:255',
            'father_name'           =>      'required|regex:/^[a-zA-Z ]+$/u|max:255',
            'permanent_address'     =>      'required|max:255',
            'contact_no1'           =>      'required|regex:/^[0-9]+$/u|max:10|min:10',
            'contact_no2'           =>      'sometimes|nullable|regex:/^[0-9]+$/u|max:10|min:10',
            'email'                 =>      'required|string|email|max:255',
            'qualification'         =>      'required|regex:/^[a-zA-Z. ]+$/u|max:255',
            'occupation'            =>      'required',
            'dob'                   =>      'required',
            'family_income'         =>      'required|integer|min:0',
            'gender'                =>      'required|in:Male,Female',
            'married'               =>      'required|in:0,1',
            'languages_known'       =>      'required',
            'hobbies'               =>      'required',
            'special_at'            =>      'required',
            'past_experience'       =>      'required',
            'franchisee_name'       =>      'required|max:255',
            'area_id'               =>      'required|exists:areas,id',
            'franchisee_address'    =>      'required',
            'spouse_name'           =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
            'spouse_dob'            =>      'sometimes|nullable|date',
            'spouse_occupation'     =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
            'wedding_anniversary'   =>      'sometimes|nullable|date',
            'child1_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
            'child1_dob'            =>      'sometimes|nullable|date',
            'child2_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
            'child2_dob'            =>      'sometimes|nullable|date',
            'child3_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
            'child3_dob'            =>      'sometimes|nullable|date',
            'status'                =>      'required|integer|between:0,'.(count($this->status)-1)
        ],[
            'contact_no1.required'                  =>      'The contact number 1 field is required',
            'contact_no1.regex'                     =>      'Enter a valid mobile number',
            'contact_no1.min'                       =>      'Enter a valid mobile number',
            'contact_no1.max'                       =>      'Enter a valid mobile number',
            'contact_no2.regex'                     =>      'Enter a valid mobile number',
            'contact_no2.min'                       =>      'Enter a valid mobile number',
            'contact_no2.max'                       =>      'Enter a valid mobile number',
            'path.image'                            =>      'Please select a image',
            'path.mime'                             =>      'Please select a image file of type GIF, JPEG or PNG',
            'path.max'                              =>      'Image size is too large select a image of size less than 2MB',
            'path.*'                                =>      'Image upload failed',
            'family_income.min'                     =>      'The family income field is invalid',
            'family_income.integer'                 =>      'The family income field is invalid',
            'married.in'                            =>      'The selected marrital status is invalid',
            'married.required'                      =>      'The marrital status is required',
            'spouse_name.required_if'               =>      'The spouse name field is required',
            'spouse_dob.required_if'                =>      'The spouse date of birth field is required',
            'spouse_occupation.required_if'         =>      'The spouse qualification field is required',
            'spouse_qualification.required_if'      =>      'The spouse qualification field is required',
            'wedding_anniversary.required_if'       =>      'The wedding anniversary field is required',
            'child1_name.regex'                     =>      'Child 1 name format is not valid',
            'child1_dob'                            =>      'Child 1 date of birth is not valid',
            'child2_name.regex'                     =>      'Child 2 name format is not valid',
            'child2_dob'                            =>      'Child 2 date of birth is not valid',
            'child3_name.regex'                     =>      'Child 3 name format is not valid',
            'child3_dob'                            =>      'Child 3 date of birth is not valid',
        ]);
    }
    protected function store(Request $request)
    {
        $data = $request->all();
        $data['center_code'] = $this->next_franchisee();
        $this->validator($request->all())->validate();
        $franchisee_obj = Franchisee::create([
            'center_code'                   => $data['center_code'],
            'name'                          => $data['name'],
            'father_name'                   => $data['father_name'],
            'permanent_address'             => $data['permanent_address'],
            'contact_no1'                   => $data['contact_no1'],
            'contact_no2'                   => $data['contact_no2'],
            'email'                         => $data['email'],
            'qualification'                 => $data['qualification'],
            'occupation'                    => $data['occupation'],
            'dob'                           => $data['dob'],
            'family_income'                 => $data['family_income'],
            'gender'                        => $data['gender'],
            'married'                       => $data['married'],
            'languages_known'               => $data['languages_known'],
            'hobbies'                       => $data['hobbies'],
            'special_at'                    => $data['special_at'],
            'past_experience'               => $data['past_experience'],
            'area_id'                       => $data['area_id'],
            'franchisee_name'               => $data['franchisee_name'],
            'franchisee_address'            => $data['franchisee_address'],
            'status'                        => $data['status']
        ]);
        if($data['married'])
        {
            $franchisee_obj->update([
                'spouse_name'               => $data['spouse_name'],
                'spouse_dob'                => $data['spouse_dob'],
                'spouse_occupation'         => $data['spouse_occupation'],
                'spouse_qualification'      => $data['spouse_qualification'],
                'wedding_anniversary'       => $data['wedding_anniversary'],
                'child1_name'               => $data['child1_name'],
                'child1_dob'                => $data['child1_dob'],
                'child2_name'               => $data['child2_name'],
                'child2_dob'                => $data['child2_dob'],
                'child3_name'               => $data['child3_name'],
                'child3_dob'                => $data['child3_dob'],
            ]);
        }
        $user = User::create([
            'user_name'                     => $franchisee_obj->center_code,
            'password'                      => Hash::make($franchisee_obj->center_code),
            'acc_type'                      => 2,
            'status'                        => $data["status"]
        ]);
        $franchisee_obj->update(['user_id'  => $user->id]);
        if($file = $request->file('path'))
        {
            $name                           = $user->user_name.'.'.$file->clientExtension();
            $file->move('images',$name);
            $franchisee_obj->update(['path' => $name]);
        }
        else
            $franchisee_obj->update(['path' => 'initial_profile_pic.jpg']);
        return back()->with(['message'    =>  'New Franchisee created with Center code '.$user->user_name]);
    }

    public static function next_franchisee(){
        $franchisee_obj_recent      =   Franchisee::orderBy("id",'desc')->first();
        if($franchisee_obj_recent)
        {
            $num = substr($franchisee_obj_recent->id,-2)+1;
            if($num<10)
                return "KA0".$num;
            else
                return "KA".$num;
        }
        else
            return "KA01";
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
    public function edit($id)
    {
        $franchisee     =       Franchisee::findorfail($id);
        $areas          =       Area::all();
        $franchisee_status  =   $this->status;
        return view('franchisee.franchiseeEdit',compact('areas','franchisee','franchisee_status'));
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
        if($request->get("button_action") == "update_details")
        {
            $validate = Validator::make($request->all(),[
                'name'                  =>      'required|regex:/^[a-zA-Z ]+$/u|max:255',
                'father_name'           =>      'required|regex:/^[a-zA-Z ]+$/u|max:255',
                'permanent_address'     =>      'required|max:255',
                'contact_no1'           =>      'required|regex:/^[0-9]+$/u|max:10|min:10',
                'contact_no2'           =>      'sometimes|nullable|regex:/^[0-9]+$/u|max:10|min:10',
                'email'                 =>      'required|string|email|max:255',
                'qualification'         =>      'required|regex:/^[a-zA-Z. ]+$/u',
                'occupation'            =>      'required',
                'dob'                   =>      'required',
                'family_income'         =>      'required|integer|min:1',
                'path'                  =>      'sometimes|nullable|image|mimes:gif,jpeg,png|max:2048',
                'gender'                =>      'required|in:Male,Female',
                'married'               =>      'required|in:0,1',
                'languages_known'       =>      'required',
                'hobbies'               =>      'required',
                'special_at'            =>      'required',
                'past_experience'       =>      'required',
                'franchisee_name'       =>      'required|max:255',
                'area_id'               =>      'required',
                'franchisee_address'    =>      'required',
                'spouse_name'           =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
//            'spouse_dob'            =>      'required_if:married,==,1|date',
                'spouse_dob'            =>      'sometimes|nullable|date',
                'spouse_occupation'     =>      'required_if:married,==,1|regex:/^[a-zA-Z ]+$/u|max:255',
//                'spouse_occupation'     =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
            'spouse_qualification'  =>      'required_if:married,==,1|regex:/^[a-zA-Z. ]+$/u',
//            'wedding_anniversary'   =>      'required_if:married,==,1|date',
//                'spouse_qualification'  =>      'sometimes|nullable|regex:/^[a-zA-Z. ]+$/u',
                'wedding_anniversary'   =>      'sometimes|nullable|date',
                'child1_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
                'child1_dob'            =>      'sometimes|nullable|date',
                'child2_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
                'child2_dob'            =>      'sometimes|nullable|date',
                'child3_name'           =>      'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|max:255',
                'child3_dob'            =>      'sometimes|nullable|date',
                'status'                =>      'required|in:0,'.(count($this->status)-1)
            ],[
                'contact_no1.required'                  =>      'The contact number 1 field is required',
                'contact_no1.regex'                     =>      'Enter a valid mobile number',
                'contact_no1.min'                       =>      'Enter a valid mobile number',
                'contact_no1.max'                       =>      'Enter a valid mobile number',
                'contact_no2.regex'                     =>      'Enter a valid mobile number',
                'contact_no2.min'                       =>      'Enter a valid mobile number',
                'contact_no2.max'                       =>      'Enter a valid mobile number',
                'path.image'                            =>      'Please select a image',
                'path.mime'                             =>      'Please select a image file of type GIF, JPEG or PNG',
                'path.max'                              =>      'Image size is too large select a image of size less than 2MB',
                'path.*'                                =>      'Image upload failed',
                'family_income.min'                     =>      'The family income field is invalid',
                'family_income.integer'                 =>      'The family income field is invalid',
                'married.in'                            =>      'The selected marrital status is invalid',
                'married.required'                      =>      'The marrital status is required',
                'spouse_name.required_if'               =>      'The spouse name field is required',
//            'spouse_dob.required_if'                =>      'The spouse date of birth field is required',
                'spouse_occupation.required_if'         =>      'The spouse occupation field is required',
                'spouse_qualification.required_if'      =>      'The spouse qualification field is required',
//                'wedding_anniversary.required_if'       =>      'The wedding anniversary field is required',
                'child1_name.regex'                     =>      'Child 1 name format is not valid',
                'child1_dob'                            =>      'Child 1 date of birth is not valid',
                'child2_name.regex'                     =>      'Child 2 name format is not valid',
                'child2_dob'                            =>      'Child 2 date of birth is not valid',
                'child3_name.regex'                     =>      'Child 3 name format is not valid',
                'child3_dob'                            =>      'Child 3 date of birth is not valid',
            ]);
            if($validate->fails()){
                return response()->json(['errors' => $validate->errors()]);
            }else{
                $data                               =       $request->all();
                $franchisee                         =       Franchisee::findorfail($id);
                $franchisee->update([
                    'name'                          => $data['name'],
                    'father_name'                   => $data['father_name'],
                    'permanent_address'             => $data['permanent_address'],
                    'contact_no1'                   => $data['contact_no1'],
                    'contact_no2'                   => $data['contact_no2'],
                    'email'                         => $data['email'],
                    'qualification'                 => $data['qualification'],
                    'occupation'                    => $data['occupation'],
                    'dob'                           => $data['dob'],
                    'family_income'                 => $data['family_income'],
                    'gender'                        => $data['gender'],
                    'married'                       => $data['married'],
                    'languages_known'               => $data['languages_known'],
                    'hobbies'                       => $data['hobbies'],
                    'special_at'                    => $data['special_at'],
                    'past_experience'               => $data['past_experience'],
                    'area_id'                       => $data['area_id'],
                    'franchisee_name'               => $data['franchisee_name'],
                    'franchisee_address'            => $data['franchisee_address'],
                    'status'                        => $data['status']
                ]);
                $user_obj           =   User::where("user_name",$franchisee->center_code)->first();
                $user_obj->status   =   $data['status'];
                $user_obj->save();
                if($data['married']){
                    $franchisee->update([
                        'spouse_name'               => $data['spouse_name'],
                        'spouse_dob'                => $data['spouse_dob'],
                        'spouse_occupation'         => $data['spouse_occupation'],
                        'spouse_qualification'      => $data['spouse_qualification'],
                        'wedding_anniversary'       => $data['wedding_anniversary'],
                        'child1_name'               => $data['child1_name'],
                        'child1_dob'                => $data['child1_dob'],
                        'child2_name'               => $data['child2_name'],
                        'child2_dob'                => $data['child2_dob'],
                        'child3_name'               => $data['child3_name'],
                        'child3_dob'                => $data['child3_dob'],
                    ]);
                }
                return json_encode('Changes Saved..!!');
            }
        }
        elseif ($request->get("button_action") == "update_profile_image")
        {
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
                $file           = $request->file('path');
                $franchisee     = Franchisee::findorfail($id);
                $name           = $franchisee->center_code . '.' . $file->clientExtension();
                $file->move('images', $name);
                $franchisee->update(['path' => $name]);
                return response()->json(['success' => 'Profile Image Updated Successfully..!!']);
            }
        }
        elseif ($request->get("button_action") == "update_password")
        {
            $validator = Validator::make($request->all(),[
                'password' => 'required|string|min:6',
                'password-confirm'=>'same:password',
            ],[
                'password-confirm.same'=>'Passwords did not match'
            ]);

            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()->all()]);
            } else{
                $data = $request->all();
                $franchisee_obj     =   new Franchisee();
                $user_obj     =   $franchisee_obj->getUser($id);
                $user_obj->password = Hash::make($data['password']);
                $user_obj->save();
                return response()->json(['success' => 'Password reset successfull..!!']);
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

    public function married_update($id)
    {
        $franchisee =   Franchisee::findorfail($id);
        echo view('married',compact('franchisee'));
    }

    public function married_register()
    {
        echo view('married');
    }
}
