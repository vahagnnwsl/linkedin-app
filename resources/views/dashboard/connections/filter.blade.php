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
                    <select multiple="multiple" class="select2Company form-control" data-placeholder="Select something" id="companies"
                            name="companies[]">
                        @foreach($companies as $company)
                             <option value="{{$company->id}}" selected>{{$company->text}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="keys_ids">Keys</label>
                    <select multiple="multiple" class="select2 form-control" data-placeholder="Select something" id="keys_ids" name="keys_ids[]">
                        @foreach($keys as $key)
                            <option
                                @if(request()->get('keys_ids') && count(request()->get('keys_ids')) && in_array($key->id,request()->get('keys_ids'))) selected
                                @endif value="{{$key->id}}">{{$key->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="categories">Categories</label>
                    <select multiple="multiple" class="select2Category form-control" data-placeholder="Select something" id="categories" name="categories[]">
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
                <div class="col-md-4">
                    <label for="keys_ids">Keyword</label>
                     <input type="text" class="form-control" name="key" placeholder="Type keyword" value="{{request()->get('key')}}">
                    <div class="form-group pl-2">
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="skills" name="search_in[]" id="skills"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('skills',request()->get('search_in'))) checked @endif>Skills
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="last_status" name="search_in[]"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('last_status',request()->get('search_in'))) checked @endif
                                >Last status
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="statuses" name="search_in[]"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('statuses',request()->get('search_in'))) checked @endif
                                >All status
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="occupation" name="search_in[]"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('occupation',request()->get('search_in'))) checked @endif
                                >Occupation
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="last_positions" name="search_in[]"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('last_positions',request()->get('search_in'))) checked @endif
                                >Last positions
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" style="cursor: pointer">
                                <input type="checkbox" class="form-check-input" value="positions" name="search_in[]"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('positions',request()->get('search_in'))) checked @endif
                                >All positions
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="keys_ids">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Type name" value="{{request()->get('name')}}">
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="formControlRange">Experience</label>
                        <input name="experience" type="range" value="{{request()->get('experience')??0}}" class="form-control-range" id="formControlRange" onInput="$('#rangeval').html($(this).val()+' years')" min="0" max="10" step="0.5" >
                        <span id="rangeval">{{request()->get('experience')?request()->get('experience').' years':''}}<!-- Default value --></span>

                    </div>
                </div>
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
                closeOnSelect : false,
                placeholder : "Placeholder",
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
