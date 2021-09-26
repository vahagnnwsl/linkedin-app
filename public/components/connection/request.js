Vue.component('send-connection-request', {

    template: `
        <div class="modal fade" id="requestModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="w-100" @submit.prevent="submit">
                    <div class="modal-header  bg-info">
                        <h4 class="modal-title">Place write something</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group-sm">
                            <textarea class="form-control" rows="3" placeholder="Type something" name="message"
                                      v-model="form.message" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="submit" class="btn btn-outline-info"><i class="fa fa-send"></i> Send</button>
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
            }
        }
    },
    mounted() {
        let _this = this
        $(document).on('sendConnectionRequest', function (e, id) {
            _this.connection_id = id;
            $('#requestModal').modal('show')
        });
    },
    methods: {
        submit: function () {
            this.$http.post(`/dashboard/connections/${this.connection_id}/request`, this.form).then(() => {

                this.form.message = '';
                this.connection_id = '';
                $('#requestModal').modal('hide')
                toastr.success('Successfully send');
                // setTimeout(function (){
                //     location.reload()
                // },1000)

            }).catch(() => {
                toastr.error('Something went wrong');
            })
        }
    }
})
