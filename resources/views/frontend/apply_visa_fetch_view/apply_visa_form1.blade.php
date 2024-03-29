@section('content')
    <div class="breadcrumbs" data-aos="fade-in">
        <div class="container">
            <h2>visa form</h2>
            <p>Visa Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text
                of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and
                typesetting industry. </p>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <div class="container mt-5 mb-5">
        <form id="visa-form" method="post" enctype="multipart/form-data" action="{{route('frontend.visa_submit')}}">
            @csrf
            <div class="heading">visa form</div>
            <div class="left">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputapply">@lang('Frontend.visaform.applying_from')</label>
                        <select onchange="getNationality($(this).val())" name='apply_form' class="form-control" id="apply_from_frontend">
                            <option value="">@lang('Admin/backend.select_option') </option>
                            @foreach ($apply_from as $apply_froms)
                                <option value="{{$apply_froms->id}}">{{$apply_froms->{'apply_from_'.get_language() } }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6" id="visa_information">
                        <label for="inputvisa4">Visa information and requirement</label>
                        <textarea readonly class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputvisa1">what is your Nationality</label>
                        <select onchange="getTravel($(this).val())" name="nationality" class="form-control" id="nationality_select_frontend">
                            <option selected value="">@lang('Admin/backend.select_option') </option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputtravel">where do you want to travel?</label>
                        <select onchange="getTypeOfVisa($(this).val())" name="travel" class="form-control" id="travel_select_frontend">
                            <option selected value="">@lang('Admin/backend.select_option') </option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputvisa3">Type of visa</label>
                        <select onchange="getApplicationCenter($(this).val())" name="type_of_visa" data-url="{{route('frontend.visa_details')}}" class="form-control" id="type_of_visa">
                            <option selected value="">@lang('Admin/backend.select_option') </option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputappliccation">@lang('Frontend.visaform.choose_visa_application_center')</label>
                        <select onchange="getNumberOfPeople($(this))" name="visa_center" class="form-control mt-2" id="visa_select_frontend">
                            <option selected value="">@lang('Admin/backend.select_option') </option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="exampleFormControlSelect1">Enter Number of People</label>
                        <select name="people_select" data-url="{{route('frontend.visa_details')}}" onchange="getVisaDetails($(this))" class="form-control" id="getPeople">
                            <option selected value="">{{__('Admin/backend.select_option')}}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row" id="renderMe"></div>

                <div class="">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>VISA Fee</td>
                                <td id="visa_fee">0 SAR</td>
                            </tr>
                            <tr id="medical_insurance_fee">
                                <td>Medical Insurance Fee</td>
                                <td id="medical_fee">0 SAR</td>
                            </tr>
                            <tr id="other_service_fees_get_a" class="d-none">
                            </tr>
                            <tr>
                                <td>Visa Services fee</td>
                                <td id="service_fee">0 SAR</td>
                            </tr>
                            <tr>
                                <td>TAX</td>
                                <td id="tax">0 SAR</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="total mt-3">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="best float-right">Total</h6>
                            </div>
                            <div class="col-md-4 text-right">
                                <h6 id="total">0 SAR</h6>
                                <input hidden id="total_value" name="total_value" value="">
                                <button type="submit" class="btn btn-primary">@lang('Admin/backend.apply_now')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form-builder -->
            <input hidden id="form_id" name="form_id">
        </form>
    </div>
    @section('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="//formbuilder.online/assets/js/form-builder.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.4.2/form-render.min.js"></script>
        <script>
            function fetchFormBuilder(form_data) {
                var formRenderOptions = {
                    formData: form_data
                };
                var frInstance = $('#renderMe').formRender(formRenderOptions);

                $(".rendered-form").addClass('form-row');
                $(".form-group").addClass('col-md-6');
            }

            function getVisaDetails(object) {
                if ($(object).val() != '') {
                    var applyfrom = $("#apply_from_frontend").val();
                    if (applyfrom == '') {
                        alert("Select Where You Are applying from")
                        return;
                    }
                    var visa_center = $("#visa_select_frontend").val();
                    var nationality = $("#nationality_select_frontend").val();
                    var travel = $("#travel_select_frontend").val();
                    var visa = $("#type_of_visa").val();
                    var people = $(object).val();
                    var urlname = $(object).attr('data-url');

                    if (visa_center == '') {
                        alert("Select Visa Center")
                        return;
                    }
                    if (nationality == '') {
                        alert("Select Nationality")
                        return;
                    }
                    if (travel == '') {
                        alert("Select Where You Travel")
                        return;
                    }
                    if (people == '') {
                        alert("Select No. Of People");
                        return;
                    }

                    $.ajax({
                        type: 'POST',
                        url: urlname,
                        data: {
                            _token: $("meta[name='csrf-token']").attr('content'),
                            applyfrom: applyfrom,
                            visa_center: visa_center,
                            nationality: nationality,
                            travel: travel,
                            visa: visa,
                            people: people
                        },

                        success: function (data) {
                            $("#loader").hide();
                            if (data.success) {
                                $("#visa_fee").text(data.visa_fee + " SAR");
                                $("#service_fee").text(data.visa_service_fee + " SAR");
                                $("#medical_fee").text(data.insurance_fee + " SAR");
                                $("#other_fee").text(data.otherprice + " SAR");
                                $("#tax").text(data.tax_fee + " SAR");

                                total_price = data.visa_fee + data.visa_service_fee + data.insurance_fee + data.otherprice + data.tax_fee;

                                $('#total').text(total_price + " SAR");
                                $('#total_value').val(total_price);
                                $("#form_id").val(data.form_id);

                                $('.alert-success').show();
                                $('.alert-success p').html(data.data);

                                // document.getElementById('visa_information').innerhtml = data.information;
                                $("#visa_information").html(data.information);
                                $("#visa_fee").val(data.visa_fee);
                                $("#insurance_fee").val(data.insurance_fee);
                                $("#visa_service_fee").val(data.visa_service_fee);
                                $("#tax_fee").val(data.tax_fee);
                                $("#other_service_fees_get_a").replaceWith(data.other_service_name);

                                if (data.fetch_form_builder) {
                                    fetchFormBuilder(data.fetch_form_builder);
                                }
                            } else if (data.errors) {
                                $('.alert-danger').show();
                                $('.alert-danger ul').html('');
                                for (var error in data.errors) {
                                    $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>');
                                }
                            }
                        },
                        error: function (data) {
                            $("#loader").hide();

                            var rees = JSON.parse(data.responseText);
                            $('.alert-danger').show();
                            $('.alert-danger ul').html('');
                            console.log(rees);
                            for (var error in rees.errors) {
                                $('.alert-danger ul').append('<li>' + rees.errors[error] + '</li>');
                            }
                        }
                    });
                }
            }

            function getNumberOfPeople(object) {
                if ($(object).val() != '') {
                    var applyfrom = $("#apply_from_frontend").val();
                    if (applyfrom == '') {
                        alert("Select Where You Are applying from")
                        return;
                    }
                    var visa_center = $(object).val();
                    var nationality = $("#nationality_select_frontend").val();
                    var travel = $("#travel_select_frontend").val();
                    var visa = $("#type_of_visa").val();
                    var people = $('#no_of_people').val();
                    var urlname = $(object).attr('data-url');

                    if (visa_center == '') {
                        alert("Select Visa Center")
                        return;
                    }
                    if (nationality == '') {
                        alert("Select Nationality")
                        return;
                    }
                    if (travel == '') {
                        alert("Select Where You Travel")
                        return;
                    }
                    if (people == '') {
                        alert("Select No. Of People");
                        return;
                    }

                    $.ajax({
                        type: 'POST',
                        url: "{{route('frontend.get_number_of_people')}}",
                        data: {
                            _token: $("meta[name='csrf-token']").attr('content'),
                            applyfrom: applyfrom,
                            visa_center: visa_center,
                            nationality: nationality,
                            travel: travel,
                            visa: visa
                        },

                        success: function (data) {
                            $("#loader").hide();
                            if (data.success) {
                                $("#getPeople").html(data.option);
                                $('.alert-success').show();
                                $('.alert-success p').html(data.data);

                                // document.getElementById('visa_information').innerhtml = data.information;
                                $("#visa_information").html(data.information);
                                $("#visa_fee").val(data.visa_fee);
                                if (data.insurance_fee != 0) {
                                    $("#medical_insurance_fee").show();
                                    $("#insurance_fee").val(data.insurance_fee);
                                }
                                if (data.visa_service_fee != 0) {
                                    $("#other_service_fee").show();
                                    $("#visa_service_fee").val(data.visa_service_fee);
                                }
                                $("#tax_fee").val(data.tax_fee);
                                $("#fetch_other_fees").html(data.other_fees);

                                if (data.fetch_form_builder) {
                                    fetchFormBuilder(data.fetch_form_builder);
                                }
                            } else if (data.errors) {
                                $('.alert-danger').show();
                                $('.alert-danger ul').html('');
                                for (var error in data.errors) {
                                    $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>');
                                }
                            }
                        },
                        error: function (data) {
                            $("#loader").hide();

                            var rees = JSON.parse(data.responseText);

                            $('.alert-danger').show();
                            $('.alert-danger ul').html('');
                            console.log(rees);
                            for (var error in rees.errors) {
                                $('.alert-danger ul').append('<li>' + rees.errors[error] + '</li>');

                            }
                        }
                    });
                }
            }

            function getNationality(value) {
                var urlname="{{url('getNationality')}}" + "/" + value;
                $.get(urlName, {}, function (data) {
                    $("#nationality_select_frontend").html(data.option);
                });
            }

            function getTravel(value) {
                var urlname="{{url('getTravel')}}" + "/" + value + "/" + $("#apply_from_frontend").val();
                $.get(urlName, {}, function (data) {
                    $("#travel_select_frontend").html(data.option);
                });
            }

            function getApplicationCenter(value) {
                var urlname="{{url('getApplicationCenter')}}" + "/" + value + "/" + $("#apply_from_frontend").val();
                $.get(urlName, {}, function (data) {
                    $("#visa_select_frontend").html(data.option);
                });
            }

            function getTypeOfVisa(value) {
                var urlname="{{url('getTypeOfVisa')}}" + "/" + value + "/" + $("#apply_from_frontend").val();
                $.get(urlName, {}, function (data) {
                    $("#type_of_visa").html(data.option);
                });
            }
        </script>
    @endsection
@endsection
