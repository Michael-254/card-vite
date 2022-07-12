<?php

namespace App\Http\Controllers;

use App\Models\ChildActivity;
use App\Models\JobCard;
use App\Models\JobCardRecord;
use App\Models\ParentActivity;
use App\Models\Signature;
use App\Models\Timeline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OperationalPlanningController extends Controller
{
    //view Operation Planning and its Activities
    public function operationPlanning()
    {
        $data = ParentActivity::with('childs')
            ->where('parent_number', 1)
            ->first();
        return Inertia::render('OperationPlanning/determine-objective', [
            'data' => $data,
        ]);
    }

    //check jobcards available in specific activity
    public function ChildActivity($id)
    {
        $child = ChildActivity::findOrFail($id);
        $user_roles = auth()->user()->roles()->pluck('role')->toArray();
        $activity_role = $child->roles()->pluck('role')->toArray();
        abort_unless(count(array_intersect($user_roles, $activity_role)) > 0, 403);
        $parent = $child->parent;
        $currentCard = JobCard::where(['current_parent_id' => $parent->id, 'current_child_id' => $child->id])
            ->get();

        return Inertia::render('OperationPlanning/Table', [
            'childData' => $child,
            'currentCards' => $currentCard,
        ]);
    }

    //view new job card
    public function NewJobCard($id)
    {

        $child = ChildActivity::with('records', 'roles')->findOrFail($id);
        return Inertia::render('OperationPlanning/Determine-objectives/new-job-card', [
            'Activity' => $child,
        ]);
    }

    //store new job card
    public function store(Request $request)
    {
        //dd($request->all());
        $data = $request->validate([
            'start_date' => 'required',
            'site' => 'required',
            "signature"  => ["required"],
            "records"  => ["required"],
            "locations"  => ["required"],
        ]);

        $job_card = JobCard::create([
            'user_id' => auth()->id(),
            'site' => $request->site,
            'current_parent_id' => $request->current_parent_id,
            'current_child_id' => $request->current_child_id,
            'status' => 'stage one',
        ]);

        Timeline::create([
            'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
            'child_activity_id' => $request->current_child_id,
            'job_card_id' => $job_card->id
        ]);

        $records = array_combine($data['records'], $data['locations']);

        foreach ($records as $key => $value) {
            JobCardRecord::create([
                'job_card_id' => $job_card->id,
                'record_id' => $key, 'location' => $value, 'child_activity_id' => $request->current_child_id,
            ]);
        }

        foreach ($request->signature as $key => $value) {
            Signature::create([
                'user_id' => auth()->id(), 'job_card_id' => $job_card->id,
                'role_id' => $value, 'child_activity_id' => $request->current_child_id
            ]);
        }

        $job_card->update(['job_card_number' => 'JobCard00' . $job_card->id]);

        return redirect()->route('OP activity', $request->current_child_id)->with('success', 'JobCard created Successfully');
    }

    //view signature and documents for Determination of Objectives
    public function signatureOne($id)
    {
        $jobCard = JobCard::findOrFail($id);
        $signed = $jobCard->signatures->pluck('role_id')->toArray();
        $confirmed_records = $jobCard->Confirmedrecords->pluck('id')->toArray();
        $confirmed_records_locations = $jobCard->Confirmedrecords->pluck('pivot.location')->toArray();
        $jobCard->load('childactivity.records', 'childactivity.roles', 'timelines');


        return Inertia::render('OperationPlanning/Determine-objectives/Edit-job-card', [
            'Jobcard' => $jobCard,
            'Signed' => $signed,
            'CheckedRecords' => $confirmed_records,
            'ConfirmedLocations' => $confirmed_records_locations,
        ]);
    }

    //update signature and documents for Determination of Objectives
    public function updateStageOne(Request $request, $id)
    {
        $data = $request->validate([
            'signature' => ['required'],
            'records' => ['required'],
            "locations"  => ['required'],
        ]);

        $jobCard = JobCard::findOrFail($id);
        $records = array_combine($data['records'], $data['locations']);
        $jobCard->Confirmedrecords()->detach();
        foreach ($records as $key => $value) {
            JobCardRecord::Create([
                'job_card_id' => $jobCard->id,
                'record_id' => $key,
                'location' => $value,
                'child_activity_id' => $jobCard->current_child_id,
            ]);
        }

        $current_signatures = Signature::where(['job_card_id' => $jobCard->id])->pluck('role_id')->toArray();
        $request_signatures = $request->signature;
        $new_signatures = array_diff($request_signatures, $current_signatures);
        foreach ($new_signatures as $key => $value) {
            Signature::Create([
                'user_id' => auth()->id(),
                'job_card_id' => $jobCard->id,
                'role_id' => $value,
                'child_activity_id' => $request->current_child_id
            ]);
        }

        $signature_no = $jobCard->childactivity->roles->pluck('id')->count();
        $record_no =  $jobCard->childactivity->records->pluck('id')->count();

        if ($signature_no == count($request->signature) && $record_no == count($request->records)) {
            $next_activity = ChildActivity::where('id', '>', $jobCard->current_child_id)->first();
            Timeline::where(['job_card_id' => $jobCard->id, 'child_activity_id' => $jobCard->current_child_id])
                ->first()
                ->update(['end_date' => now()->format('Y-m-d')]);
            $jobCard->update(['current_child_id' => $next_activity->id]);
        }

        return redirect()->route('OP activity', $jobCard->current_child_id)->with('success', 'Update Successfully');
    }

    //View signature and documents for all other stages,
    public function ViewStage($id)
    {
        $jobCard = JobCard::findOrFail($id);
        $jobCard->load(
            'signatures.role',
            'signatures.user',
            'signatures.son_activity',
            'timelines',
            'Confirmedrecords.activityChild',
            'childactivity.records',
            'childactivity.roles'
        );
        $signed = Signature::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->pluck('role_id')->toArray();
        $confirmed_records = JobCardRecord::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->pluck('record_id')->toArray();
        $confirmed_records_locations = JobCardRecord::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->pluck('location')->toArray();
        $find_start_date = Timeline::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->first();
        if ($find_start_date) {
            $start_date = $find_start_date->start_date;
        } else {
            $start_date = Carbon::now();
        }

        return Inertia::render('OperationPlanning/Op-planning/Edit-job-card', [
            'Jobcard' => $jobCard,
            'Signed' => $signed,
            'Records' => $confirmed_records,
            'ConfirmedLocations' => $confirmed_records_locations,
            'BeginDate' => $start_date,
        ]);
    }

    //Update all other stages of Operational planning
    public function updateAllOtherStages(Request $request, $id)
    {
        $data = $request->validate([
            'signature' => ['required'],
            'start_date' => ['required'],
            'records' => ['required'],
            "locations"  => ['required'],
        ]);

        $jobCard = JobCard::findOrFail($id);
        $timeline = Timeline::where(['job_card_id' => $id, 'child_activity_id' => $jobCard->current_child_id])->first();
        if ($timeline == null) {
            Timeline::create([
                'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                'child_activity_id' => $jobCard->current_child_id, 'job_card_id' => $jobCard->id,
            ]);
        }

        $records = array_combine($data['records'], $data['locations']);
        foreach ($records as $key => $value) {
            JobCardRecord::updateOrCreate(
                ['job_card_id' => $jobCard->id, 'record_id' => $key, 'child_activity_id' => $jobCard->current_child_id],
                ['location' => $value]
            );
        }

        $has_signed = Signature::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->pluck('role_id')->toArray();

        $new_signatures = array_diff($request->signature, $has_signed);
        foreach ($new_signatures as $key => $value) {
            Signature::Create([
                'user_id' => auth()->id(),
                'job_card_id' => $jobCard->id,
                'role_id' => $value,
                'child_activity_id' => $jobCard->current_child_id
            ]);
        }

        $signature_no = $jobCard->childactivity->roles->pluck('id')->count();
        $record_no =  $jobCard->childactivity->records->pluck('id')->count();

        if ($signature_no == count($request->signature) && $record_no == count($request->records)) {
            $next_activity = ChildActivity::where('id', '>', $jobCard->current_child_id)->first();
            Timeline::where(['job_card_id' => $jobCard->id, 'child_activity_id' => $jobCard->current_child_id])
                ->first()
                ->update(['end_date' => now()->format('Y-m-d')]);
            $jobCard->update(['current_child_id' => $next_activity->id, 'current_parent_id' => $next_activity->parent_activity_id]);
        }

        return redirect()->route('OP activity', $jobCard->current_child_id)->with('success', 'Update Successfully');
    }
}
