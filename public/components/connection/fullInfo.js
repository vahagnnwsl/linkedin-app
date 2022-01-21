Vue.component('connection-full-info', {

    template: `
        <div class="modal fade" id="fullInfoModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">


            <div class="modal-content">
                <div class="modal-header  bg-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <h4 class="text-blue text-center text-capitalize">
                        profile
                        <button v-on:click="copyCode('json-renderer1')" class="btn btn-primary" style="float: right!important;"  value="1">Copy json</button>
                    </h4>
                    <textarea id="json-renderer1" style="overflow-y: scroll;max-height: 100px;width: 100%">{{ profile }}</textarea>

                    <h4 class="text-blue text-center text-capitalize">
                        skills and positions
                        <button v-on:click="copyCode('json-renderer2')" class="btn btn-primary" style="float: right!important;"  value="1">Copy json</button>

                    </h4>
                    <textarea id="json-renderer2" style="overflow-y: scroll;max-height: 100px;width: 100%">{{ skills }}</textarea>

                    <h4 class="text-blue text-center text-capitalize">
                        opportunity
                        <button v-on:click="copyCode('json-renderer3')" class="btn btn-primary" style="float: right!important;"  value="1">Copy json</button>

                    </h4>
                    <textarea id="json-renderer3" style="overflow-y: scroll;max-height: 100px;width: 100%">{{ opportunity }}</textarea>


                </div>

            </div>
        </div>
        </div>`,

    data: function () {
        return {
            skills: {},
            profile: {},
            opportunity: {},
        }
    },
    mounted() {

        let _this = this

        $(document).on('getConnectionFullInfo', function (e, id) {
            _this.profile = {};
            _this.skills = {};
            _this.opportunity = {};
            _this.getInfo(id);
        });

    },
    methods: {
        getInfo: function (id) {
            this.$http.get(`/dashboard/connections/${id}/fullInfo`).then((data) => {


                this.profile = data.data.profile;
                this.skills = data.data.skills;
                this.opportunity = data.data.opportunity;

                $('#fullInfoModal').modal('show')

            }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
        copyCode: function (id) {


            var copyTextarea = document.getElementById(id);
            copyTextarea.focus();
            copyTextarea.select();

            try {
                var successful = document.execCommand('copy');
                toastr.success(' copied');

            } catch (err) {
                toastr.success('error');
            }
            //this.cStype = 'hidden';
        },
    }
})
