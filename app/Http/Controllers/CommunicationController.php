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

class CommunicationController extends Controller
{
    //view Operation Planning and its Activities
    public function Communication()
    {
        $data = ParentActivity::with('childs')
            ->where('parent_number', 2)
            ->first();
        return Inertia::render('Communication/Communication-activities', [
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

        return Inertia::render('Communication/Table', [
            'childData' => $child,
            'currentCards' => $currentCard,
        ]);
    }

    //View signature and documents for all Communication stages,
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

        return Inertia::render('Communication/Edit-job-card', [
            'Jobcard' => $jobCard,
            'Signed' => $signed,
            'Records' => $confirmed_records,
            'ConfirmedLocations' => $confirmed_records_locations,
            'ActivityStartDate' => $start_date,
        ]);
    }

    //Update all other stages of Operational planning
    public function updateCommunicationStages(Request $request, $id)
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

        return redirect()->route('Comm Activities')->with('success', 'Update Successfully');
    }
}
