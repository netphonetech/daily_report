@extends('layouts.app')
@section('content')
<div class="col-sm-12 form-group">
    <div class="card form-group">
        <div class="card-header">
            <a class="btn btn-outline-dark" href="{{ route('list-projects')}}"><i class="fa fa-long-arrow-left"></i>
                Back</a>
            <span class="text-capitalize">Projects</span><span
                class="text-capitalize font-italic">&nbsp;list</span><button type="button"
                class="btn btn-outline-success float-right" data-toggle="modal" data-target="#modal-add">
                Add participant
            </button>
        </div>
        <div class="card-body form-group">
            <div class="form-group row">
                <div class="col-md-5">
                    <form method="POST" action="{{ route('update-project') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$project->id}}">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-8">
                                <textarea id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                                    required placeholder="Project title">{{ $project->name }}</textarea>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="start"
                                class="col-md-4 col-form-label text-md-right">{{ __('Start Date') }}</label>

                            <div class="col-md-8">
                                <input id="start" type="date" value="{{$project->start_date }}" required
                                    class="form-control @error('start') is-invalid @enderror" name="start">

                                @error('start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="expected_end"
                                class="col-md-4 col-form-label text-md-right">{{ __('Expected End') }}</label>

                            <div class="col-md-8">
                                <input id="expected_end" type="date" value="{{ $project->expected_end_date }}" required
                                    class="form-control @error('expected_end') is-invalid @enderror"
                                    name="expected_end">

                                @error('expected_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="end"
                                class="col-md-4 col-form-label text-md-right">{{ __('Exact End Date') }}</label>

                            <div class="col-md-8">
                                <input id="end" type="date" value="{{ $project->end_date }}"
                                    class="form-control @error('end') is-invalid @enderror" name="end">

                                @error('end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description"
                                class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-8">
                                <textarea id="description"
                                    class="form-control @error('description') is-invalid @enderror" name="description"
                                    placeholder="Optional participant description ...">{{ $project->description }}</textarea>

                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8 offset-4 form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                Update project details
                            </button>
                        </div>
                    </form>
                    <div class="row offset-2">
                        <div class="col-md-6 form-group">
                            <form method="POST" action="{{ route('destroy-project') }}"
                                onsubmit="return confirm('Are you sure to delete? This action is not reversible!')">
                                @csrf
                                <input type="hidden" name="id" value="{{$project->id}}">
                                <button type="submit" class="btn btn-danger btn-block">
                                    Delete This Project
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('mark-complete') }}"
                                onsubmit="return confirm('Is the project finished?')">
                                @csrf
                                <input type="hidden" name="id" value="{{$project->id}}">
                                <button type="submit" class="btn btn-success btn-block">
                                    Mark Completed
                                </button>
                            </form>
                        </div>
                    </div>


                </div>
                <div class="col-md-7">
                    @if (!$participants->first())
                    <h4 class="text-center">No participant added yet</h4>
                    @else
                    <table class="table table-sm table-striped table-bordered form-group" id="many">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Reports</th>
                                <th>Performance</th>
                                <th>Started</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $sno=1;
                            @endphp
                            @foreach ($participants as $participant)
                            <tr>
                                <td>@php
                                    echo $sno;$sno++;
                                    @endphp
                                </td>
                                <td>{{$participant->name}}</td>
                                <td>{{$participant->role}}</td>
                                <td>@php
                                    $reports=App\Report::where([['userID',$participant->userID],['projectID',$project->id]])->count();
                                    echo $reports;
                                    @endphp
                                </td>
                                <td>{{$participant->performance}}</td>
                                <td>{{$participant->created_at!=NULL?date('M j, Y',strtotime($participant->created_at)):"-"}}
                                </td>
                                <td align="center"><button type="button" class="btn btn-outline-success btn-sm"
                                        data-toggle="modal" data-target="#modal-edit-{{$participant->id}}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                                <td align="center">
                                    <form action="{{route('destroy-project-participant')}}" method="post"
                                        onsubmit="return confirm('Delete this participant?')">@csrf
                                        <input type="hidden" name="id" value="{{$participant->id}}">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- edit-{{$participant->id}} modal --}}
                            <div class="modal fade" id="modal-edit-{{$participant->id}}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" role="dialog"
                                aria-labelledby="modal-edit-{{$participant->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal-edit-{{$participant->id}}Label">Update
                                                participant details
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('update-project-participant') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$participant->id}}">
                                                <div class="form-group row">
                                                    <label for="role"
                                                        class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

                                                    <div class="col-md-8">
                                                        <input value="{{ $participant->name??"" }}" readonly
                                                            class="form-control @error('role') is-invalid @enderror">

                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="role"
                                                        class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

                                                    <div class="col-md-8">
                                                        <input id="role" type="text"
                                                            value="{{ $participant->role??"" }}" required
                                                            class="form-control @error('role') is-invalid @enderror"
                                                            name="role">

                                                        @error('role')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="performance"
                                                        class="col-md-4 col-form-label text-md-right">{{ __('Performance') }}</label>

                                                    <div class="col-md-8">
                                                        <input id="performance" type="text" name="performance"
                                                            value="{{ $participant->performance??"" }}"
                                                            class="form-control @error('performance') is-invalid @enderror">

                                                        @error('performance')
                                                        <span class="invalid-feedback" performance="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-8 offset-4">
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        Update participant
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- end edit-{{$participant->id}} modal --}}
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- add modal --}}
<div class="modal fade" id="modal-add" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="modal-addLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-addLabel">Add new participant
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('store-project-participant') }}">
                    @csrf
                    <input type="hidden" name="project" value="{{$project->id}}">
                    <div class="form-group row">
                        <label for="user" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-8">
                            <select class="form-control @error('user') is-invalid @enderror" name="user" required>
                                <option value="" selected></option>
                                @foreach ($users as $user)
                                <option value="{{$user->id}}" title="{{$user->email}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                            @error('user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

                        <div class="col-md-8">
                            <input id="role" type="text" value="{{ old('role') }}" required
                                class="form-control @error('role') is-invalid @enderror" name="role">

                            @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-8 offset-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            Add participant
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