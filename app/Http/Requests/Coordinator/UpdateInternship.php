<?php

namespace App\Http\Requests\Coordinator;

use App\Models\Internship;
use App\Models\Sector;
use App\Models\Supervisor;
use App\Rules\Active;
use App\Rules\CompanyHasAgreement;
use App\Rules\CompanyHasCoordinatorCourse;
use App\Rules\CompanyHasSector;
use App\Rules\CompanyHasStudentCourse;
use App\Rules\CompanyHasSupervisor;
use App\Rules\HourInterval;
use App\Rules\Integer;
use App\Rules\MinimalSemester;
use App\Rules\MinimalYear;
use App\Rules\RA;
use App\Rules\StudentAge;
use App\Rules\StudentHasInternship;
use App\Rules\StudentHasJob;
use App\Rules\StudentMaxYears;
use App\Rules\StudentSameCoordinatorCourse;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInternship extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $internship = Internship::findOrFail($this->route('id'));

        return [
            'has2Schedules' => ['required', 'boolean'],
            'dilation' => ['required', 'boolean'],

            'ra' => ['required', new Integer, 'min:1', new RA, new StudentHasInternship($internship->id), new StudentHasJob, new StudentSameCoordinatorCourse, new CompanyHasStudentCourse($this->get('company')), new StudentAge($this->get('startDate')), (!$this->get('dilation')) ? new StudentMaxYears($this->get('startDate'), $this->get('endDate')) : '', new MinimalYear, new MinimalSemester($this->get('startDate'))],
            'active' => ['required', 'boolean'],
            'company' => ['required', 'integer', 'min:1', 'exists:companies,id', new CompanyHasCoordinatorCourse, new CompanyHasAgreement($this->get('startDate'))],
            'sector' => ['required', 'integer', 'min:1', 'exists:sectors,id', new CompanyHasSector($internship->company->id), new Active(Sector::class, $internship->sector_id)],
            'startDate' => ['required', 'date', 'before:endDate'],
            'endDate' => ['required', 'date', 'after:startDate'],
            'activities' => ['required', 'max:8000'],

            'monS' => ['required_without_all:tueS,wedS,thuS,friS,satS', 'required_with:monE', 'nullable', 'date_format:H:i', 'before:monE'],
            'monE' => ['required_with:monS', 'nullable', 'date_format:H:i', 'after:monS', new HourInterval($this->get('monS'), $this->get('monE2'), $this->get('monS2'))],
            'tueS' => ['required_with:tueE', 'nullable', 'date_format:H:i', 'before:tueE'],
            'tueE' => ['required_with:tueS', 'nullable', 'date_format:H:i', 'after:tueS', new HourInterval($this->get('tueS'), $this->get('tueE2'), $this->get('tueS2'))],
            'wedS' => ['required_with:wedE', 'nullable', 'date_format:H:i', 'before:wedE'],
            'wedE' => ['required_with:wedS', 'nullable', 'date_format:H:i', 'after:wedS', new HourInterval($this->get('wedS'), $this->get('wedE2'), $this->get('wedS2'))],
            'thuS' => ['required_with:thuE', 'nullable', 'date_format:H:i', 'before:thuE'],
            'thuE' => ['required_with:thuS', 'nullable', 'date_format:H:i', 'after:thuS', new HourInterval($this->get('thuS'), $this->get('thuE2'), $this->get('thuS2'))],
            'friS' => ['required_with:friE', 'nullable', 'date_format:H:i', 'before:friE'],
            'friE' => ['required_with:friS', 'nullable', 'date_format:H:i', 'after:friS', new HourInterval($this->get('friS'), $this->get('friE2'), $this->get('friS2'))],
            'satS' => ['required_with:satE', 'nullable', 'date_format:H:i', 'before:satE'],
            'satE' => ['required_with:satS', 'nullable', 'date_format:H:i', 'after:satS', new HourInterval($this->get('satS'), $this->get('satE2'), $this->get('satS2'))],

            'monS2' => [($this->get('has2Schedules')) ? 'required_without_all:tueS2,wedS2,thuS2,friS2,satS2' : '', 'nullable', 'date_format:H:i', 'after:monE', 'before:monE2'],
            'monE2' => ['required_with:monS2', 'nullable', 'date_format:H:i', 'after:monS2'],
            'tueS2' => ['required_with:tueE2', 'nullable', 'date_format:H:i', 'after:tueE', 'before:tueE2'],
            'tueE2' => ['required_with:tueS2', 'nullable', 'date_format:H:i', 'after:tueS2'],
            'wedS2' => ['required_with:wedE2', 'nullable', 'date_format:H:i', 'after:wedE', 'before:wedE2'],
            'wedE2' => ['required_with:wedS2', 'nullable', 'date_format:H:i', 'after:wedS2'],
            'thuS2' => ['required_with:thuE2', 'nullable', 'date_format:H:i', 'after:thuE', 'before:thuE2'],
            'thuE2' => ['required_with:thuS2', 'nullable', 'date_format:H:i', 'after:thuS2'],
            'friS2' => ['required_with:friE2', 'nullable', 'date_format:H:i', 'after:friE', 'before:friE2'],
            'friE2' => ['required_with:friS2', 'nullable', 'date_format:H:i', 'after:friS2'],
            'satS2' => ['required_with:satE2', 'nullable', 'date_format:H:i', 'after:satE', 'before:satE2'],
            'satE2' => ['required_with:satS2', 'nullable', 'date_format:H:i', 'after:satS2'],

            'supervisor' => ['required', 'integer', 'min:1', 'exists:supervisors,id', new CompanyHasSupervisor($internship->company->id), new Active(Supervisor::class, $internship->supervisor_id)],

            'protocol' => ['required', new Integer, 'digits:7'],
            'observation' => ['nullable', 'max:8000'],
        ];
    }
}
