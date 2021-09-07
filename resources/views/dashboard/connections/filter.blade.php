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
                    <select multiple="multiple" class="select2 form-control" data-placeholder="Select something" id="categories" name="categories[]">
                        @foreach($categories as $category)
                            <option
                                @if(request()->get('categories') && count(request()->get('categories')) && in_array($category->id,request()->get('categories'))) selected
                                @endif value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label for="keys_ids">Keyword</label>
                     <input type="text" class="form-control" name="key" placeholder="Type keyword" value="{{request()->get('key')}}">
                    <div class="form-group pl-2">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" value="skills" name="search_in[]"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('skills',request()->get('search_in'))) checked @endif>Skills
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" value="last_position" name="search_in[]"
                                       @if(request()->get('search_in') && count(request()->get('search_in')) && in_array('last_position',request()->get('search_in'))) checked @endif
                                >Last position
                            </label>
                        </div>
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
