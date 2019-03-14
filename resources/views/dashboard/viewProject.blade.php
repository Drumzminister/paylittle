@extends('layouts.dashboardclean')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1 class="p-c font-weight-light">View Project</h1>
                <hr>
            </div>
        </div>
    </div>

    <div class="container my-4">
        <div class="row">
            <div class="col-md-4 col-12">
                <img class="card-img-top img-fluid" src="{{asset($project->photo->projectavatar)}}"
                     alt="Card image cap">
            </div>
            <div class="col-md-7 col-12 offset-md-1 text-secondary">
                <h5>Project Name: </h5>
                <p> {{$project->name}}</p>
                <h5>Project Amount: </h5>
                <p> {{$project->formattedamount}}</p>
                <h5>Project Details: </h5>
                <p class="mr-5 pr-5 text-justify" style="width: 20em; word-break: break-word"> {{$project->details}}<p>
                <h5>Project Duration: </h5>
                <p>{{$project->duration->formattedTimeline}}</p>
                <h5>Project Returns: </h5>
                <p class="mr-5 pr-5 text-justify"> {{$project->formattedreturnspercentage}}<p>
                <br><br>
                @guest
                <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary form-control" data-toggle="modal"
                            data-target="#exampleModal">
                        Sponsor Project
                    </button>
                @else
                    @if(Auth::user()->id != $project->user->id)
                    <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary form-control" data-toggle="modal"
                                data-target="#exampleModal">
                            Sponsor Project
                        </button>
                    @endif
                @endguest
                    <br>
                    @if ($errors->has('amount'))
                        <span class="text-danger" role="alert">
                <strong>{{ $errors->first('amount') }}</strong>
            </span>
                @endif
            </div>
        </div>

        <hr class="mt-4">
        @auth
            @if(Auth::user()->id == $project->user->id)
                <div class="float-right text-white">
                    {{--<a class="btn btn-primary btn-sm badge-pill" data-toggle="modal" data-target="#editModal">--}}
                        {{--Edit Project--}}
                    {{--</a>--}}
                    <a class="btn btn-primary btn-sm badge-pill" href="{{route('userProjects.edit', $project->id)}}">
                        Edit Project
                    </a>
                    <a class="btn btn-danger btn-sm badge-pill " data-toggle="modal" data-target="#deleteModal">
                        Delete Project
                    </a>
                </div>
            @endif
        @endauth


    </div>



    <!-- Sponsor Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sponsor Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{route('sponsor.project', $project->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h5>
                            Project Cost: <small> {{$project->formattedamount}} </small>
                        </h5>
                        <br>
                        <div class="form-group">

                            <label for="sponsoramount">Amount to Sponsor</label>
                            <select name="amount" id="sponsoramount" class="form-control">
                                <option value="null" selected>Sponsorship Amount</option>
                                @foreach($sponsorshipAmounts as $amount)
                                    <option value="{{$amount->amount}}" {{$amountremaining < $amount->amount ? 'disabled' : ''}}>{{$amount->amount}}
                                        Thousand
                                    </option>
                                @endforeach
                                <option id="other" value="others" data ="{{$amountremaining}}">Others</option>
                            </select>
                        </div>

                        <br>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        NGN
                                    </div>
                                </div>
                                <input id="others" type="text"
                                       class="form-control {{ $errors->has('others') ? ' is-invalid' : '' }}"
                                       name="others"  placeholder="Choose Other Above" disabled>

                            </div>
                                <small class="text-danger">Don't Sponsor above NGN{{number_format($amountremaining)}}</small>
                        </div>

                        <div>
                            <h5>Proposed Return Amount</h5>
                            <p id="proposedamount" aria="{{$project->id}}">NGN 0,000 </p>
                            <input type="hidden" name="returns" id="returns">
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Sponsor Project</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit {{$project->name}}'s Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <form action="{{route('userProjects.update', $project->id)}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf @method('put') {{--
                <img src="" id="blah" class="img-fluid">--}}

                    <div class="row text-left my-4">
                        <div class=" offset-1 col-10 mt-4">

                            <div class="form-group py-2">
                                <div>
                                    <label for="name">Project Name</label>
                                    <input id="name" type="text"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           name="name" value="{{ $project->name }}"
                                           required autofocus> @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span> @endif
                                </div>
                            </div>

                            <div class="form-group py-2">
                                <div>
                                    <label for="amount">Proposed Amount</label>
                                    <input id="amount" type="number"
                                           class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                           name="amount" value="{{$project->amount }}"
                                           required> @if ($errors->has('amount'))
                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('amount') }}</strong>
                                                    </span> @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="repayment_id">Repayment Plans</label>
                                <select class="form-control {{ $errors->has('repayment_id') ? ' is-invalid' : '' }}"
                                        id="gender" name="repayment_id">
                                    <option>Select Plan</option>
                                    @foreach($repaymentPlans as $repaymentPlan)
                                        @if($project->repayment_id == $repaymentPlan->id)
                                            <option value="{{$repaymentPlan->id}}"
                                                    selected>{{$repaymentPlan->timeline}}</option>
                                        @endif
                                        <option value="{{$repaymentPlan->id}}">{{$repaymentPlan->timeline}}</option>
                                    @endforeach
                                </select> @if ($errors->has('repayment_id'))
                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('repayment_id') }}</strong>
                                                        </span> @endif
                            </div>
                            <div class="form-group">
                                <label for="duration_id">Project Duration</label>
                                <select class="form-control {{ $errors->has('duration_id') ? ' is-invalid' : '' }}"
                                        id="duration" name="duration_id">
                                    <option>Select Duration</option>
                                    @foreach($durations as $duration)
                                        @if($project->duration_id == $duration->id)
                                            <option value="{{$duration->id}}" selected>{{$duration->timeline}}</option>
                                        @endif
                                        <option value="{{$duration->id}}">{{$duration->timeline}}</option>
                                    @endforeach

                                </select>
                                @if ($errors->has('duration_id'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('duration_id') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group py-2">
                                <div>
                                    <label for="location">Project Location</label>
                                    <input id="location" type="text"
                                           class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                           name="location"
                                           value="{{ $project->location }}"> @if ($errors->has('location'))
                                        <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $errors->first('location') }}</strong>
                                                                                </span> @endif
                                </div>
                            </div>

                            <div class="form-group py-2">
                                <div>
                                    <label for="address">Address</label>
                                    <input id="address" type="text"
                                           class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}"
                                           name="address" value="{{ $project->address }}"
                                           placeholder="Enter Address"> @if ($errors->has('address'))
                                        <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $errors->first('address') }}</strong>
                                                                                </span> @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="project_details">Project Details</label>
                                <textarea style="resize: none;"
                                          class="form-control {{$errors->has('details') ? ' is-invalid' : ''}}"
                                          id="project_details"
                                          rows="6"
                                          name="details">{{ $project->details }}</textarea> @if ($errors->has('details'))
                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('details') }}</strong>
                                                </span> @endif
                            </div>
                            <div class="form-group btn-file">
                                <span class="btn btn-primary btn-file">
                                    <i class="fa fa-camera"
                                       aria-hidden="true"></i>
                                    &nbsp; &nbsp;
                                    Select Avatar <input type="file"
                                                         name="avatarobject">
                                    {{--<img src="#" id="blah" alt="">--}}
                                </span> {{--

                                <p class="btn btn-primary form-control">Select Avatar</p>--}}
                            </div>
                            <div class="form-group py-2">
                                <div class="float-right">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <input type="submit" value="Update Project" class="btn btn-primary">
                                </div>
                            </div>
                        </div>


                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Delete Project Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete {{$project->name}}'s Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <h4>Do you really want to delete this project?</h4>
                    <small>N/B: It will be moved to thrash</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{route('userProjects.delete', $project->id)}}" class="btn btn-primary">Delete Project</a>
                </div>

            </div>
        </div>
    </div>
    <script src="{{ asset('js/hits.js') }}" defer></script>


    @guest
        <script>
            $(document).ready(function () {
                increaseprojectHit("{{$project->id}}");
            });
        </script>
    @else
        @if(Auth::user()->id != $project->user->id)
            <script>
                $(document).ready(function () {
                    increaseprojectHit("{{$project->id}}");
                });
            </script>
        @endif
    @endguest

@endsection