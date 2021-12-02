@push('css')
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">

@endpush
<div class="card card-info p-2" data-select2-id="32">
    <div class="card-header">
        <h3 class="card-title">Filter</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>

        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body" style="display: block;" data-select2-id="31">
        <div class="col-md-12">
            @foreach($searches as $search)
                <div class="form-check-inline">
                    <label class="form-check-label" style="cursor: pointer">
                        <input type="radio" class="form-check-input" value="{{$search->hash}}" name="search"
                          @if(isset($hash) &&  $hash === $search->hash) checked @endif
                        >{{$search->name}}
                    </label>
                </div>
            @endforeach
            <hr/>
        </div>
        <form method="GET" action="{{url(request()->path())}}">
            <div class="row">
                <div class="col-md-4">
                    <label for="keys_ids">Companies</label>
                    <select multiple="multiple" class="select2Company form-control" data-placeholder="Select something"
                            id="companies"
                            name="companies[]">
                        @foreach($companies as $company)
                            <option value="{{$company->id}}" selected>{{$company->text}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="keys_ids">Keys</label>
                    <select multiple="multiple" class="select2 form-control" data-placeholder="Select something"
                            id="keys_ids" name="keys_ids[]">
                        @foreach($keys as $key)
                            <option
                                @if(isset($req['keys_ids']) && count($req['keys_ids']) && in_array($key->id,$req['keys_ids'])) selected
                                @endif value="{{$key->id}}">{{$key->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="categories">Categories</label>
                    <select multiple="multiple" class="select2Category form-control" data-placeholder="Select something"
                            id="categories" name="categories[]">
                        @foreach($categories as $category)
                            <option
                                @if(isset($req['categories']) && count($req['categories']) && in_array($category->id,$req['categories'])) selected
                                @endif value="{{$category->id}}"
                                data-badge=""

                            >{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <hr/>
                </div>
            </div>

            <div class="row">


                <div class="col-md-4">
                    <label for="keys_ids">Keyword</label>
                    <input type="text" class="form-control" name="key" placeholder="Type keyword"
                           value="{{$req['key']??''}}">
                    <hr/>
                    <div class="form-group pl-2">
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="last" name="statuses"
                                       @if(isset($req['statuses']) && $req['statuses'] === 'last') checked @endif
                                >Last status
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="all" name="statuses"
                                       @if(!isset($req['statuses']) || (isset($req['statuses']) && $req['statuses'] === 'all')) checked @endif
                                >All statuses
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="clear" name="statuses"
                                       @if(isset($req['statuses']) && $req['statuses'] === 'clear') checked @endif
                                >Ignore
                            </label>
                        </div>
                        <hr/>


                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="last" name="positions"
                                       @if(isset($req['positions']) &&  $req['positions'] === 'last') checked @endif
                                >Last positions
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="all" name="positions"
                                       @if(!isset($req['positions']) || ($req['positions'] && $req['positions'] === 'all')) checked @endif
                                >All positions
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="clear" name="positions"
                                       @if(isset($req['positions']) &&  $req['positions'] === 'clear') checked @endif>Ignore
                            </label>
                        </div>
                        <hr/>
                        <div class="form-check mt-2">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="occupation" name="search_in[]"
                                       @if(isset($req['search_in']) && count($req['search_in']) && in_array('occupation',$req['search_in'])) checked @endif
                                >Occupation
                            </label>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="skills" name="search_in[]"
                                       id="skills"
                                       @if(isset($req['search_in']) && count($req['search_in']) && in_array('skills',$req['search_in'])) checked @endif>Skills
                            </label>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row">
                        <label for="keys_ids">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Type name"
                               value="{{$req['name'] ?? ''}}">
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="not_answered" name="contact"  @if(isset($req['contact']) && $req['contact'] === 'not_answered') checked @endif>Connection not answered
                            </label>
                        </div>
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="answered" name="contact"  @if(isset($req['contact']) && $req['contact'] === 'answered') checked @endif>Connection  answered
                            </label>
                        </div>
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="month" name="contact"  @if(isset($req['contact']) && $req['contact'] === 'month') checked @endif>Past  30 days
                            </label>
                        </div>
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="request" name="contact" @if(isset($req['contact']) && $req['contact'] === 'request') checked @endif>Connection send request
                            </label>
                        </div>
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="clear" name="contact"  @if(!isset($req['contact']) || ( isset($req['contact']) && $req['contact'] === 'clear')) checked @endif>Ignore
                            </label>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">

                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="accounts" name="distance"
                                       @if(isset($req['distance']) &&  $req['distance'] === 'accounts') checked @endif
                                >Only accounts connections
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="no_accounts" name="distance"
                                       @if(isset($req['distance']) && $req['distance'] === 'no_accounts') checked @endif
                                >Only connections that have not accounts
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="all" name="distance"
                                       @if(!isset($req['distance']) || (isset($req['distance']) && $req['distance'] === 'all')) checked @endif
                                >All connections
                            </label>
                        </div>

                    </div>
                    <hr/>
                    <div class="row">

                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="have_keys" name="connections_keys"
                                       @if(isset($req['connections_keys']) &&  $req['connections_keys'] === 'have_keys') checked @endif
                                >Only connections have keys
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="no_keys" name="connections_keys"
                                       @if(isset($req['connections_keys']) &&  $req['connections_keys'] === 'no_keys') checked @endif
                                >Only connections have no keys
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="all" name="connections_keys"
                                       @if(!isset($req['connections_keys']) || (isset($req['connections_keys']) &&  $req['connections_keys'] === 'all')) checked @endif
                                >All connections
                            </label>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row">
                    <label for="accounts">Accounts</label>
                    <select multiple="multiple" class="select2 form-control" data-placeholder="Select something"
                            id="accounts" name="accounts[]">
                        @foreach($accounts as $ac)
                            <option
                                @if(isset($req['accounts']) && count($req['accounts']) && in_array($ac->id,$req['accounts'])) selected
                                @endif value="{{$ac->id}}"


                            >{{$ac->full_name}}</option>
                        @endforeach
                    </select>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="form-group w-100">
                            <label for="formControlRange">Experience</label>
                            <input name="experience" type="range" value="{{$req['experience']??0}}"
                                   class="form-control-range" id="formControlRange"
                                   onInput="$('#rangeval').html($(this).val()+' years')" min="0" max="10" step="0.5">
                            <span
                                id="rangeval">{{isset($req['experience'])?$req['experience'].' years':''}}<!-- Default value --></span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12 mt-2">


                    <div class="btn-group btn-group-sm float-right">
                        @if(count($req) && !request()->get('hash'))
                            @if((count($req) === 1 && !isset($req['page'])) || count($req)>1)
                                <a href="#" class="btn btn-default float-right mr-1"  data-toggle="modal" data-target="#saveSearch">Save search</a>
                            @endif
                        @endif
                        <a href="{{url(request()->path())}}" class="btn btn-default float-right mr-1">Clear</a>
                        <button type="submit" class="btn btn-info float-right"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <div class="modal" id="saveSearch">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{route('searches.store')}}">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <input type="hidden" value="{{json_encode($req)}}" name="params">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control" placeholder="Name" required>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default" >Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script src="/plugins/moment/moment.min.js"></script>

    <script src="/plugins/daterangepicker/daterangepicker.js"></script>


    <script>

        $(function () {

            $('input[name=search]').change(function () {

                window.location.href ='{{url(request()->path())}}'+'?hash='+$(this).val()
            });

            $('.select2').select2({
                multiple: true,
            })

            $('.select2Category').select2({
                multiple: true,
                closeOnSelect: false,
                placeholder: "Placeholder",
                allowHtml: true,
                allowClear: true,
                tags: true //
            })

            $('.select2Company').select2({
                multiple: true,
                ajax: {
                    url: '/dashboard/companies',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            type: 'public'
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }
            })

        });
    </script>
@endpush
