Vue.component('connection-full-info', {

    template: `
        <div class="modal fade" id="fullInfoModal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">


            <div class="modal-content">
                <div class="modal-header  bg-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <h4 class="text-blue text-center text-capitalize">profile</h4>
                    <pre id="json-renderer1" class="json-body"></pre>

                    <h4 class="text-blue text-center text-capitalize">skills and positions</h4>
                    <pre id="json-renderer2" class="json-body"></pre>

                    <h4 class="text-blue text-center text-capitalize">opportunity</h4>
                    <pre id="json-renderer3" class="json-body"></pre>


                </div>

            </div>
        </div>
        </div>`,

    data: function () {
        return {

        }
    },
    mounted() {

        let _this = this

        $(document).on('getConnectionFullInfo', function (e, id) {
            $('#json-renderer1').html(null);
            $('#json-renderer2').html(null);
            $('#json-renderer3').html(null);
            _this.getInfo(id);
        });

    },
    methods: {
        getInfo: function (id) {
            this.$http.get(`/dashboard/connections/${id}/fullInfo`).then((data) => {


                $('#json-renderer1').jsonBrowse(data.data.profile,{
                    collapsed:true,
                    withQuotes:true

                });
                $('#json-renderer2').jsonBrowse(data.data.skills,{
                    collapsed:true
                });
                $('#json-renderer3').jsonBrowse(data.data.opportunity,{
                    collapsed:true
                });

                $('#fullInfoModal').modal('show')

            }).catch(() => {
                toastr.error('Something went wrong');
            })
        }
    }
})
