<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{__('Frontend.regsitration_form')}}</title>
    
    <style>
        @if (app()->getLocale() != 'en')
            * { font-family: 'DejaVu Sans', sans-serif; }
            body { direction: rtl; text-align: right; }
        @endif

        .study {
            box-shadow: 0px 0px 2px 1px #ccc;
            padding: 15px 15px;
        }
        .m-0 {
            margin: 0!important;
        }
        .m-2 {
            margin: 0.5rem!important;
        }
        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }
        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }
        .h2, h2 {
            font-size: 2rem;
        }
        .img-fluid {
            max-width: 100%;
            height: auto;
        }
        img {
            vertical-align: middle;
            border-style: none;
        }
        .table {
            width: 100%;
            max-width: 100%;
            color: #212529;
            border-collapse: collapse;
            table-layout: fixed;
            overflow-wrap: break-word;
            margin-bottom: 1rem;
        }
        .table.table-shadow {
            border: 1px solid #dee2e6;
            box-shadow: 0px 0px 2px 1px #ccc;
        }
        .table tr, .table tr td {
            border: none;
            align-items: top;
            vertical-align: top;
        }
        .flex-row {
            display: flex;
            display: -ms-flexbox;
            flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            align-items: center;
            column-gap: 15px;
        }
    </style>
</head>

<body>
    <div class="registration-cancellation-conditions course-details">
        <div class="study m-2">
            <table class="table">
                <tbody>
                    <tr>
                        <th><img src="{{asset('public/frontend/assets/img/logo.png')}}" class="img-fluid" alt="" /></th>
                        <th colspan="4"><h2>{{__('Frontend.registration_cancelation_conditions')}}</h2></th>
                    </tr>
                </tbody>
            </table>
            <div>
                {!! app()->getLocale() == 'en' ? $course_application->registration_cancelation_conditions : $course_application->registration_cancelation_conditions_ar !!}
            </div>
            <div class="flex-row">
                <label for="student_guardian_full_name" class="col-form-label">
                    <strong>{{__('Frontend.student_guardian_full_name')}}</strong>:
                </label>
                <p class="m-0">{{ $course_application->guardian_full_name }}</p>
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <td><strong>{{__('Frontend.date')}}:</strong></td>
                        <td colspan="3">{{ $today }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('Frontend.signature')}}:</strong></td>
                        <td colspan="3"><img src="{{ $course_application->signature }}" class="img-fluid"/></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>