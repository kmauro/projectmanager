<?php

namespace App\Repositories;

use App\Models\Applicant;
use Illuminate\Support\Facades\DB;

class ApplicantRepository
{
    public function insertGetId($applicant){
            $applicantId = Applicant::insertGetId([
                'IP_address' => $applicant['IP_address'],
                'user' => $applicant['user'],
                'full_name' => $applicant['full_name'],
                'email' => $applicant['email'],
                'phone' => $applicant['phone'],
                'cuil' => $applicant['cuil'],
                'institution_id' => $applicant['institution_id'],
                'company_name' => $applicant['company_name'],
                'company_CUIT' => $applicant['company_CUIT'],
            ]);
        
        return $applicantId ?? null;

    }

    public static function getApplicant($applicantId)
    {
        $applicant = Applicant::find($applicantId);
        if(empty($applicant)){
            abort(404);
        }
        return $applicant;
    }
}