<?php

namespace App\Http\Controllers;

use App\User;
use Ramsey\Uuid\Uuid;
use App\Models\Sponsor;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectSubscription;
use Illuminate\Support\Facades\Auth;
use App\Models\Status;
use App\Http\Requests\SponsorshipRequest;




class SponsorController extends Controller
{


    public function sponsorProject(SponsorshipRequest $request, Project $project)
    {
        $request['id'] = Uuid::uuid1();
        $request['user_id'] = Auth::user()->id;
        $request['project_id'] = $project->id;

        ProjectSubscription::create($request->except(['_token']));
        return redirect()->route('view.sponsor', Auth::user()->id)->with('success', 'Project Sponsored');
    }

    public function sponsoredProjects(User $user)
    {
        $data['user'] = $user;
        $data['projectsubscriptions'] =  ProjectSubscription::whereUserId($user->id)->get();

        return view('dashboard.showsponsored', $data);
    }

    public function sponsorReturns(Project $project, $amount)
    {
        $percentageReturns = ($amount * $project->returnsPercentage) + $amount;

        return $percentageReturns;
    }
}
