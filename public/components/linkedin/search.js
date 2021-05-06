Vue.component('linkedin-search', {

    template: `
        <div class="row">
        <form @submit.prevent="onsubmit" class="w-100 p-2">
            <div class="row">
                <div class="input-group input-group-md">
                    <input type="text" class="form-control" v-model="key" name="key" required>
                    <button type="submit" class="btn btn-primary btn-flat">Go</button>
                    <span class="error invalid-feedback d-block"
                          ref="attachment-error">{{ errors.first('key') }}</span>
                </div>
            </div>
        </form>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Image</th>
                <th>Full name</th>
                <th>Headline</th>
                <th>PublicIdentifier</th>
                <th>Distance</th>
                <th>Connect</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(profile,index) in profiles" :key="index">
                <td> <img :src="profile.picture"
                          alt="user-avatar"
                          width="50"
                          class="img-circle img-fluid">
                </td>
                <td>{{ profile.fullName }}</td>
                <td>{{ profile.headline }}</td>
                <td>{{ profile.publicIdentifier }}</td>
                <td>{{ profile.secondaryTitle }}</td>
                <td>
                    <a href="javascript:void(0)" @click="setFormParams(profile)"
                       class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </td>
            </tr>

            </tbody>
        </table>
        <div class="modal fade" id="modal-secondary" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-secondary">
                    <form class="w-100" @submit.prevent="sendConnectionRequest">
                        <div class="modal-header">
                            <h4 class="modal-title">Place write something</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group-sm">
                                <textarea class="form-control" rows="3" placeholder="Message" name="message" v-model="form.message" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-light"><i class="fa fa-send"></i> Send</button>
                        </div>
                    </form>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        </div>

    `,

    data: function () {
        return {
            form: {
                profile_id: '',
                tracking_id: '',
                message: '',
            },
            key: '',
            profiles: []
        }
    },
    mounted() {


    },
    methods: {
        onsubmit: function () {


            this.$http.get(`/dashboard/linkedin/connection-search?key=${this.key}`).then((response) => {
                this.profiles = response.data.profiles;
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
        setFormParams: function (profile) {

            this.form.profile_id = profile.publicIdentifier;
            this.form.tracking_id = profile.trackingId;
            this.form.message = '';

            console.log(this.form)
            $('#modal-secondary').modal('show')

        },
        sendConnectionRequest: function () {

            this.$http.post(`/dashboard/linkedin/profiles/invitations`, this.form).then((response) => {
                if (response.data.success) {
                    this.$refs[this.form.profile_id][0].innerHTML = 'PENDING'
                    $('#modal-secondary').modal('hide')

                    toastr.success('Success');

                } else {
                    toastr.error('Something went wrong');
                }
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
    }
})
