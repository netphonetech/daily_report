@extends('layouts.app')
@section('content')
<div class="col-sm-12 form-group">
    <div class="card form-group">
        <div class="card-header"><span class="text-capitalize">Users</span><span
                class="text-capitalize font-italic">&nbsp;list</span>
            <button type="button" class="btn btn-outline-success col-md-2 float-right" data-toggle="modal"
                data-target="#modal-add">
                Add user
            </button>
        </div>
        <div class="card-body form-group">
            <div class="form-group">
                @if (!$users->first())
                <h4 class="text-center">No user added yet</h4>
                @else
                <table class="table table-sm table-striped table-bordered" id="many">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>E-mail address</th>
                            <th>Phone number</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $sno=1;
                        @endphp
                        @foreach ($users as $user)
                        <tr>
                            <td>@php
                                echo $sno;$sno++;
                                @endphp
                            </td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>+255 {{$user->phone}}</td>
                            <td><i
                                    class="badge badge-pill badge-{{$user->admin?'secondary':'primary'}}">{{$user->admin?'Admin':'Normal'}}</i>
                            </td>
                            <td><i
                                    class="badge badge-pill badge-{{$user->status?'success':'warning'}}">{{$user->status?'Active':'Not active'}}</i>
                            </td>
                            <td><button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal"
                                    data-target="#modal-edit-{{ $user->id}}"><i class="fa fa-eye"></i>
                                </button>
                            </td>
                            <td>
                                <form action="{{route('destroy-user')}}" method="post"
                                    onsubmit="return confirm('Delete this user?')">@csrf
                                    <input type="hidden" name="id" value="{{$user->id}}">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- edit modal --}}
                        <div class="modal fade" id="modal-edit-{{$user->id}}" data-backdrop="static"
                            data-keyboard="false" tabindex="-1" role="dialog" data
                            aria-labelledby="modal-edit-{{$user->id}}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-edit-{{$user->id}}Label">User details & update
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="{{ route('update-user') }}"
                                            id="modal-edit-{{$user->id}}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$user->id}}">
                                            <div class="form-group row">
                                                <label for="name"
                                                    class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>

                                                <div class="col-md-9">
                                                    <input id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" required value="{{$user->name}}"
                                                        placeholder="User's name">

                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="phone"
                                                    class="col-md-3 col-form-label text-md-right">{{ __('Phone') }}</label>

                                                <div class="col-md-9">
                                                    <input id="phone" placeholder="eg 0712098765"
                                                        value="0{{ $user->phone }}" autofocus pattern="0[0-9]{9}"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        name="phone" required>

                                                    @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="email"
                                                    class="col-md-3 col-form-label text-md-right">{{ __('E-mail') }}</label>

                                                <div class="col-md-9">
                                                    <input id="email" value="{{ $user->email }}" type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" required>

                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="role"
                                                    class="col-md-3 col-form-label text-md-right">{{ __('User Role') }}</label>

                                                <div class="col-md-9">
                                                    <select class="form-control @error('role') is-invalid @enderror"
                                                        name="role" required>
                                                        <option value="{{ $user->admin?1:0 }}" selected>
                                                            {{ $user->admin?'Administrator':'Normal user' }}
                                                        </option>
                                                        <option value="1">Administrator</option>
                                                        <option value="0">Normal user</option>
                                                    </select>

                                                    @error('role')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-9 offset-3">
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    Add user
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-addLabel">Add new user
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('store-user') }}" id="modal-add">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-9">
                            <input id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                                required value="{{old('name')}}" placeholder="User's name">

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-md-3 col-form-label text-md-right">{{ __('Phone') }}</label>

                        <div class="col-md-9">
                            <input id="phone" placeholder="eg 0712098765" value="{{ old('phone') }}" autofocus
                                pattern="0[0-9]{9}" class="form-control @error('phone') is-invalid @enderror"
                                name="phone" required>

                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('E-mail') }}</label>

                        <div class="col-md-9">
                            <input id="email" value="{{ old('email') }}" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email" required>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role" class="col-md-3 col-form-label text-md-right">{{ __('User Role') }}</label>

                        <div class="col-md-9">
                            <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                                <option value="" selected>-- select role -- </option>
                                <option value="1">Administrator</option>
                                <option value="0">Normal user</option>
                            </select>

                            @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-9 offset-3">
                        <button type="submit" class="btn btn-primary btn-block">
                            Add user
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