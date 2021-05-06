Vue.component('role-permissions-component', {

    template:`
        <div class="modal fade" id="role__permission" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h3>Choose permissions for <span class="text-blue font-weight-bold">  {{ role.name }}   <i
                                :class="role.icon" class="float-right"></i></span></h3>
                        </div>
                        <div class="card-body">
                            <div class="row form-group ">
                                <div class="form-check col-md-6" :key="index" v-for="(permission,index) in permissions">
                                    <input class="form-check-input" type="checkbox" :id="permission.name"
                                           style="cursor: pointer"
                                           :value="permission.id" v-model="selectedPermissions">
                                    <label class="form-check-label" :for="permission.name"
                                           style="cursor: pointer">{{ permission.name }}</label>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-group float-right">
                                <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
                                <button type="submit" class="btn btn-success" @click="submit"><i
                                    class="fa fa-check-circle mr-2"></i>Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

    `,

    data: function () {
        return {
            selectedPermissions: [],
            roleId: '',
            role: []
        }
    },

    props: ['permissions'],
    mounted() {
        let _this = this
        $(document).on('role_id.update', function (e, response) {
            _this.roleId = response;
            _this.selectedPermissions = [];
            _this.getRole();
        });
    },
    methods: {
        submit: function () {
            if (!this.selectedPermissions.length) {
                alert('Select at last one');
                return;
            }
            this.$http.post(`/dashboard/roles/${this.roleId}`, {permissions: this.selectedPermissions})
                .then((response) => {
                    toastr.success('Successfully added');

                }).catch((error) => {
            })
        },
        getRole() {
            this.$http.get(`/dashboard/roles/${this.roleId}`)
                .then((response) => {
                    this.role = response.data.data;
                    this.selectedPermissions = this.role.permissions;
                }).catch((error) => {
            })
        }

    }
})
