<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\District;
use App\Region;
use App\UnAssignedDistrictView;
use App\RegionDistrict;
use App\RegionDistrictView;
use App\Department;
use App\Instituition;
use App\InstitutionTypes;
use App\GradeTypes;
use App\InstitutionView;
use App\InstituitionInstituteTypes;
use App\ProfessionalBodies;
use App\InstituitionProfessionalBodies;
use GuzzleHttp;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Session;
use App\InstittutionsPrograms;

class ConfigurationController extends Controller {

  

    public function newdistrict() {
        $id = Session::get('id');


        if (empty($id)) {
            return redirect('logout');
        }
        return view('newdistrict');
    }

    public function showdistricts() {

        $permissions = Session::get('permissions');
        $id = Session::get('id');


        if (empty($id)) {
            return redirect('logout');
        }
        if (!in_array("VIEW_DISTRICTS", $permissions)) {
            return redirect('logout');
        }
        return view('districts');
    }

    public function showdepartments() {

        $permissions = Session::get('permissions');
        $id = Session::get('id');


        if (empty($id)) {
            return redirect('logout');
        }
        if (!in_array("VIEW_DEPARTMENTS", $permissions)) {
            return redirect('logout');
        }

        return view('department');
    }

    public function showGradeTypes() {

        $permissions = Session::get('permissions');
        $id = Session::get('id');


        if (empty($id)) {
            return redirect('logout');
        }
        if (!in_array("VIEW_GRADE_TYPES", $permissions)) {
            return redirect('logout');
        }
        return view('gradetypes');
    }

    public function showRegionDistricts() {

        $permissions = Session::get('permissions');
        $id = Session::get('id');


        if (empty($id)) {
            return redirect('logout');
        }
        if (!in_array("VIEW_REGION_DISTRICT", $permissions)) {
            return redirect('logout');
        }
        return view('regiondistricts');
    }

    public function showInstitutionTypes() {

        $permissions = Session::get('permissions');
        $id = Session::get('id');


        if (empty($id)) {
            return redirect('logout');
        }
        if (!in_array("VIEW_INSTITUTION_TYPES", $permissions)) {
            return redirect('logout');
        }
        return view('institutiontypes');
    }

    public function showProfessionalbodies() {
        $permissions = Session::get('permissions');
        $id = Session::get('id');


        if (empty($id)) {
            return redirect('logout');
        }
        if (!in_array("VIEW_PROFESSIONAL_BODIES", $permissions)) {
            return redirect('logout');
        }
        return view('professionalbodies');
    }

    public function getDistricts() {

        return District::where('active', 0)
                        ->get();
    }

    public function deleteDistrict($id) {


        $delete = District::find($id);

        $delete = $delete->delete();
        if (!$delete) {
            return '1';
        } else {
            return '0';
        }
    }

    public function updateDistrict(Request $request) {

        $data = $request->all();

        $id = $data['code'];
        $update = District::find($id);
        $update->name = $data['name'];
        $saved = $update->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function saveDistrict(Request $request) {

        $data = $request->all();
        $new = new District();

        $new->code = 'DST' . $this->generateuniqueCode(8);
        $new->name = $data['district'];
        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getRegions() {

        return Region::where('active', 0)
                        ->get();
    }

    public function getUnAssignedDistricts() {

        return UnAssignedDistrictView::where('active', 0)
                        ->get();
    }

    public function saveRegionDistricts(Request $request) {

        $data = $request->all();

        $districts = $data['districts'];
        $region = $data['region'];
        $dataSet = [];
        foreach ($districts as $district) {
            $dataSet[] = array(
                'code' => $this->generateuniqueCode(),
                'region_code' => $region,
                'district_code' => $district
            );
        }
        $save = RegionDistrict::insert($dataSet);
        if (!$save) {
            return '1';
        } else {
            return '0';
        }
    }

    public function deleteRegionDistrict($id) {


        $delete = RegionDistrict::find($id);

        $delete = $delete->delete();
        if (!$delete) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getRegionDistricts() {

        return RegionDistrictView::where('active', 0)
                        ->get();
    }

    public function getDepartments() {

        return App\Department::where('active', 0)
                        ->get();
    }

    public function saveDepartment(Request $request) {

        $data = $request->all();
        $new = new Department();

        $new->code = $this->generateuniqueCode(8);
        $new->name = $data['name'];
        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function updateDepartment(Request $request) {

        $data = $request->all();

        $id = $data['code'];
        $update = Department::find($id);
        $update->name = $data['name'];
        $saved = $update->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function deleteDepartment($id) {


        $delete = Department::find($id);

        $delete = $delete->delete();
        if (!$delete) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getInstituitionsTypes() {

        if (Session::get('role') == "principal") {
            $instituition_code = Session::get('institute');

            return InstittutionsPrograms::where('insitution_code', $instituition_code)
                            ->get();
        }

        return InstitutionTypes::where('active', 0)
                        ->get();
    }

    public function saveInstituitionType(Request $request) {

        $data = $request->all();

        $new = new InstitutionTypes();

        $new->code = $this->generateuniqueCode(8);
        $new->name = $data['name'];
        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function updateInstituitionType(Request $request) {

        $data = $request->all();

        $id = $data['code'];
        $update = InstitutionTypes::find($id);
        $update->name = $data['name'];
        $saved = $update->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function deleteInstituitionType($id) {


        $delete = InstitutionTypes::find($id);

        $delete = $delete->delete();
        if (!$delete) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getGradeTypes() {

        return GradeTypes::where('active', 0)
                        ->get();
    }

    public function deleteGradeType($id) {


        $delete = GradeTypes::find($id);
        $delete = $delete->delete();
        if (!$delete) {
            return '1';
        } else {
            return '0';
        }
    }

    public function updateGradeType(Request $request) {

        $data = $request->all();

        $id = $data['code'];
        $update = GradeTypes::find($id);
        $update->name = $data['name'];
        $saved = $update->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function saveGradeType(Request $request) {

        $data = $request->all();
        $new = new GradeTypes();

        $new->code = $this->generateuniqueCode(8);
        $new->name = $data['name'];
        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getInstituitions() {

        return InstitutionView::where('active', 0)
                        ->get();
    }

    public function getInstituitionDetail($id) {

        return InstitutionView::findOrFail($id);
    }

    public function getDistrictsOnRegion($id) {

        return RegionDistrictView::where('region_code', $id)
                        ->get();
    }

    public function saveInstitute(Request $request) {

        $data = $request->all();
        $new = new Instituition();


        $code_existence = $this->checkInstitutionCodeExistence($data['code']);
//return $code_existence;       
        if ($code_existence == '0') {
            $new->code = $data['code'];
            $new->name = $data['institution_name'];
            $new->region = $data['region'];
            $new->district = $data['district'];
            $new->location = $data['location'];
            $new->longitude = $data['longitude'];
            $new->latitude = $data['latitude'];
            $new->date_of_establishment = $data['date_established'];

            $save = $new->save();
            if (!$save) {
                return 'error';
            } else {
                $this->saveInstituteInstitutionTypes($data['code'], $data['institution_types']);
                $this->saveInstituteProfessionalBodies($data['code'], $data['professional_bodies']);
                return $data['code'];
            }
        } else {
            return 'exists';
        }



        //return $new->id;
    }

    public function saveInstituteInstitutionTypes($instcode, $instituitions) {

        $data = [];
        foreach ($instituitions as $item) {
            $data[] = array(
                'insitution_code' => $instcode,
                'insitute_type_code' => $item
            );
        }

        InstituitionInstituteTypes::insert($data); // Eloquent
    }

    public function saveInstituteProfessionalBodies($instcode, $professionalbodies) {

        $data = [];
        foreach ($professionalbodies as $item) {
            $data[] = array(
                'insitution_code' => $instcode,
                'professionalbody_code' => $item
            );
        }

        InstituitionProfessionalBodies::insert($data); // Eloquent
    }

    public function deleteInstituition($id) {


        $delete = Instituition::find($id);
        $delete->active = '1';
        $saved = $delete->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    private function generateuniqueCode($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function testdb() {
        $users = DB::select('select * from instituitions ');
//     try {
//        DB::connection()->getPdo();
//        if(DB::connection()->getDatabaseName()){
//            echo "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName();
//        }
//    } catch (\Exception $e) {
//        die("Could not connect to the database.  Please check your configuration.".$e->getMessage());
//    }
        echo \GuzzleHttp\json_encode($users);
        //  return view('user.index', ['users' => $users]);
    }

    public function getInstituitionInstituteTypes($id) {


        return InstituitionInstituteTypes::where('insitution_code', $id)->pluck('insitute_type_code')->toArray();
    }

    public function getProfessionalBodies() {

        return ProfessionalBodies::where('active', 0)
                        ->get();
    }

    public function saveProfessionalBody(Request $request) {

        $data = $request->all();


        $new = new ProfessionalBodies();
        $new->code = $this->generateuniqueCode(8);
        $new->name = $data['professional_body'];
        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function updateProfessionalBody(Request $request) {

        $data = $request->all();

        $id = $data['code'];
        $update = ProfessionalBodies::find($id);
        $update->name = $data['name'];
        $saved = $update->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function deleteProfessionalBody($id) {
        $delete = ProfessionalBodies::find($id);
        $delete->active = '1';
        $saved = $delete->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getInstituitionProfessionalBodies($id) {


        return InstituitionProfessionalBodies::where('insitution_code', $id)->pluck('professionalbody_code')->toArray();
    }

    public function checkInstitutionCodeExistence($code) {

        $check = Instituition::where('code', '=', $code)->count();
        if ($check == 0) {
            //it doesnt exist 
            return '0';
        } else {
            //its exists
            return '1';
        }
    }

}
