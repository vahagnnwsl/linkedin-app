Vue.component('role-crate-component', {

    template: `
        <div class="modal fade" id="role__create"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Create new role</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit">
                                <input name="name" v-model="form.name" data-vv-as="Name" v-validate="'required'"
                                       class="form-control"
                                       type="text" placeholder="Name">
                                <span class="error invalid-feedback d-block">{{ errors.first('name') }}</span>

                                <br>

                                <input name="icon" v-model="form.icon" data-vv-as="Icon" v-validate="'required'"
                                       class="form-control" type="text" placeholder="Icon">
                                <span class="error invalid-feedback d-block">{{ errors.first('icon') }}</span>

                                <br>

                                <div class="btn-group float-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>`,

    data: function () {
        return {
            form: {
                name: '',
                icon: ''
            }
        }
    },
    methods: {
        submit: function () {
            this.$validator.validate().then(valid => {
                if (valid) {


                    this.$http.post('/dashboard/roles', this.form)
                        .then((response) => {
                            $('#role__create').modal('hide');
                            setTimeout(function () {
                                location.reload();
                            }, 200)
                        }).catch((error) => {
                        this.$setErrorsFromResponse(error.response.data);
                    })
                }

            });
        }
    }
})
