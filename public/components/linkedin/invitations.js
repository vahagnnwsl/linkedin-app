Vue.component('linkedin-send-invitations', {

    template: `
        <div class="row">
         <h2>
             <span class="text-blue float-right">Total: {{invitations.length}}</span>
         </h2>
        <table class="table table-hover" v-if="invitations.length">
            <thead>
            <tr>
                <th>Image</th>
                <th>Full name</th>
                <th>Headline</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(invitation,index) in invitations" :key="index">
                <td> <img :src="invitation.profile.avatar"
                          alt="user-avatar"
                          width="50"
                          class="img-circle img-fluid">
                </td>
                <td>{{ invitation.profile.fullName }}</td>
                <td>{{ invitation.profile.occupation }}</td>
                <td>{{ invitation.sentTime }}</td>
            </tr>

            </tbody>
        </table>
        </div>
    `,

    data: function () {
        return {
            key: '',
            invitations: []
        }
    },
    mounted() {

       this.getSentInvitation();
    },
    methods: {
        getSentInvitation: function () {
            this.$http.get(`/dashboard/linkedin/invitations/sent`).then((response) => {
                this.invitations = response.data.invitations;
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
    }
})
