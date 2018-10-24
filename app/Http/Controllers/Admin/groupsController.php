<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Group;
use App\Registration;
class groupsController extends Controller
{



    //show the blade
    public function index(){

        return view("groupe.groupe");
    }

    //show form update
    public function showFormUpdate($id_group){
      $groups = Group::where("id",$id_group)->get(["id","name","stream"])->first();
      $stream = Group::getListStream($id_group);
      return view("groupe.update")->with(['group'=> $groups,"stream"=>$stream]);
    }

    //show form delete
    public function showFormDelete($id_group){
      $group = Group::find($id_group);
      return view("groupe.delete")->with('group', $group);
    }

    //show form add
    public function showFormAdd(){
      return view("groupe.add");
    }

    //back to groups
    public function back(){
        return redirect("group");
    }

  

    //check if the group exist in Registration table , if doesn't so we can delete it
    public function check_group(Request $request){
      $id_group = $request['id_group'];
      $result = Registration::where("group",$id_group)->count();
      if($result == 0){
        Group::find($id_group)->delete();
        return "done";
      }

      return "error";
    }



    /******************************** for vueJs api ********************************/


    public function get_students(Request $request){
      $id_group = $request['id_group'];

      $all = Registration::with("studentRecord:id,firstname,lastname")
          ->with("groupRecord:id,name")
          ->where("Registrations.group","=",$id_group)
          ->get();
      return response()->json(["data"=> $all],    200);
    }


    
    public function getAllGroups(){
        $groups = Group::orderBy("id", "desc")->get(["id","stream","created_at","name"]);
        return response()->json(["data"=>$groups], 200);
    }


    public function add_group(Request $request){
       $group = new Group();
       $group->name = strtoupper($request['name']);
       $group->stream = $request['stream'];
      if($group->save()){
        return response()->json(['data'=>$group,200]);
      }

      return  response()->json(["data"=>"error"], 500);
  }



  public function saveUpdateGroup(Request $request){
    $name = $request['name'];
    $stream = $request['stream'];
    $id_group = $request['id_group'];
    $group = Group::find($id_group);
    $group->name = $name;
    $group->stream = $stream;
    if($group->save()){
        return response()->json(["data"=>"true"], 200);
    }
    return  response()->json(["data"=>"false"], 500);

  }


}
