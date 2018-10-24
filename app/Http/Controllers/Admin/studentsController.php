<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Registration;
use App\Group;
use Session;
use Carbon\Carbon;
use Mail;
use App\Mail\Welcome;
class studentsController extends Controller
{

    // public function __construct(){
    //     $this->middleware("StudentPermission")->except("show_all_students","Show_blade_update_student");
    // }


    public function index(){
        return view("student-admin.student");
    }




    //return the student that has a group
    public function check_groupName(Request $request){
        //get all the group name from registration table
        $students = DB::table("registrations")
                        ->join("groups","registrations.group","=","groups.id")
                        ->join("users","registrations.student","=","users.id")
                        ->select("registrations.student")
                        ->where("users.role","=",0)
                        ->get();

        return  json_encode($students);

    }

    // return the groups of a student
    public function get_groupName(Request $request){
        $group = DB::table("registrations")
                ->join('users',"users.id","=","registrations.student")
                ->join("groups","groups.id","=","registrations.group")
                ->where("users.id","=",$request['id_student'])
                ->select("registrations.session","users.firstname","groups.name")
                ->get();
        return json_encode($group);
    }



    //return the form update view
    public function show_update($id_student){
      $student = User::find($id_student);

      //student has group
      $hasGroup = Registration::hasGroup($id_student);
      //get Session
      $session = Registration::get_session($id_student); //return the session or false
      //get group name
      $group_name = Registration::get_group_name($id_student);
      //get all group choice
      $groups_choice = Registration::getAllGroupOptions($id_student);

      //check if session == to the year ""universitaire""
      // exemple, session:2017/2018 ==> 2017
      //current year = 2018-1 = 2017
      $groupOpperations = false;
      if(Carbon::now()->year -1 == Registration::get_year_session($id_student)){
            $groupOpperations = true;
      }
      return view("student-admin.update")->with([
        "student" => $student,
        "hasGroup" => $hasGroup,
        "session" => $session,
        "group_name" => $group_name,
        "groups_choice" => $groups_choice,
        "group_opperations" => $groupOpperations,
      ]);

    }


    //save update
    public function save_updates(Request $request,$id_student){
      $this->validate($request, [
                'name' =>"required|String",
                'last_name'=>"required|string",
                'email' => 'required|string|email|max:255',
                "cin" => "required|digits:8|unique:users",
                "phone" => "required|string",
                "dob" => 'required|date'
            ]
        );

      $student = User::find($id_student);
      $student->firstname = $request->input("name");
      $student->lastname = $request->input("last_name");
      $student->email = $request->input("email");
      $student->cin = $request->input("cin");
      $student->birthdate = $request->input("dob");
      $student->phone = $request->input("phone");
      $student->updated_by = auth()->user()->id;
      $student->save();

      Session::flash("success_update","success");
      return redirect()->back();

    }



    //save group save_updated_group
    public function save_updated_group(Request $request){
         //get group id to that group name
         $group_id = Group::where("name",$request['groupe_name'])->get(['id'])->first();
         if($group_id){
           //search the student who was affected to that group id and in a session
           $s = Registration::where("student",$request['id_student'])->where("session",$request['session'])->get()->first();
           $s->group = $group_id['id'];
           $s->updated_by = auth()->user()->id;
           $s->save();
           return "done";
         }

         return "error";

    }


    public function show_add_student(){
        //get all availble group
        $groups = Group::get(["id",'name']);
        return view("student-admin.add_student")->with("groups",$groups);
    }








    /************************ vue js API ***********************/



    //show list of all students
    public function show_students(){
        $all_students = Registration::with("studentRecord:id,firstname,lastname,cin,birthdate,state,email,created_at,phone")
                        ->orderBy("created_at", "desc")
                        ->get();

        return response()->json(["data"=> $all_students, 200]);
    }

    // add student
    public function add_student(Request $request){
        //send email after save
        //state should be accepted

        $this->validate($request,[
            "firstname" => "required|string",
            "lastname" => "required|string",
            "email" => "required|string|email|unique:users",
            "birthdate" => "required|date",
            "cin" => "required|integer|digits:8|unique:users",
            "phone" => "required|string",
            "classe_id" => "required"
        ],
        [
           // required messages
          "birthdate.required" => "Date de naissance est obligatoire",
          "firstname.required" => "Le nom est obligatoire",
          "lastname.required" => "Le prenom est obligatoire",
          "cin.required" => "Le numero CIN est obligatoire",
          "phone.required" => "Le numero de telephone est obligatoire",
          'email.required' => "Adresse email est obligatoire",
          "classe_id.required" => "Choisir une classe pour cette étudiant",

          //string messages
          "firstname.string" => "Le nom est invalid",
          "lastname.string" => "Le prenom est invalid",
          "phone.string" => "Le numero de telephone est invalid", // ready to be changed

          //date messages
          "birthdate.date" => "Date de naissance est invalid",

          //integer,digits messages
          "cin.integer" => "Le numéro CIN doit etre  numérique",
          "cin.digits" => "Le numéro CIN doit etre composé de 8 chiffres",

          //exist
          "cin.unique" => "Cin est deja existe",

          //email exist
          "email.unique" => "Adresse email existe deja",

          //email format
          "email.email" => "Adresse email est invalide"
        ]);


        // add student to the users table
        $student = new User();
        $student->firstname = $request['firstname'];
        $student->lastname = $request['lastname'];
        $student->email = $request['email'];
        $student->birthdate = $request['birthdate'];
        $student->cin = $request['cin'];
        $student->phone = $request['phone'];
        $student->password = bcrypt($request['cin']);
        $student->state = "accepted";
        $student->role=0;

        //add student to registrations table



        if($student->save()){
            $registration = new Registration();
            $registration->student = $student->id;
            $registration->group = $request['classe_id'];
            $registration->session = (string)(Carbon::now()->year)."/".(string)(Carbon::now()->year+1);
            $registration->state = "accepted";

            if($registration->save()){
                return response()->json(["data"=>"success", 200]);
            }
        }

        //return response()->json(["data"=>[$student,$registration], 500]);

     }
}
