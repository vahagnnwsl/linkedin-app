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
                    <label for="accounts_ids">Accounts</label>
                    <select multiple="multiple" class="select2 form-control" data-placeholder="Select something" id="accounts_ids" name="accounts_ids[]">
                    @foreach($accounts as $account)
                        <option
                            @if(request()->get('accounts_ids') && count(request()->get('accounts_ids')) && in_array($account['id'],request()->get('accounts_ids'))) selected
                            @endif value="{{$account['id']}}">{{$account['text']}}</option>
                        @endforeach
                        </select>

                </div>

                <div class="col-md-4">
                    <label for="keys_ids">Keys</label>
                    <select multiple="multiple" class="select2 form-control" data-placeholder="Select something" id="keys_ids" name="keys_ids[]">
                        @foreach($keys as $key)
                            <option
                                @if(request()->get('keys_ids') && count(request()->get('keys_ids')) && in_array($key['id'],request()->get('keys_ids'))) selected
                                @endif value="{{$key['id']}}">{{$key['text']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="keys_ids">Key</label>
                     <input type="text" class="form-control" name="key" placeholder="Search key" value="{{request()->get('key')}}">

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

        });
    </script>
@endpush
