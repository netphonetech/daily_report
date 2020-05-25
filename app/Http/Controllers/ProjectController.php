<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectParticipant;
use App\Report;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects =  Project::where('status', true)->get();
        return view('project.list', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|string',
            'start' => 'required|date',
            'expected_end' => 'required|date|after_or_equal:start'
        ]);
        $id =  Project::insertGetId([
            'name' => $request->name,
            'start_date' => $request->start,
            'end_date' => $request->end,
            'expected_end_date' => $request->expected_end,
            'description' => $request->description,
        ]);


        if ($id > 0) {
            session()->flash('success', 'Project added');
        } else {
            session()->flash('error', 'Error, project not added. Refresh and try again');
        }
        return redirect()->route('show-project', compact('id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $project = Project::where('id', $request->id)->first();
        if (!$project) {
            session()->flash('error', 'Failed, project not exist. Refresh and try again');
            return redirect()->back();
        }
        $participants = ProjectParticipant::where('projectID', $project->id)
            ->join('users', 'users.id', 'project_participants.userID')
            ->select('project_participants.id', 'role', 'performance', 'name', 'users.id as userID', 'project_participants.created_at', 'userID')
            ->get();
        $users = User::where('status', true)->get();
        return view('project.show', compact('project', 'participants', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $this->validate($request, [
            'name' => 'required|max:255|string',
            'start' => 'required|date',
            'expected_end' => 'required|date|after_or_equal:start'
        ]);

        $project =  Project::where('id', $request->id)->first();
        if (!$project) {
            session()->flash('error', 'Failed, project not exist. Refresh and try again');
            return redirect()->back();
        }
        $updated = Project::where('id', $request->id)->update(['name' => $request->name, 'start_date' => $request->start, 'end_date' => $request->end, 'expected_end_date' => $request->expected_end, 'description' => $request->description]);

        if ($updated) {
            session()->flash('success', 'Project updated');
        } else {
            session()->flash('error', 'Failed, project not updated. Refresh and try again');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $report
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request)
    {
        $project = Project::where('id', $request->id)->first();
        if (!$project) {
            session()->flash('success', 'Failed, project not exist, refresh and try again');
        }
        $project->completed = true;
        if ($project->save()) {
            session()->flash('success', 'Project removed');
        } else {
            session()->flash('error', 'Error, project not removed. Refresh and try again');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $project =  Project::where('id', $request->id)->first();
        if (!$project) {
            session()->flash('error', 'Failed, project not exist. Refresh and try again');
            return redirect()->back();
        }

        $project_report = Report::where('projectID', $project->id)->first();
        if ($project_report) {
            session()->flash('error', 'Failed, project is in progress.');
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            ProjectParticipant::where('projectID', $project->id)->delete();
            $project->delete();

            DB::commit();
            session()->flash('success', 'Project deleted');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Failed, project not deleted. Refresh and try again');
        }

        return redirect()->route('list-projects');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeParticipant(Request $request)
    {
        $this->validate($request, [
            'project' => 'required|numeric',
            'user' => 'required|numeric',
            'role' => 'required|string',
        ]);

        $test =  ProjectParticipant::where([['userID', $request->user], ['projectID', $request->project]])->first();
        if ($test) {
            session()->flash('error', 'Failed, project participant already exist');
            return redirect()->back();
        }
        $participant =  new ProjectParticipant();
        $participant->projectID = $request->project;
        $participant->userID = $request->user;
        $participant->role = $request->role;
        $participant->performance = $request->performance;

        if ($participant->save()) {
            session()->flash('success', 'Project participant added');
        } else {
            session()->flash('error', 'Error, project participant not added. Refresh and try again');
        }
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProjectParticipant  $project
     * @return \Illuminate\Http\Response
     */
    public function updateParticipant(Request $request, ProjectParticipant $project)
    {
        $participant =  ProjectParticipant::where('id', $request->id)->first();
        if (!$participant) {
            session()->flash('error', 'Failed, project participant not exist. Refresh and try again');
            return redirect()->back();
        }
        $updated = ProjectParticipant::where('id', $request->id)->update(['role' => $request->role, 'performance' => $request->performance]);

        if ($updated) {
            session()->flash('success', 'Project participant updated');
        } else {
            session()->flash('error', 'Failed, project participant not updated. Refresh and try again');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectParticipant  $project
     * @return \Illuminate\Http\Response
     */
    public function destroyParticipant(Request $request)
    {
        $participant =  ProjectParticipant::where('id', $request->id)->first();
        if (!$participant) {
            session()->flash('error', 'Failed, project participant not exist. Refresh and try again');
            return redirect()->back();
        }

        $participant_report = Report::where([['userID', $participant->userID], ['projectID', $participant->projectID]])->first();
        if ($participant_report) {
            session()->flash('error', 'Failed, project participant in action.');
            return redirect()->back();
        }
        if ($participant->delete()) {
            session()->flash('success', 'Project participant removed');
        } else {
            session()->flash('error', 'Failed, project participant not removed. Refresh and try again');
        }
        return redirect()->back();
    }
}
