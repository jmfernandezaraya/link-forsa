@extends('schooladmin.layouts.app')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body table table-responsive">

<center><h1 class="card-title">{{__('SuperAdmin/backend.school_admin_details')}}</h1></center>
<a href = "{{route('school_admin.create')}}" type="button" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i>&nbsp;{{__('SuperAdmin/backend.add')}}</a>
<table class="table table-hover table-bordered">
<thead>
<tr>
<th>#</th>
<th>{{__('SuperAdmin/backend.first_name')}}</th>
<th>{{__('SuperAdmin/backend.last_name')}}</th>
<th>{{__('SuperAdmin/backend.email')}}</th>
<th>{{__('SuperAdmin/backend.contact_no')}}</th>
<th>{{__('SuperAdmin/backend.profile_image')}}</th>
<th>{{__('SuperAdmin/backend.created_on')}}</th>
<th>{{__('SuperAdmin/backend.action')}}</th>
</tr>
</thead>
<tbody>
@foreach($schools as $school)
<tr>
<td>{{$loop->iteration}}</td>
<td>

	{{ucwords($school->{'first_name_'.get_language()})}}</td>
<td>{{ucwords($school->{'last_name_'.get_language()})}}</td>
<td>{{$school->email == null ? '-----' : $school->email }}</td>




<td>{{ucwords($school->contact)}}</td>
<td>
	@if($school->image != null || $school->image != '')
	<img width = "200px" height = "200px" src = "{{asset($school->image)}}">
	@else
        <img width = "200px" height = "200px" src = "//desk87.com/assets/images/preview-not-available.jpg">

    @endif



	</td>
<td>{{$school->created_at->diffForHumans()}}</td>
<td>
<div class="btn-group">
<a href  = "{{route('school_admin.edit', $school->id)}}" class="btn btn-info btn-sm fa fa-pencil"></a>
 <form action="{{route('school_admin.destroy', $school->id)}}" method="post">
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