Vue.component('send-message', {

    template: `
        <div class="modal fade" id="messageModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-info">
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
            }
        }
    },
    mounted() {
        let _this = this
        $(document).on('sendMessage', function (e, id) {
            $('#messageModal').modal('show')
            _this.connection_id = id;
        });
    },
    methods: {


        submit: function () {
            this.$http.post(`/dashboard/connections/${this.connection_id}/createConversation`, this.form).then(() => {

                this.form.message = '';
                this.connection_id = '';
                $('#messageModal').modal('hide')
                toastr.success('Successfully send');

            }).catch(() => {
                toastr.error('Something went wrong');
            })
        }
    }
})
