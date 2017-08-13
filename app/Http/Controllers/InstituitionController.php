<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Instituition;
use App\InstituitionInstituteTypes;
use App\Http\Controllers\ConfigurationController;
use Illuminate\Support\Facades\Session;
use App\Users;
use App\Staff;

//use App\Instituition;


class InstituitionController extends Controller {

    public function newinstituitiion() {

        return view('newinstitution');
    }

    public function showinstitutions() {

        return view('institutions');
    }

    public function updateInstitution(Request $request) {

        $data = $request->all();



        $institute_code = $data['code'];
        $institutetypes = $data['institution_types'];
        $new = Instituition::where('code', $data['code'])
                ->get();
        $old_principal = $new[0]['principal_no'];

        $update = Instituition::where('code', $data['code'])
                ->first();
        $update->name = $data['institution_name'];
        $update->region = $data['region'];
        $update->district = $data['district'];
        $update->location = $data['location'];
        $update->longitude = $data['longitude'];
        $update->latitude = $data['latitude'];
        $update->date_of_establishment = $data['date_established'];
        if (!empty($data['principal'])) {
            $update->principal_no = $data['principal'];
        }

        $update->last_modifiedby = Session::get('id');

        $saved = $update->save();
        if (!$saved) {
            return '1';
        } else {
            $this->deleteInstituteInstitutionTypes($data['code']);
            $new = new ConfigurationController();
            $new->saveInstituteInstitutionTypes($data['code'], $institutetypes);
            if (!empty($data['principal'])) {
                $old_usercode = $this->getStaffCode($old_principal, $data['code']);
                $this->updateUser($old_usercode, 'staff');
                $new_usercode = $this->getStaffCode($data['principal'], $institute_code);
                $this->updateUser($new_usercode, 'principal');
            }
            return '0';
        }
    }

    public function deleteInstituteInstitutionTypes($institution_code) {


        $delete = InstituitionInstituteTypes::where('insitution_code', $institution_code)
                ->delete();

        if (!$delete) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getStaffCode($staffno, $institute_code) {


        $users = Staff::where([ ['instituition_code', '=', $institute_code], ['staff_no', '=', $staffno]])->first();

        return $users[0]['code'];
    }

    public function updateUser($usercode, $role) {

        $users = Users::where('usercode', $usercode)
                ->first();
        $users->role = $role;
        $users->save();
    }

}
