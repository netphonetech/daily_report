<?php

namespace App\Http\Controllers;

use App\Project;
use App\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
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
        $projects = Project::join('project_participants', 'project_participants.projectID', 'projects.id')->where([['projects.status', true], ['project_participants.userID', auth()->user()->id]])
        ->select('projects.id','projects.name','description','start_date','expected_end_date','end_date')
        ->get();
        $reports = Report::where('reports.status', true)
            ->join('users', 'users.id', 'reports.userID')
            ->join('projects', 'projects.id', 'reports.projectID')
            ->select('reports.id', 'reports.date', 'main_task', 'sub_task', 'next_sub_task', 'extra_notes', 'users.name as user', 'projects.name as project', 'userID', 'projectID')
            ->get();
        return view('report.list', compact('reports', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $report = new Report();

        $report->userID = auth()->user()->id;
        $report->projectID = $request->project;
        $report->date = now();
        $report->main_task = $request->main;
        $report->sub_task = $request->sub;
        $report->next_sub_task = $request->next_sub;
        $report->extra_notes = $request->notes;
        if ($report->save()) {
            session()->flash('success', 'Report submitted');
        } else {
            session()->flash('error', 'Error, report not submitted. Refresh and try again');
        }
        return redirect()->route('list-reports');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $report = Report::where('id', $request->id)->first();
        if (!$report) {
            session()->flash('success', 'Failed, report not exist, refresh and try again');
        }
        return view('report.show', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        $report = Report::where('id', $request->id)->first();
        if (!$report) {
            session()->flash('success', 'Failed, report not exist, refresh and try again');
        }
        $report->projectID = $request->project;
        $report->main_task = $request->main;
        $report->sub_task = $request->sub;
        $report->next_sub_task = $request->next_sub;
        $report->extra_notes = $request->notes;
        $report->updatedBy = auth()->user()->id;
        if ($report->save()) {
            session()->flash('success', 'Report submitted');
        } else {
            session()->flash('error', 'Error, report not submitted. Refresh and try again');
        }
        return redirect()->route('list-reports');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $report = Report::where('id', $request->id)->first();
        if (!$report) {
            session()->flash('success', 'Failed, report not exist, refresh and try again');
        }

        $report->deletedBy = auth()->user()->id;
        $report->status = false;
        if ($report->save()) {
            session()->flash('success', 'Report removed');
        } else {
            session()->flash('error', 'Error, report not removed. Refresh and try again');
        }
        return redirect()->route('list-reports');
    }
}
