<?php

namespace App\Http\Controllers;

use App\Models\ChildActivity;
use App\Models\JobCard;
use App\Models\ParentActivity;
use App\Models\Signature;
use App\Models\Timeline;
use App\Models\Tree;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FruitCollectionController extends Controller
{
    //view Fruit collection and its Activities
    public function FruitCollection()
    {
        $data = ParentActivity::with('childs')
            ->where('parent_number', 3)
            ->first();
        return Inertia::render('FruitCollection/FruitCollection-activities', [
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

        return Inertia::render('FruitCollection/Table', [
            'childData' => $child,
            'currentCards' => $currentCard,
        ]);
    }

    //View signature and document for all Fruit collection stage one,
    public function ViewStage($id)
    {
        $jobCard = JobCard::findOrFail($id);
        $jobCard->load(
            'childactivity.records',
            'childactivity.roles'
        );
        $signed = Signature::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->pluck('role_id')->toArray();
        $find_start_date = Timeline::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->first();
        if ($find_start_date) {
            $start_date = $find_start_date->start_date;
        } else {
            $start_date = Carbon::now();
        }

        return Inertia::render('FruitCollection/Edit-job-card', [
            'Jobcard' => $jobCard,
            'Signed' => $signed,
            'BeginDate' => $start_date,
        ]);
    }

    //update signature for Fruit Collection approval plan
    public function updateStageOne(Request $request, $id)
    {
        $data = $request->validate([
            'signature' => ['required'],
        ]);

        $jobCard = JobCard::findOrFail($id);

        $timeline = Timeline::where(['job_card_id' => $id, 'child_activity_id' => $jobCard->current_child_id])->first();
        if ($timeline == null) {
            Timeline::create([
                'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                'child_activity_id' => $jobCard->current_child_id, 'job_card_id' => $jobCard->id,
            ]);
        }

        $current_signatures = Signature::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->pluck('role_id')->toArray();

        $request_signatures = $request->signature;
        $new_signatures = array_diff($request_signatures, $current_signatures);
        foreach ($new_signatures as $key => $value) {
            Signature::Create([
                'user_id' => auth()->id(),
                'job_card_id' => $jobCard->id,
                'role_id' => $value,
                'child_activity_id' => $jobCard->current_child_id
            ]);
        }

        $signature_no = $jobCard->childactivity->roles->pluck('id')->count();

        if ($signature_no == count($request->signature)) {
            $next_activity = ChildActivity::where('id', '>', $jobCard->current_child_id)->first();
            Timeline::where(['job_card_id' => $jobCard->id, 'child_activity_id' => $jobCard->current_child_id])
                ->first()
                ->update(['end_date' => now()->format('Y-m-d')]);
            $jobCard->update(['current_child_id' => $next_activity->id, 'current_parent_id' => $next_activity->parent_activity_id]);
        }

        return redirect()->route('FC activity', $jobCard->current_child_id)->with('success', 'Update Successfully');
    }

    public function LabelGunnyBag($id)
    {
        $jobCard = JobCard::findOrFail($id);
        $jobCard->load(
            'childactivity.records',
            'childactivity.roles',
            'trees'
        );

        $find_start_date = Timeline::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->first();

        if ($find_start_date) {
            $start_date = $find_start_date->start_date;
        } else {
            $start_date = Carbon::now();
        }

        return Inertia::render('FruitCollection/Label-gunny-bag', [
            'Jobcard' => $jobCard,
            'BeginDate' => $start_date,
        ]);
    }

    public function storeTreeNumber(Request $request, $id)
    {
        $data = $request->validate([
            'treeNumber' => 'required',
            'startDate' => 'required',
        ]);

        $jobcard = JobCard::findOrFail($id);

        $timeline = Timeline::where([
            'job_card_id' => $jobcard->id,
            'child_activity_id' => $jobcard->current_child_id
        ])->first();

        if (!$timeline) {
            Timeline::create([
                'start_date' => Carbon::parse($data['startDate'])->format('Y-m-d'),
                'job_card_id' => $jobcard->id,
                'child_activity_id' => $jobcard->current_child_id
            ]);
        }

        Tree::create([
            'tree_number' => strtoupper($request->treeNumber),
            'job_card_id' => $jobcard->id,
        ]);

        return redirect()->back()->with('success', 'Saved');
    }

    public function destroyTreeNumber($id)
    {
        Tree::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'destroyed');
    }

    public function CompleteLabelGunnyBags($id)
    {
        $jobcard = JobCard::findOrFail($id);
        $signatures = $jobcard->childactivity->roles->pluck('id')->toArray();
        foreach ($signatures as $key => $value) {
            Signature::Create([
                'user_id' => auth()->id(),
                'job_card_id' => $jobcard->id,
                'role_id' => $value,
                'child_activity_id' => $jobcard->current_child_id
            ]);
        }
        $next_activity = ChildActivity::where('id', '>', $jobcard->current_child_id)->first();
        Timeline::where(['job_card_id' => $jobcard->id, 'child_activity_id' => $jobcard->current_child_id])
            ->first()
            ->update(['end_date' => now()->format('Y-m-d')]);
        $jobcard->update(['current_child_id' => $next_activity->id, 'current_parent_id' => $next_activity->parent_activity_id]);

        return redirect()->route('FC activity', $jobcard->current_child_id)->with('success', 'Update Successfully');
    }

    public function FruitCollectionFromTree($id)
    {
        $jobCard = JobCard::findOrFail($id);
        $jobCard->load(
            'childactivity.records',
            'childactivity.roles',
            'trees'
        );

        $find_start_date = Timeline::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->first();

        return Inertia::render('FruitCollection/Fruit-collection-from-tree', [
            'Jobcard' => $jobCard,
        ]);
    }

    public function storeCollectedPerTree(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required',
        ]);

        $tree = Tree::findOrFail($id);

        $jobcard = JobCard::findOrFail($tree->job_card_id);

        $timeline = Timeline::where([
            'job_card_id' => $jobcard->id,
            'child_activity_id' => $jobcard->current_child_id
        ])->first();

        if (!$timeline) {
            Timeline::create([
                'start_date' => Carbon::now()->format('Y-m-d'),
                'job_card_id' => $jobcard->id,
                'child_activity_id' => $jobcard->current_child_id
            ]);
        }

        $tree->update(['fruit_collection_quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Update Successfully');
    }

    public function FruitCollectionFromFarm($id)
    {
        $jobCard = JobCard::findOrFail($id);
        $jobCard->load(
            'childactivity.records',
            'childactivity.roles',
            'trees'
        );

        $find_start_date = Timeline::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->first();

        return Inertia::render('FruitCollection/Fruit-collection-from-farm', [
            'Jobcard' => $jobCard,
        ]);
    }

    public function storeCollectionFromFarm(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required',
        ]);

        $tree = Tree::findOrFail($id);

        $jobcard = JobCard::findOrFail($tree->job_card_id);

        $timeline = Timeline::where([
            'job_card_id' => $jobcard->id,
            'child_activity_id' => $jobcard->current_child_id
        ])->first();

        if (!$timeline) {
            Timeline::create([
                'start_date' => Carbon::now()->format('Y-m-d'),
                'job_card_id' => $jobcard->id,
                'child_activity_id' => $jobcard->current_child_id
            ]);
        }

        $tree->update(['farm_collection_quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Update Successfully');
    }

    public function FruitCollectionNurseryTransport($id)
    {
        $jobCard = JobCard::findOrFail($id);
        $jobCard->load(
            'childactivity.records',
            'childactivity.roles',
            'trees'
        );

        $find_start_date = Timeline::where([
            'job_card_id' => $jobCard->id,
            'child_activity_id' => $jobCard->current_child_id
        ])->first();

        return Inertia::render('FruitCollection/Nursery-transport', [
            'Jobcard' => $jobCard,
        ]);
    }

    public function storeNurseryTransport(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required',
        ]);

        $tree = Tree::findOrFail($id);

        $jobcard = JobCard::findOrFail($tree->job_card_id);

        $timeline = Timeline::where([
            'job_card_id' => $jobcard->id,
            'child_activity_id' => $jobcard->current_child_id
        ])->first();

        if (!$timeline) {
            Timeline::create([
                'start_date' => Carbon::now()->format('Y-m-d'),
                'job_card_id' => $jobcard->id,
                'child_activity_id' => $jobcard->current_child_id
            ]);
        }

        $tree->update(['nursery_transport_quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Update Successfully');
    }
}
