@extends('admin.layouts.app')

@section('title')
    {{__('Admin/backend.payment_received')}}
@endsection

@section('content')
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div style="text-align: center;">
                    <h1 class="card-title">{{__('Admin/backend.payment_received')}}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body table table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('Admin/backend.transaction_reference')}}</th>
                            <th>{{__('Admin/backend.order_id')}}</th>
                            <th>{{__('Admin/backend.amount')}}</th>
                            <th>{{__('Admin/backend.description')}}</th>
                            <th>{{__('Admin/backend.first_name')}}</th>
                            <th>{{__('Admin/backend.last_name')}}</th>
                            <th>{{__('Admin/backend.billing_address')}}</th>
                            <th>{{__('Admin/backend.billing_city')}}</th>
                            <th>{{__('Admin/backend.billing_region')}}</th>
                            <th>{{__('Admin/backend.billing_country')}}</th>
                            <th>{{__('Admin/backend.billing_zip')}}</th>
                            <th>{{__('Admin/backend.billing_email')}}</th>
                            <th>{{__('Admin/backend.status')}}</th>
                            <th>{{__('Admin/backend.dated')}}</th>
                            <th>{{__('Admin/backend.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            @if ($payment->response)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if (isset($payment->response['order']))
                                            {{ $payment->response['order']['transaction']['ref'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $payment->order_id }}</td>
                                    <td>{{ $payment->amount }}</td>
                                    <td>{{ $payment->description }}</td>
                                    <td>{{ $payment->billing_fname }}</td>
                                    <td>{{ $payment->billing_sname }}</td>
                                    <td>{{ $payment->billing_address_1 . " " . $payment->billing_address_2 }}</td>
                                    <td>{{ $payment->billing_city }}</td>
                                    <td>{{ $payment->billing_region }}</td>
                                    <td>{{ $payment->billing_country }}</td>
                                    <td>{{ $payment->billing_zip }}</td>
                                    <td>{{ $payment->billing_email }}</td>
                                    <td>{{ $payment->status == 1 ? __('Admin/backend.success') : __('Admin/backend.failed') }}</td>
                                    <td>{{ $payment->created_at }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="" class="btn btn-info btn-sm fa fa-pencil"></a>
                                            <form action="" method="post">
                                                <button onclick="return confirmDelete()" class="btn btn-danger btn-sm fa fa-trash"></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection