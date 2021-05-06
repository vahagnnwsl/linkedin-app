@extends('layouts.app')
@section('css')


@endsection

@section('content')
    <div class="wrapper" id="app">
        @include('dashboard.includes.nav')
        @include('dashboard.includes.aside')

        <div class="content-wrapper" >
            @yield('sub_content')
        </div>

        <loader></loader>

        <footer class="main-footer">
            <strong>Copyright © 2014-2021 <a href="http://nwslab.com" target="_blank">NWS LAB</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

    </div>

@endsection

@push('js')
    <script src="/components/loader.js"></script>

    <script>
        window.VeeValidate = VeeValidate;

        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


        // Add a request interceptor
        axios.interceptors.request.use(function (config) {
            $(document).trigger('loader.update', true);
            return config;
        }, function (error) {
            $(document).trigger('loader.update', false);
            return Promise.reject(error);
        });

        // Add a response interceptor
        axios.interceptors.response.use(function (response) {

            $(document).trigger('loader.update', false);

            return response;
        }, function (error) {
            $(document).trigger('loader.update', false);

            return Promise.reject(error);
        });


        Vue.prototype.$http = axios

        Vue.use(VeeValidate, {
            events: 'input|change|blur',
        });


        Vue.prototype.$setErrorsFromResponse = function (errorResponse) {
            // only allow this function to be run if the validator exists
            if (!this.hasOwnProperty('$validator')) {
                return;
            }

            // clear errors
            this.$validator.errors.clear();

            // check if errors exist
            if (!errorResponse.hasOwnProperty('errors')) {
                return;
            }

            let errorFields = Object.keys(errorResponse.errors);

            // insert laravel errors

            errorFields.map(field => {
                this.$validator.errors.add({
                    field: field,
                    msg: errorResponse.errors[field][0]
                });
            });

        };
        Vue.component('v-select', VueSelect.VueSelect);

        const bus = new Vue();

        Pusher.logToConsole = true;


        @if(\Illuminate\Support\Facades\Auth::user()->account)
        const pusher = new Pusher('5177f8ebcb023c1c9e3b', {
            cluster: 'ap2'
        });

        const channel = pusher.subscribe('channel.{{\Illuminate\Support\Facades\Auth::user()->account->entityUrn}}');
        @endif

        var app = new Vue({
            el: "#app",
            methods: {}
        });
    </script>
@endpush
