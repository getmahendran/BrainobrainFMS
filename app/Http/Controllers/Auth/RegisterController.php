<?php

namespace App\Http\Controllers\Auth;

use App\Master;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
//        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function next_master($str){
        if ( preg_match('/\d+/', $str, $reg)){
            $num = $reg[0]+1;
        }
        if($num < 10) return 'MKA0'.$num;
        else return 'MKA'.$num;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function edit($id){
        $usr = Master::findOrFail($id);
        return view('auth\registerEdit',compact('usr'));
    }

    public function update(Request $request, $id)
    {
        $usr = Master::findOrFail($id);
        $usr->update($request->all());
        $success = 'Successfully updated..!!';
        if($request->ajax())
            return json_encode($success);
        else
            return redirect(route('register.index'));
    }

    public function index(){
        $admins = Master::all()->where('status','=',1);
        return view('auth\allUsers', compact('admins'));
    }

    public function destroy($id){
        $usr = Master::findOrFail($id);
        $obj=User::where(["account_key"=>$id,"acc_type"=>1]);
        $usr->delete();
        $obj->delete();
        return redirect(route('register.index'));
    }

    protected function store(Request $request)
    {
        $data = $request->all();
        $this->validator($request->all())->validate();
        $usr = User::orderBy('created_at','desc')->where('acc_type', '1')->first();
        if($usr)
            $data['user_name'] = $this->next_master($usr->user_name);
        else
            $data['user_name'] = 'MKA01';
        $mast_obj = Master::create([
            'name'      => $data['name'],
            'user_name' => $data['user_name'],
            'mobile'    => $data['mobile'],
            'email'     => $data['email'],
        ]);
        $user = User::create([
            'user_name' => $mast_obj->user_name,
            'password' => Hash::make($data['password']),
            'acc_type' => 1,
            'status'    => 1
        ]);
        return back()->with(['message'    =>  'New Admin created with username '.$user->user_name]);
    }
    public function passwordReset(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'password' => 'required|string|min:6',
            'password-confirm'=>'same:password',
        ],[
            'password-confirm.same'=>'Passwords did not match'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->all()]);
        } else{
            $data               =   $request->all();
            $master             =   Master::find($id);
            $user_obj           =   User::all()->where('user_name','=',$master->user_name)->first();
            $user_obj->password =   Hash::make($data['password']);
            $user_obj->save();
            return response()->json(['success' => 'Password reset successfull..!! ']);
        }
    }
}
