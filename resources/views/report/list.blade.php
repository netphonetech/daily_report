@extends('layouts.app')
@section('content')
<div class="col-sm-12 form-group">
    <div class="card form-group">
        <div class="card-header"><span class="text-capitalize">Daily Reports</span><span
                class="text-capitalize font-italic">&nbsp;list</span>
            <button type="button" class="btn btn-outline-success col-md-2 float-right" data-toggle="modal"
                data-target="#modal-add">
                Add report
            </button>
        </div>
        <div class="card-body form-group">
            <div class="form-group">
                @if (!$reports->first())
                <h4 class="text-center">No report added yet</h4>
                @else
                <table class="table table-sm table-striped table-bordered" id="many">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Posted By</th>
                            <th>Project</th>
                            <th>Main task</th>
                            <th>Subtask</th>
                            <th>Next sub task</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $sno=1;
                        @endphp
                        @foreach ($reports as $report)
                        <tr>
                            <td>@php
                                echo $sno;$sno++;
                                @endphp
                            </td>
                            <td>{{Carbon\Carbon::parse($report->date)->diffForHumans()}}</td>
                            <td>{{$report->user}}</td>
                            <td><textarea class="form-control" rows="1">{{$report->project}}</textarea></td>
                            <td><textarea class="form-control" rows="1">{{$report->main_task}}</textarea></td>
                            <td><textarea class="form-control" rows="1">{{$report->sub_task}}</textarea></td>
                            <td><textarea class="form-control" rows="1">{{$report->next_sub_task}}</textarea></td>
                            <td><button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal"
                                    data-target="#modal-edit{{ $report->id}}"><i class="fa fa-eye"></i>
                                </button>
                            </td>
                            <td>
                                <form action="{{route('report-destroy')}}" method="post"
                                    onsubmit="return confirm('Delete this report?')">@csrf
                                    <input type="hidden" name="id" value="{{$report->id}}">
                                    @if (Auth::user()->id==$report->userID || Auth::user()->admin)
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endif
                                </form>
                            </td>
                        </tr>

                        {{-- edit modal --}}
                        <div class="modal fade" id="modal-edit{{ $report->id}}" data-backdrop="static"
                            data-keyboard="false" tabindex="-1" role="dialog"
                            aria-labelledby="modal-edit{{ $report->id}}Label" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-edit{{ $report->id}}Label">Edit this report
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-md-6 col-form-label text-md-right">
                                                {{ __('Submitted by ') }} <b>{{$report->user}}</b>
                                            </label>

                                            <label class="col-md-6 col-form-label text-md-right">
                                                {{ __('On ') }} <b>
                                                    {{date("D F j, Y, g:i a",strtotime($report->date))}}</b>
                                                <i>({{Carbon\Carbon::parse($report->date)->diffForHumans()}})</i>
                                            </label>

                                        </div>
                                        <form method="POST" action="{{ route('report-update') }}"
                                            id="modal-edit-{{$report->id}}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$report->id}}">
                                            <div class="form-group row">
                                                <label for="project"
                                                    class="col-md-1 col-form-label text-md-right">{{ __('Project') }}</label>

                                                <div class="col-md-11">
                                                    <select name="project" required autofocus
                                                        class="form-control @error('project') is-invalid @enderror">
                                                        <option value="{{$report->projectID}}" selected>
                                                            {{$report->project}}</option>
                                                        @foreach ($projects as $project)
                                                        <option value="{{$project->id}}" title="{{$project->name}}">
                                                            {{$project->name}}</option>
                                                        @endforeach
                                                    </select>

                                                    @error('project')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label for="main"
                                                            class="col-md-2 col-form-label text-md-right">{{ __('Main Task') }}</label>

                                                        <div class="col-md-10">
                                                            <textarea id="main" rows="2"
                                                                placeholder="On going main task"
                                                                class="form-control @error('main') is-invalid @enderror"
                                                                name="main" required autocomplete="main"
                                                                autofocus>{{ $report->main_task??"" }}</textarea>

                                                            @error('main')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="sub"
                                                            class="col-md-2 col-form-label text-md-right">{{ __('Sub Task') }}</label>

                                                        <div class="col-md-10">
                                                            <textarea id="sub"
                                                                class="form-control @error('sub') is-invalid @enderror"
                                                                rows="3" name="sub" required
                                                                placeholder="Sub task or activity completed today">{{ $report->sub_task??"" }}</textarea>

                                                            @error('sub')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label for="next_sub"
                                                            class="col-md-2 col-form-label text-md-right">{{ __('Next Sub Task') }}</label>

                                                        <div class="col-md-10">
                                                            <textarea id="next_sub"
                                                                class="form-control @error('next_sub') is-invalid @enderror"
                                                                rows="3" name="next_sub" required
                                                                placeholder="Task for the next day">{{ $report->next_sub_task??"" }}</textarea>

                                                            @error('next_sub')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="notes"
                                                            class="col-md-2 col-form-label text-md-right">{{ __('Extra notes') }}</label>

                                                        <div class="col-md-10">
                                                            <textarea id="notes" rows="2" placeholder="Extra notes."
                                                                class="form-control @error('notes') is-invalid @enderror"
                                                                name="notes" required autocomplete="notes"
                                                                autofocus>{{ $report->extra_notes??"" }}</textarea>

                                                            @error('notes')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (Auth::user()->id==$report->userID || Auth::user()->admin) <div
                                                class="col-md-8 offset-2">
                                                <button type="submit" class="btn btn-outline-primary btn-block">
                                                    Save changes made
                                                </button>
                                            </div>
                                            @endif
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        @if (Auth::user()->id==$report->userID || Auth::user()->admin)
                                        <form action="{{route('report-destroy')}}" method="post"
                                            onsubmit="return confirm('Delete this report?')">@csrf
                                            <input type="hidden" name="id" value="{{$report->id}}">
                                            <button type="submit" class="btn btn-danger">DELETE</button>
                                        </form>
                                        @endif
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end edit modal --}}
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- add modal --}}
<div class="modal fade" id="modal-add" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="modal-addLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-addLabel">Add today's report
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('store-report') }}" id="modal-add">
                    @csrf
                    <div class="form-group row">
                        <label for="project" class="col-md-2 col-form-label text-md-right">{{ __('Project') }}</label>

                        <div class="col-md-10">
                            <select name="project" required autofocus
                                class="form-control @error('project') is-invalid @enderror">
                                <option value="" selected></option>
                                @foreach ($projects as $project)
                                <option value="{{$project->id}}" title="{{$project->name}}">
                                    {{$project->name}}</option>
                                @endforeach
                            </select>

                            @error('project')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="main"
                                    class="col-md-2 col-form-label text-md-right">{{ __('Main Task') }}</label>

                                <div class="col-md-10">
                                    <textarea id="main" rows="2" placeholder="On going main task"
                                        class="form-control @error('main') is-invalid @enderror" name="main" required
                                        autocomplete="main" autofocus>{{ old('main_task') }}</textarea>

                                    @error('main')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="sub"
                                    class="col-md-2 col-form-label text-md-right">{{ __('Sub Task') }}</label>

                                <div class="col-md-10">
                                    <textarea id="sub" class="form-control @error('sub') is-invalid @enderror" rows="3"
                                        name="sub" required
                                        placeholder="Sub task or activity completed today">{{old('sub_task')}}</textarea>

                                    @error('sub')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="next_sub"
                                    class="col-md-2 col-form-label text-md-right">{{ __('Next Sub Task') }}</label>

                                <div class="col-md-10">
                                    <textarea id="next_sub" class="form-control @error('next_sub') is-invalid @enderror"
                                        rows="3" name="next_sub" required
                                        placeholder="Task for the next day">{{old('next_sub_task')}}</textarea>

                                    @error('next_sub')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="notes"
                                    class="col-md-2 col-form-label text-md-right">{{ __('Extra notes') }}</label>

                                <div class="col-md-10">
                                    <textarea id="notes" rows="2" placeholder="Extra notes."
                                        class="form-control @error('notes') is-invalid @enderror" name="notes" required
                                        autocomplete="notes" autofocus>{{ old('extra_notes') }}</textarea>

                                    @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 offset-2">
                        <button type="submit" class="btn btn-outline-primary btn-block">
                            Save changes made
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- end add modal --}}
@endsection