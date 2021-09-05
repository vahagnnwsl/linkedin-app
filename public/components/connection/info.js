Vue.component('connection-info', {

    template: `
        <div class="modal fade" id="infoModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">


            <div class="modal-content">
                <div class="modal-header  bg-info">
                    <h4 class="modal-title">{{ fullName }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row" v-if="skills.length">
                        <h5 class="text-bold pl-2 text-info">Skills</h5>
                        <div class="col-12">
                            <a href="#" class="btn btn-outline-info text-bold text-black-50 ml-1 mt-1"
                               v-for="(skill,index ) in skills">
                                {{ skill.name }} <span class="badge badge-info"
                                                       style="font-size: 15px">{{ skill.pivot.like_count }}</span>
                            </a>
                        </div>
                    </div>
                    <hr/>
                    <div class="row" v-if="positions.length">
                        <h5 class="text-bold pl-2 text-info">Positions</h5>
                        <div class="col-12 border"  v-for="(position,index ) in positions">
                            <h5 class="text-bold text-black-50" v-if="position.company">{{ position.company.name }}</h5>
                            <p class="mt-2"><mark>{{position.name }}</mark>
                                <em v-if="position.start_date">{{ position.start_date }}</em>
                                –
                                <em  v-if="position.end_date">{{ position.end_date }}</em>
                                <em  v-else>Now</em>
                                <em class="float-right">  {{ position.duration }}</em>
                            </p>
                            <p><em>{{position.description}}</em></p>
                        </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>`,

    data: function () {
        return {
            skills: [],
            positions: [],
            fullName: '',
        }
    },
    mounted() {

        let _this = this

        $(document).on('getConnectionInfo', function (e, id) {
            _this.connection_id = id;
            _this.positions = [];
            _this.skills = [];
            _this.getInfo(id);
        });

    },
    methods: {
        getInfo: function (id) {
            this.$http.get(`/dashboard/connections/${id}/getInfo`).then((data) => {
                this.fullName = data.data.fullName;
                this.skills = data.data.skills;
                this.positions = data.data.positions;
                $('#infoModal').modal('show')

            }).catch(() => {
                toastr.error('Something went wrong');
            })
        }
    }
})
