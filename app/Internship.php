<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Internship extends Model
{
    //j ai changé le nom des méthodes(nom_table_relation."Record") a cause de la confusion entre l attribut du modele qui va etre cree apres le chargement des donnees et le nom de la methode qui effectue la relation avec l'autre table
 public function companyFramer()
    {
        return $this->belongsTo('App\Manager','company_framer');
    }

    public function framerRecord()
    {
        return $this->belongsTo('App\User','framer');
    }

    public function registration()
    {
        return $this->belongsTo('App\Registration','student');
    }

    public function defense()
    {
        return $this->hasOne('App\Defense');
    }

        //retourne le createur de l'enregistrement dans la BD (created_by)
    public function adminCreator(){
        return $this->belongsTo('App\User','created_by');
    }

    //retourne le modificateur de l'enregistrement dans la BD (updated_by)
    public function adminUpdator(){
        return $this->belongsTo('App\User','updated_by');
    }

    public function specification(){
       return  $this->belongsTo('App\Specification','specifications');
    }

    public function requestsFraming(){
        return $this->hasMany('App\FramingRequest','internship');
    }
    /*********** AMINE BEJAOUI WORK ***********/
    //get all  "internships" of a student (reference by id_student)
    public static function getCurrentInternships($student_id){
        #1:
        $results = DB::table("internships")
                   ->join("users","users.id","=","internships.framer")
                   ->join("managers","managers.id","=","internships.company_framer")
                   ->join("companies","managers.company","=","companies.id")
                   ->join("registrations","registrations.id","=","internships.student")
                   ->join("users as students","students.id","=","registrations.student")
                   ->where("students.id","=",$student_id)
                   ->orderBy("id", "desc")
                   ->select("users.firstname","users.lastname","internships.start_date","end_date","managers.name","internships.state","internships.type","companies.name as company_name","internships.id")
                   ->get()->first();


        // $Intern = array();
        // foreach($results as  $res){
        //     $startYearIntern = explode("-",$res->start_date)[0];
        //     if($startYearIntern == Carbon::now()->year)
        //         array_push($Intern,$res);
        // }
        // return $Intern;
        if($results != null) {
            $startYearIntern = explode("-",$results->start_date)[0];
            if($startYearIntern == Carbon::now()->year){
            return array($results);
            }
        }
        return [];


    }

    //get details for one intership
    public static function getDetails($id_internship){
        $result = DB::table("internships")
                    ->join("users","users.id","=","internships.framer")
                    ->join("managers","managers.id","=","internships.company_framer")
                    ->join("companies","managers.company","=","companies.id")
                    ->where("internships.id","=",$id_internship)
                    ->select("users.firstname","users.lastname","internships.start_date","end_date","managers.name","internships.state","internships.type","companies.name as company_name","internships.id")
                    ->get()->first();

        return $result;
    }
}
