@extends('admin.layouts.app')

@section('title')
    {{__('Admin/backend.school_details')}}
@endsection

@section('content')
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div style="text-align: center;">
                    <h1 class="card-title">{{__('Admin/backend.school_details')}}</h1>
                </div>

                <form method="post" action="{{ auth('superadmin')->check() ? route('superadmin.school.bulk') : route('schooladmin.school.bulk') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="form-group col-md-3">
                            <select name="action" class="custom-select custom-select-sm form-control form-control-sm">
                                <option value="clone">{{__('Admin/backend.clone')}}</option>
                                <option value="play">{{__('Admin/backend.play')}}</option>
                                <option value="pause">{{__('Admin/backend.pause')}}</option>
                                <option value="destroy">{{__('Admin/backend.destroy')}}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <input name="ids" type="hidden" value="" />
                            <button class="btn btn-primary btn-sm" onclick="return (confirm('{{__('Admin/backend.are_you_sure_you_wanna_restore')}}') && DoBulkAction())">Bluk Action</button>
                        </div>
                    </div>
                </form>
                <a href="{{ auth('superadmin')->check() ? route('superadmin.school.create') : route('schooladmin.school.create') }}" type="button" class="btn btn-primary btn-sm pull-right">
                    <i class="fa fa-plus"></i>&nbsp;{{__('Admin/backend.add')}}
                </a>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body table table-responsive">
                <table class="table table-hover table-bordered table-filtered" data-length="25,50,100,-1:25,50,100,All">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th data-filter="checkbox"> {{__('Admin/backend.select')}} </th>
                            <th data-filter="select" data-select="{{implode(",", $choose_fields["names"])}}"> {{__('Admin/backend.name')}} </th>
                            <th> {{__('Admin/backend.email_address')}} </th>
                            <th> {{__('Admin/backend.contact_number')}} </th>
                            <th data-filter="select" data-select="{{implode(",", $choose_fields["countries"])}}"> {{__('Admin/backend.country')}} </th>
                            <th data-filter="select" data-select="{{implode(",", $choose_fields["cities"])}}"> {{__('Admin/backend.city')}} </th>                            
                            <th> {{__('Admin/backend.branch_name')}} </th>
                            <th> {{__('Admin/backend.created_on')}} </th>
                            <th> {{__('Admin/backend.updated_on')}} </th>
                            <th> {{__('Admin/backend.action')}} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schools as $school)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><input type="checkbox" data-id="{{$school->id}}" /></td>
                                <td>
                                    @if ($school->name)
                                        {{ app()->getLocale() == 'en' ? $school->name->name : $school->name->name_ar }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $school->email }}</td>
                                <td>{{ $school->contact }}</td>
                                <td>
                                    @if ($school->country)
                                        {{ app()->getLocale() == 'en' ? $school->country->name : $school->country->name_ar }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($school->city)
                                        {{ app()->getLocale() == 'en' ? $school->city->name : $school->city->name_ar }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ app()->getLocale() == 'en' ? $school->branch_name : $school->branch_name_ar }}
                                </td>
                                <td>{{ $school->created_at->diffForHumans() }}</td>
                                <td>
                                    @if ($school->updated_at)
                                        {{ $school->updated_at->diffForHumans() }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ auth('superadmin')->check() ? route('superadmin.school.edit', $school->id) : route('schooladmin.school.edit', $school->id) }}" class="btn btn-info btn-sm fa fa-pencil"></a>
                                        <form action="{{ auth('superadmin')->check() ? route('superadmin.school.clone', $school->id) : route('schooladmin.school.clone', $school->id) }}" method="post">
                                            @csrf
                                            <button type="submit" onclick="return confirmClone()" class="btn btn-primary btn-sm fa fa-clone"></button>
                                        </form>
                                        @if ($school->is_active)
                                            <form method="post" action="{{ auth('superadmin')->check() ? route('superadmin.school.pause', $school->id) : route('schooladmin.school.pause', $school->id) }}">
                                                @csrf
                                                <button onclick="return confirm('{{__('Admin/backend.are_you_sure_you_wanna_pause')}}')" class="btn btn-secondary btn-sm fa fa-pause"></button>
                                            </form>
                                        @else
                                            <form method="post" action="{{ auth('superadmin')->check() ? route('superadmin.school.play', $school->id) : route('schooladmin.school.play', $school->id) }}">
                                                @csrf
                                                <button onclick="return confirm('{{__('Admin/backend.are_you_sure_you_wanna_play')}}')" class="btn btn-success btn-sm fa fa-play"></button>
                                            </form>
                                        @endif
                                        <form action="{{ auth('superadmin')->check() ? route('superadmin.school.destroy', $school->id) : route('schooladmin.school.destroy', $school->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirmDelete()" class="btn btn-danger btn-sm fa fa-trash"></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection