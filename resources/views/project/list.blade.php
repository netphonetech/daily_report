@extends('layouts.app')
@section('content')
<div class="col-sm-12 form-group">
    <div class="card form-group">
        <div class="card-header"><span class="text-capitalize">Projects</span><span
                class="text-capitalize font-italic">&nbsp;list</span>
            <button type="button" class="btn btn-outline-success col-md-2 float-right" data-toggle="modal"
                data-target="#modal-add">
                Add project
            </button>
        </div>
        <div class="card-body form-group">
            <div class="form-group">
                @if (!$projects->first())
                <h4 class="text-center">No project added yet</h4>
                @else
                <table class="table table-sm table-striped table-bordered" id="many">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Project</th>
                            <th>Start Date</th>
                            <th>Expected End </th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $sno=1;
                        @endphp
                        @foreach ($projects as $project)
                        <tr>
                            <td>@php
                                echo $sno;$sno++;
                                @endphp
                            </td>
                            <td><textarea class="form-control" rows="1">{{$project->name}}</textarea></td>
                            <td>{{date('M j, Y',strtotime($project->start_date))}}</td>
                            <td>{{date('M j, Y',strtotime($project->expected_end_date))}}</td>
                            <td>{{$project->end_date!=NULL?date('M j, Y',strtotime($project->end_date)):"-"}}</td>
                            <td><i
                                    class="badge badge-pill badge-{{$project->completed?'success':'warning'}}">{{$project->completed?'Completed':'In progress'}}</i>
                            </td>
                            <td align="center"><a class="btn btn-outline-info btn-sm"
                                    href="{{ route('show-project',['id'=>$project->id]) }}"><i
                                        class="fa fa-long-arrow-right"></i>
                                </a>
                            </td>
                            <td align="center">
                                <form action="{{route('destroy-project')}}" method="post"
                                    onsubmit="return confirm('Delete this project?')">@csrf
                                    <input type="hidden" name="id" value="{{$project->id}}">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-addLabel">Add new project
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('store-project') }}" id="modal-add">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-8">
                            <textarea id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                                required placeholder="Project title">{{old('name')}}</textarea>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="start" class="col-md-4 col-form-label text-md-right">{{ __('Start Date') }}</label>

                        <div class="col-md-8">
                            <input id="start" type="date" value="{{ old('start_date') }}" required
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
                            <input id="expected_end" type="date" value="{{ old('expected_end_date') }}" required
                                class="form-control @error('expected_end') is-invalid @enderror" name="expected_end">

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
                            <input id="end" type="date" value="{{ old('end_date') }}"
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
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                name="description"
                                placeholder="Optional project description ...">{{old('description')}}</textarea>

                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-9 offset-3">
                        <button type="submit" class="btn btn-primary btn-block">
                            Add project
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