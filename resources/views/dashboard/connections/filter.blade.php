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
                                @if(request()->get('keys_ids') && count(request()->get('keys_ids')) && in_array($key->id,request()->get('keys_ids'))) selected
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
                                @if(request()->get('categories') && count(request()->get('categories')) && in_array($category->id,request()->get('categories'))) selected
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
                           value="{{request()->get('key')}}">
                    <hr/>
                    <div class="form-group pl-2">
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="last" name="statuses"
                                       @if(request()->get('statuses') && request()->get('statuses') === 'last') checked @endif
                                >Last status
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="all" name="statuses"
                                       @if(!request()->get('statuses') || (request()->get('statuses') && request()->get('statuses') === 'all')) checked @endif
                                >All statuses
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="clear" name="statuses"
                                       @if(request()->get('statuses') && request()->get('statuses') === 'clear') checked @endif
                                >Ignore
                            </label>
                        </div>
                        <hr/>


                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="last" name="positions"
                                       @if(request()->get('positions') &&  request()->get('positions') === 'last') checked @endif
                                >Last positions
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="all" name="positions"
                                       @if(!request()->get('positions') || (request()->get('positions') && request()->get('positions') === 'all')) checked @endif
                                >All positions
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="clear" name="positions"
                                       @if(request()->get('positions') &&  request()->get('positions') === 'clear') checked @endif>Ignore
                            </label>
                        </div>
                        <hr/>
                        <div class="form-check mt-2">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="occupation" name="search_in[]"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('occupation',request()->get('search_in'))) checked @endif
                                >Occupation
                            </label>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="skills" name="search_in[]"
                                       id="skills"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('skills',request()->get('search_in'))) checked @endif>Skills
                            </label>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row">
                        <label for="keys_ids">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Type name"
                               value="{{request()->get('name')}}">
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="not_answered" name="contact"  @if(request()->get('contact') && request()->get('contact') === 'not_answered') checked @endif>Connection not answered
                            </label>
                        </div>
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="answered" name="contact"  @if(request()->get('contact') && request()->get('contact') === 'answered') checked @endif>Connection  answered
                            </label>
                        </div>
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="month" name="contact"  @if(request()->get('contact') && request()->get('contact') === 'month') checked @endif>Past  30 days
                            </label>
                        </div>
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="request" name="contact" @if(request()->get('contact') && request()->get('contact') === 'request') checked @endif>Connection send request
                            </label>
                        </div>
                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="clear" name="contact"  @if(request()->get('contact') && request()->get('contact') === 'clear') checked @endif>Ignore
                            </label>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">

                        <div class="form-check w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="accounts" name="distance"
                                       @if(request()->get('distance') &&  request()->get('distance') === 'accounts') checked @endif
                                >Only accounts connections
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="no_accounts" name="distance"
                                       @if(request()->get('distance') && request()->get('distance') === 'no_accounts') checked @endif
                                >Only connections that hav not accounts
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="all" name="distance"
                                       @if(!request()->get('distance') || (request()->get('distance') && request()->get('distance') === 'all')) checked @endif
                                >All connections
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="clear" name="distance"
                                       @if(request()->get('distance') && request()->get('distance') === 'clear') checked @endif>Ignore
                            </label>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">

                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="have_keys" name="connections_keys"
                                       @if(request()->get('connections_keys') &&  request()->get('connections_keys') === 'have_keys') checked @endif
                                >Only connections have keys
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="no_keys" name="connections_keys"
                                       @if(request()->get('connections_keys') &&  request()->get('connections_keys') === 'no_keys') checked @endif
                                >Only connections have no keys
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="all" name="connections_keys"
                                       @if(!request()->get('connections_keys') || (request()->get('connections_keys') &&  request()->get('connections_keys') === 'all')) checked @endif
                                >All connections
                            </label>
                        </div>
                        <div class="form-check  w-100">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="radio" class="form-check-input" value="clear" name="connections_keys"
                                       @if(request()->get('connections_keys') &&  request()->get('connections_keys') === 'clear') checked @endif>Ignore
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
                                @if(request()->get('accounts') && count(request()->get('accounts')) && in_array($ac->id,request()->get('accounts'))) selected
                                @endif value="{{$ac->id}}"


                            >{{$ac->full_name}}</option>
                        @endforeach
                    </select>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="form-group w-100">
                            <label for="formControlRange">Experience</label>
                            <input name="experience" type="range" value="{{request()->get('experience')??0}}"
                                   class="form-control-range" id="formControlRange"
                                   onInput="$('#rangeval').html($(this).val()+' years')" min="0" max="10" step="0.5">
                            <span
                                id="rangeval">{{request()->get('experience')?request()->get('experience').' years':''}}<!-- Default value --></span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12 mt-2">

                    <div class="btn-group btn-group-sm float-right">
                        <a href="{{url(request()->path())}}" class="btn btn-default float-right mr-1">Clear</a>
                        <button type="submit" class="btn btn-info float-right"><i class="fa fa-search"></i></button>

                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@push('js')
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script src="/plugins/moment/moment.min.js"></script>

    <script src="/plugins/daterangepicker/daterangepicker.js"></script>


    <script>

        $(function () {

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
