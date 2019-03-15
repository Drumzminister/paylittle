<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use App\Models\Hits;
use App\Models\Photo;
use Ramsey\Uuid\Uuid;
use App\Models\Project;
use App\Models\Duration;
use App\Models\Guarantor;
use App\Models\RepaymentPlan;
use App\Models\sponsorshipAmount;
use App\Models\ProjectSubscription;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;


class ProjectController extends Controller
{
    protected $newImageName;
    protected $previousImageName;
    protected $amountSubscribed;

    public function __construct()
    {
        $this->middleware([ 'auth', 'verified', 'checkbvn' ])->except([ 'index', 'show' ]);
    }


    public function index()
    {
        $data['user']     = Auth::user();
        $data['projects'] = Project::whereStatusId(2)->paginate(12);
        $data['count']    = Project::whereStatusId(2)->count();

        if($data['count'] == 0){
            Session::flash('info', 'No sponsorable project yet!');
            return redirect()->back();
        }

        return view('dashboard.projects.index', $data);
    }


    public function create()
    {
        $data['user']           = Auth::user();
        $data['durations']      = Duration::all();
        $data['repaymentPlans'] = RepaymentPlan::all();
        $data['guarantors'] = Guarantor::all();

        if($data['guarantors']->count() == 0){
            Session::flash('info', 'Create at least one Guarantor first!');
            return redirect()->back();
        }

        return view('dashboard.projects.create', $data);
    }

    public function store( CreateProjectRequest $request )
    {
        $request['id']               = Uuid::uuid1();
        $request['user_id']          = Auth::user()->id;
        $request['repayment_amount'] = $request->amount + ($request->amount * 0.35);
        $project                     = Project::create($request->except([ '_token' ]));
        $this->storeOrReplaceImage($request, $project);
        if (!$project)
        {
            Session::flash('error', 'Could not create project');
            return redirect()->route('project.create');
        }
        Session::flash('success', 'Project Created Successfully');
        return redirect()->route('clientarea');
    }


    public function show( Project $project )
    {
        $data['project']            = $project;
        $data['user']               = Auth::user();
        $data['durations']          = Duration::all();
        $data['repaymentPlans']     = RepaymentPlan::all();
        $data['sponsorshipAmounts'] = sponsorshipAmount::all();
        $data['amountremaining']    = $this->checkSponsorshipAmountRemaining($project->id);
        //        return $data;
        return view('dashboard.viewProject', $data);
    }

    public function checkSponsorshipAmountRemaining( $project )
    {
        $project                   = Project::findOrfail($project);
        $projectSubscriptionAmount = ProjectSubscription::whereProjectId($project->id)->pluck('amount');
        $projectSubscriptionAmount->each(function ( $amount ) {
            $this->amountSubscribed += $amount;
        });
        return $project->amount - $this->amountSubscribed;
    }


    public function edit( Project $project )
    {
        $data['user']           = Auth::user();
        $data['durations']      = Duration::all();
        $data['repaymentPlans'] = RepaymentPlan::all();
        $data['project']        = $project;
        return view('dashboard.projects.edit', $data);
    }

    public function update( UpdateProjectRequest $request, $id )
    {
        $project = Project::findOrFail($id);
        $project->update($request->except([ '_token' ]));

        if ($request->hasFile('avatarobject'))
        {
            $this->storeOrReplaceImage($request, $project, "replace");
        }

        if (!$project){
            Session::flash('error', 'Could not Update project');
            return redirect()->route('userProjects.show', $project->id);
        }

        Session::flash('success', 'Project Updated');
        return redirect()->route('userProjects.show', $project->id);
    }

    public function delete( Project $project )
    {
        //implement a feature that checks if project has sponsorship already, if it does, it can't be deleted
        $project->delete();

        Session::flash('success', 'Project Thrashed Successfully');
        return redirect()->back();
    }

    public function destroy( $project ){
        Project::onlyTrashed()->find($project)->forceDelete();

        Session::flash('success', 'Project Deleted Permanently');
        return redirect()->route('projects.trashed');
    }

    public function trashedProjects(){
        $data['projects'] = Project::onlyTrashed()->whereUserId(Auth::id())->paginate(9);
        return view('dashboard.projects.trashed',$data);
    }

    public function restoreProject($project){
        Project::onlyTrashed()->find($project)->restore();

        Session::flash('success', 'Project Restored Successfully');
        return redirect()->route('projects.trashed');
    }

    public function filterByUser( )
    {
        $data['projects'] = Project::whereUserId(Auth::id())->paginate(9);

        if($data['projects']->count() == 0){
            Session::flash('info', 'You have not created any project yet!');
            return redirect()->back();
        }

        return view('dashboard.projects.showcreated', $data);
    }

    // Don't mess around here
    public function storeOrReplaceImage( $request, $project, $storeOrReplace = "store" )
    {
        if ($storeOrReplace != "store")
        {
            return $this->replaceImage($request, $project);
        }
        return $this->storeImage($request, $project);
    }

    public function replaceImage( $request, $project )
    {
        $this->previousImageName = $project->photo->avatar ?? 'nothing';
        if (Storage::disk('public')->exists("avatars/projects/" . $this->previousImageName) && !Storage::disk('public')->delete('avatars/projects/' . $this->previousImageName))
        {
            Session::flash('error', 'Can\'t Process the file at the moment');
            return redirect()->back();
        }
        $this->newImageName = Auth::user()->id . "_" . Auth::user()->first_name . "_" . time() . "." . $request->avatarobject->getClientOriginalExtension();
        if (!$request->avatarobject->storeAs('public/avatars/projects', $this->newImageName))
        {
            Session::flash('error', 'Can\'t save image');
            return redirect()->back();
        }
        $request['avatar'] = $this->newImageName;
        $project->photo()->update([
            'avatar' => $request['avatar'],
        ]);
    }

    public function storeImage( $request, $project )
    {
        $this->newImageName        = Auth::user()->id . "_" . Auth::user()->first_name . "_" . time() . "." . $request->avatarobject->getClientOriginalExtension();
        $request['avatar']         = $this->newImageName;
        $request['imageable_type'] = $project->model;
        $request['imageable_id']   = $project->id;

        if (!$request->avatarobject->storeAs('public/avatars/projects', $this->newImageName)){
            Session::flash('error', 'Can\'t save image');
            return redirect()->back();
        }

        $photo = Photo::create($request->except([ '_token' ]));
    }

    public function increaseProjectHit( Project $project )
    {
        $hits = Hits::whereProjectId($project->id)->get();

        if (count($hits) != 1)
        {
            $data['project_id'] = $project->id;
            $data['count']      = 1;
            Hits::create($data);
            return;
        }

        $updatedHit = $hits->first()->count += 1;

        $hits->first()->update([
            'count' => $updatedHit,
        ]);
        return;

    }

    public function ProjectsHistory( ){
        $projects = Project::whereUserId(Auth::id())->get();
        $subscriptions = ProjectSubscription::whereUserId(Auth::id())->get();

        if($projects->count() == 0){
            Session::flash('info', 'You must have atleast one project.');
            return redirect()->back();
        }

        $data['allProjects'] = $projects->merge($subscriptions)->sortByDesc('created_at');
        return view('dashboard.projects.history', $data);
    }


}
