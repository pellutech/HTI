<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\StudentAcademic;
use App\StudentBank;
use App\StudentEnrollment;
use App\Users;
use App\StudentView;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\StaffController;

class StudentController extends Controller {

    public function showstudent() {
        $id = Session::get('id');

        $permissions = Session::get('permissions');

        if (!in_array("CREATE_STUDENT	", $permissions)) {
            return redirect('logout');
        }


        if (empty($id)) {
            return redirect('logout');
        }
        return view('newstudentview');
    }

    public function getStudent() {

        $permissions = Session::get('permissions');

        if (!in_array("VIEW_STUDENTS	", $permissions)) {
            return redirect('logout');
        }


        if (Session::get('role') == "principal") {
            $instituition_code = Session::get('institute');
            return StudentView::where([ ['active', '=', 0], ['instituition_code', '=', $instituition_code]])->get();
        }
        return StudentView::where('active', 0)
                        ->get();
    }

    public function showstudentdetails($studentcode) {

        $studentinfo = $this->getStudentsDetails($studentcode);
        return view('studentdetails')->with('studentinfo', $studentinfo);
    }

    public function getStudentsDetails($studentcode) {
        // echo $studentcode;
        return StudentView::where('code', $studentcode)
                        ->get();
    }

    public function showallstudents() {

        $id = Session::get('id');

        if (empty($id)) {
            return redirect('logout');
        }
        return view('allstudents');
    }

    public function saveStudentInfo(Request $request) {


        $data = $request->all();
        $response = array();

        $new = new Student();
        if (Session::get('role') == "principal") {
            $new->instituition_code = Session::get('institute');
        } else {
            $new->instituition_code = $data['institute_code'];
        }

        $exist = new StaffController();
        $email_existence = $exist->checkEmailExistence($data['email']);
        if ($email_existence == '0') {
            $student_initials = $data['firstname'][0] . $data['middlename'][0] . $data['lastname'][0];

            $new->code = $this->generateuniqueCode(8);
            $new->student_no = '';
            $new->firstname = $data['firstname'];
            $new->middlename = $data['middlename'];
            $new->surname = $data['lastname'];
            $new->gender = $data['gender'];
            $new->dob = $data['dateofbirth'];
            $new->place_of_birth = $data['placeofbirth'];
            $new->region = $data['region'];
            $new->district = $data['district'];
            $new->nationality = $data['nationality'];
            $new->address = $data['address'];
            $new->suburb = $data['suburb'];
            $new->postcode = $data['postal_address'];
            $new->contact_no = $data['contactno'];
            $new->email_address = $data['email'];
            $new->marital_status = $data['marital_status'];
            $new->identification_number = $data['identification_number'];
            $new->identification_type = $data['identification_type'];
            $new->next_of_kin = $data['nextofkin'];
            $new->relationship = $data['kin_relationship'];
            $new->kin_address = $data['kin_address'];
            $new->kin_email = $data['kin_email'];
            $new->kin_contactno = $data['kin_contactno'];
            $new->kin_postcode = $data['kin_postal'];
            $new->guardian_firstname = $data['guardian_firstname'];
            $new->guardian_surname = $data['guardian_lastname'];
            $new->guardian_address = $data['guardian_address'];
            $new->guardian_contact = $data['guardian_contact'];
            $new->guardian_emailaddress = $data['guardian_email'];
            $new->guardian_postal_address = $data['guardian_postcode'];
            $new->createdby = Session::get('id');
            $saved = $new->save();
            $inserted_id = $new->id;

            $student_no = 'STU' . str_pad("$inserted_id", 5, '0', STR_PAD_LEFT) . $student_initials;
            $this->updateStudentNo($student_no, $inserted_id);
            if (!$saved) {
                $response['success'] = '1';
                $response['message'] = 'Couldnt save';
                return json_encode($response);
            } else {
                $this->saveStudentAcademicDetails($student_no, $data);
                $this->saveStudentEnrollmentDetails($student_no, $data);
                $this->saveStudentBankDetails($student_no, $data);


                $this->saveUsers($new->code, $data, 'student');
                $response['success'] = '0';
                $response['message'] = 'Student Information Saved Successfully';
                return json_encode($response);
            }
        }

        if ($email_existence == "1") {
            $response['success'] = '1';
            $response['message'] = 'Email already exists';
            return json_encode($response);
        }
    }

    private function updateStudentNo($studentno, $id) {


        $update = Student::find($id);
        $update->student_no = $studentno;
        $saved = $update->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    private function saveStudentAcademicDetails($code, $data) {


        $new = new StudentAcademic();
        if (Session::get('role') == "principal") {
            $new->instituition_code = Session::get('institute');
        } else {
            $new->instituition_code = $data['institute_code'];
        }
        $new->student_no = $code;
        $new->last_instituition = $data['last_institution_completed'];
        $new->completion_year = $data['completion_year'];
        $new->examination_type = $data['examination_type'];
        $new->aggregate = $data['aggregate'];
        $new->program = $data['program'];

        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    private function saveStudentBankDetails($code, $data) {


        $new = new StudentBank();
        if (Session::get('role') == "principal") {
            $new->instituition_code = Session::get('institute');
        } else {
            $new->instituition_code = $data['institute_code'];
        }
        $new->student_no = $code;
        $new->bank_name = $data['bank_name'];
        $new->account_name = $data['account_name'];
        $new->account_no = $data['account_number'];
        $new->branch = $data['branch'];
        $new->tin = $data['tin'];
        $new->ssniit = $data['SNNIT'];
        $new->superannutation_name = $data['superannutation_fund'];



        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {

            return '0';
        }
    }

    private function saveStudentEnrollmentDetails($code, $data) {


        $new = new StudentEnrollment();
        if (Session::get('role') == "principal") {
            $new->instituition_code = Session::get('institute');
        } else {
            $new->instituition_code = $data['institute_code'];
        }
        $new->student_no = $code;
        $new->program = $data['program'];
        $new->admission_year = $data['admission_year'];
        $new->professional_body = $data['professional_body'];
        $new->sitting_year = $data['year_of_seating'];
        $new->index_no = $data['indexno'];
        $new->certificate_type = $data['certificatype'];



        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {

            return '0';
        }
    }

    private function saveUsers($code, $data, $type) {

        $new = new Users();
        if (Session::get('role') == "principal") {
            $new->instituition_code = Session::get('institute');
        } else {
            $new->instituition_code = $data['institute_code'];
        }
        $new->usercode = $code;
        $new->firstname = $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'];
        $new->email = $data['email'];
        $new->password = md5('123456');
        $new->role = $type;
        $new->usergroup = "null";
        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    public function updateStudentInfo(Request $request) {


        $data = $request->all();
        print_r($data);


        $new = Student::where('code', $data['code'])
                ->first();
        if (!empty($data['institute_code'])) {
            $new->instituition_code = $data['institute_code'];
        }


        $new->firstname = $data['firstname'];
        $new->middlename = $data['middlename'];
        $new->surname = $data['lastname'];
        $new->gender = $data['gender'];
        $new->dob = $data['dateofbirth'];
        $new->place_of_birth = $data['placeofbirth'];
        if (!empty($data['region'])) {
            $new->region = $data['region'];
        }
        if (!empty($data['district'])) {
            $new->district = $data['district'];
        }

        $new->nationality = $data['nationality'];
        $new->address = $data['address'];
        $new->suburb = $data['suburb'];
        $new->postcode = $data['postal_address'];
        $new->contact_no = $data['contactno'];
        $new->email_address = $data['email'];
        $new->marital_status = $data['marital_status'];
        $new->identification_number = $data['identification_number'];
        $new->identification_type = $data['identification_type'];
        $new->next_of_kin = $data['nextofkin'];
        $new->relationship = $data['kin_relationship'];
        $new->kin_address = $data['kin_address'];
        $new->kin_email = $data['kin_email'];
        $new->kin_contactno = $data['kin_contactno'];
        $new->kin_postcode = $data['kin_postal'];
        $new->guardian_firstname = $data['guardian_firstname'];
        $new->guardian_surname = $data['guardian_lastname'];
        $new->guardian_address = $data['guardian_address'];
        $new->guardian_contact = $data['guardian_contact'];
        $new->guardian_emailaddress = $data['guardian_email'];
        $new->guardian_postal_address = $data['guardian_postcode'];

        $new->createdby = Session::get('id');
        $saved = $new->save();

        $code = $new->code;
        if (!$saved) {
            $response['success'] = '1';
            $response['message'] = 'Couldnt save';
            return json_encode($response);
        } else {
            $this->updateStudentAcademicDetails($data);
            $this->updateStudentBankDetails($data);
            $this->updateStudentEnrollmentDetails($data);


            $this->saveUsers($code, $data, 'student');
            $response['success'] = '0';

            return json_encode($response);
        }
    }

    private function updateStudentAcademicDetails($data) {


        $new = StudentAcademic::where('student_no', $data['studentno'])
                ->first();
        // $new->student_no = $code;
        if (!empty($data['institute_code'])) {
            $new->instituition_code = $data['institute_code'];
        }

        $new->last_instituition = $data['last_institution_completed'];
        $new->completion_year = $data['completion_year'];
        $new->examination_type = $data['examination_type'];
        $new->aggregate = $data['aggregate'];
        $new->program = $data['program'];

        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

    private function updateStudentBankDetails($data) {


        $new = StudentBank::where('student_no', $data['studentno'])
                ->first();
        if (!empty($data['institute_code'])) {
            $new->instituition_code = $data['institute_code'];
        }

        $new->bank_name = $data['bank_name'];
        $new->account_name = $data['account_name'];
        $new->account_no = $data['account_number'];
        $new->branch = $data['branch'];
        $new->tin = $data['tin'];
        $new->ssniit = $data['SNNIT'];
        $new->superannutation_name = $data['superannutation_fund'];



        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {

            return '0';
        }
    }

    private function updateStudentEnrollmentDetails($data) {


        // $new = new StudentEnrollment();
        $new = StudentEnrollment::where('student_no', $data['studentno'])
                ->first();
        if (!empty($data['institute_code'])) {
            $new->instituition_code = $data['institute_code'];
        }

        // $new->program = $data['enrollment_program'];
        $new->admission_year = $data['admission_year'];
        $new->professional_body = $data['professional_body'];
        $new->sitting_year = $data['year_of_seating'];
        $new->index_no = $data['indexno'];
        $new->certificate_type = $data['certificatype'];


        $saved = $new->save();
        if (!$saved) {
            return '1';
        } else {

            return '0';
        }
    }

    private function updateUsers($data) {

        // $new = new Users();
        $new = Users::where('usercode', $data['code'])
                ->first();
        if (!empty($data['institute_code'])) {
            $new->instituition_code = $data['institute_code'];
        }

        //  $new->usercode = $code;
        $new->firstname = $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'];
        $new->email = $data['email'];
        $new->password = md5('123456');
        //$new->role = $type;
        $new->usergroup = "null";
        $saved = $new->save();
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

    public function deleteStudent($id) {
        $update = Student::find($id);
        $update->active = '1';
        $saved = $update->save();
        if (!$saved) {
            return '1';
        } else {
            return '0';
        }
    }

}
