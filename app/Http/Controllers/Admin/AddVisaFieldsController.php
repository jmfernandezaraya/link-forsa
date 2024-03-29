<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\SuperAdmin\AddApplicationRequest;
use App\Http\Requests\SuperAdmin\AddNationalityRequest;
use App\Http\Requests\SuperAdmin\AddTravelRequest;
use App\Http\Requests\SuperAdmin\AddTypeOfVisaRequest;
use App\Http\Requests\SuperAdmin\ApplyFromRequest;

use App\Models\AddNationality;
use App\Models\AddTypeOfVisa;
use App\Models\AddWhereToTravel;
use App\Models\ApplyFrom;
use App\Models\VisaApplicationCenter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AddVisaFieldsController extends Controller
{
    public function addApplyingFrom(ApplyFromRequest $request)
    {
        (new ApplyFrom())->fill($request->validated())->save();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<select name = 'applying_from' class='form-control' id='applying_from'><option value=''>$select</option>";
        foreach (ApplyFrom::all() as $applyform) {
            $data['option'] .= "<option value='$applyform->id'>".$applyform->{'apply_from_'.  get_language() }." </option>";
        }

        $data['option'] .="</select>";
        $data['data'] = __('Admin/backend.errors.success');
        return response($data);
    }

    public function deleteApplyingFrom(Request $request)
    {
        ApplyFrom::find($request->applying_from)->delete();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<select name = 'applying_from' class='form-control' id='applying_from'><option value=''>$select</option>";
        foreach (ApplyFrom::all() as $applyform) {
            $data['option'] .= "<option value='$applyform->id'>".$applyform->{'apply_from_'.  get_language() }." </option>";
        }

        $data['option'] .="</select>";
        $data['data'] = __('Admin/backend.data_removed_successfully');
        return response($data);
    }

    public function addApplicationCenter(AddApplicationRequest $request)
    {
        (new VisaApplicationCenter())->fill($request->validated())->save();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<select name = 'applying_from' class='form-control' id='application_center'><option value=''>$select</option>";
        foreach (VisaApplicationCenter::all() as $applyform) {
            $data['option'] .= "<option value='$applyform->id'>".$applyform->{'application_center_'.  get_language() }." </option>";
        }


        $data['option'] .="</select>";
        $data['data'] = __('Admin/backend.errors.success');
        return response($data);
    }

    public function deleteApplicationCenter(Request $request)
    {
        VisaApplicationCenter::find($request->application_center)->delete();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<option value=''>$select</option>";
        foreach (VisaApplicationCenter::all() as $applyform) {
            $data['option'] .= "<option value='$applyform->id'>".$applyform->{'application_center_'.  get_language() }." </option>";
        }

        $data['data'] = __('Admin/backend.data_removed_successfully');
        return response($data);
    }

    public function addNationality(AddNationalityRequest $request)
    {
        (new AddNationality())->fill($request->validated())->save();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<option value=''>$select</option>";
        foreach(AddNationality::all() as $options){
            $data['option'] .= "<option value='$options->id'>".$options->{'nationality_'.  get_language() }." </option>";
        }

        $data['data'] = __('Admin/backend.errors.success');
        return response($data);
    }

    public function deleteNationality(Request $request)
    {
        AddNationality::find($request->application_center)->delete();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<option value=''>$select</option>";
        foreach(AddNationality::all() as $applyform){
            $data['option'] .= "<option value='$applyform->id'>".$applyform->{'nationality_'.  get_language() }." </option>";
        }

        $data['data'] = __('Admin/backend.data_removed_successfully');
        return response($data);
    }

    public function addTravel(AddTravelRequest $request)
    {
        (new AddWhereToTravel())->fill($request->validated())->save();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<option value=''>$select</option>";
        foreach(AddWhereToTravel::all() as $options){
            $data['option'] .= "<option value='$options->id'>".$options->{'travel_'.  get_language() }." </option>";
        }

        $data['data'] = __('Admin/backend.errors.success');
        return response($data);
    }

    public function deleteTravel(Request $request)
    {
        AddWhereToTravel::find($request->application_center)->delete();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<option value=''>$select</option>";
        foreach(AddNationality::all() as $applyform){
            $data['option'] .= "<option value='$applyform->id'>".$applyform->{'nationality_'.  get_language() }." </option>";
        }

        $data['data'] = __('Admin/backend.data_removed_successfully');
        return response($data);
    }

    public function addTypeOfVisa(AddTypeOfVisaRequest $request)
    {
        (new addTypeOfVisa())->fill($request->validated())->save();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<option value=''>$select</option>";
        foreach(AddTypeOfVisa::all() as $options){
            $data['option'] .= "<option value='$options->id'>".$options->{'visa_'.  get_language() }." </option>";
        }

        $data['data'] = __('Admin/backend.errors.success');
        return response($data);
    }

    public function deleteTypeOfVisa(Request $request)
    {
        AddTypeOfVisa::find($request->application_center)->delete();

        $select = __('Admin/backend.select_option');
        $data['success'] = true;
        $data['option'] = "<option value=''>$select</option>";
        foreach(AddTypeOfVisa::all() as $applyform){
            $data['option'] .= "<option value='$applyform->id'>".$applyform->{'visa_'.  get_language() }." </option>";
        }

        $data['data'] = __('Admin/backend.data_removed_successfully');
        return response($data);
    }
}