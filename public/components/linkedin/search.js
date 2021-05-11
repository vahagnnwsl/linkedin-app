Vue.component('linkedin-search', {

    template: `

        <form @submit.prevent="onsubmit" class="w-100 p-2">
        <div class="row">
            <div class="col-md-4">
                <select class="form-control" name="key_id"  v-model="form.key_id" required>
                    <option value="" selected  >Select one</option>

                    <option v-for="(item,index) in keys" :value="item.id">{{ item.name }}</option>
                </select>
            </div>

            <div class="col-md-4">
                <select class="form-control" name="company_id" v-model="form.company_id" required>
                    <option value="" selected   >Select one</option>
                    <option v-for="(company,index) in companies" :value="company.id" >{{ company.name }}</option>
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary float-right">Submit</button>
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
            <td><img :src="profile.picture"
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


    `,

    data: function () {
        return {
            form: {
                key_id: '',
                company_id: ''
            },
            profiles: []
        }
    },
    props: ['keys', 'companies'],

    mounted() {



    },
    methods: {
        onsubmit: function () {

            this.$http.get(`/dashboard/search/linkedin`, {
                params: this.form
            }).then((response) => {
                     location.reload()
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        },

    }
})
