Vue.component('send-connection-request', {

    template: `
        <div class="modal fade" id="requestModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-secondary">
                <form class="w-100" @submit.prevent="submit">
                    <div class="modal-header">
                        <h4 class="modal-title">Place write something</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group-sm">
                            <textarea class="form-control" rows="3" placeholder="Message" name="message"
                                      v-model="form.message" required></textarea>
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
        </div>`,

    data: function () {
        return {
            connection_id: '',
            form: {
                message: '',
                trackingId: '',
            }
        }
    },
    mounted() {
        let _this = this
        $(document).on('sendConnectionRequest', function (e, id) {
            _this.connection_id = id;
            _this.getTrackingId(id)
        });
    },
    methods: {

        getTrackingId: function (id) {
            this.$http.get(`/dashboard/connections/${id}/trackingId`)
                .then((response) => {

                    if (response.data.success) {
                        $('#requestModal').modal('show')
                        this.form.trackingId = response.data.trackingId;
                    } else {
                        toastr.error('Something went wrong');
                    }

                }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
        submit: function () {
            this.$http.post(`/dashboard/connections/${this.connection_id}/sendInvitation`, this.form).then(() => {

                this.form.trackingId = '';
                this.form.message = '';
                this.connection_id = '';
                $('#requestModal').modal('hide')
                toastr.success('Successfully send');
                setTimeout(function (){
                    location.reload()
                },1000)

            }).catch(() => {
                toastr.error('Something went wrong');
            })
        }
    }
})
