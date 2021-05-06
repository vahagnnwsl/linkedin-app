Vue.component('profile-linkedin-search', {

    template: `
        <div data-select2-id="31" class="row" style="display: block;">

        <div class="col-md-12">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-five-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0)" :class="tab==='search'?'active':''"
                               @click="tab='search'">Search</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0)" :class="tab==='sent'?'active':''"
                               @click="tab='sent'">Sended invitation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0)" :class="tab==='received'?'active':''"
                               @click="tab='received'">Received invitation</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-five-tabContent">

                        <div class="row pt-2" v-if="tab==='search'">
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
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column"
                                 v-for="(profile,index) in result" :key="index">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-header text-muted border-bottom-0">
                                        {{ profile.headline }}
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-7">
                                                <h2 class="lead"><b>{{ profile.fullName }}</b>
                                                    <small class="text-blue">{{ profile.secondaryTitle }}</small>
                                                </h2>

                                            </div>
                                            <div class="col-5 text-center">
                                                <img :src="profile.picture" alt="user-avatar"
                                                     class="img-circle img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" v-if="!['1st','You'].includes(profile.secondaryTitle)">

                                        <div class="text-right" :ref="profile.publicIdentifier">
                                            <a href="javascript:void(0)" @click="setFormParams(profile)"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus-circle"></i> Connect
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-2" v-if="tab==='sent'">
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column"
                                 v-for="(invitation,index) in sent_invitations" :key="index">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-header text-muted border-bottom-0">
                                        {{ invitation.profile.occupation }}
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-7">
                                                <h2 class="lead"><b>{{ invitation.profile.fullName }}</b>
                                                    <small class="text-blue">{{ invitation.secondaryTitle }}</small>
                                                </h2>

                                            </div>
                                            <div class="col-5 text-center">
                                                <img :src="invitation.profile.avatar" alt="user-avatar"
                                                     class="img-circle img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="javascript:void(0)"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-reply-all"></i> Resend
                                        </a>
                                        <span class="float-right">Sended: <span
                                            class="text-blue">{{ invitation.sentTime }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-2" v-if="tab==='received'">
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column"
                                 v-for="(invitation,index) in received_invitations" :key="index">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-header text-muted border-bottom-0">
                                        {{ invitation.profile.occupation }}
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-7">
                                                <h2 class="lead"><b>{{ invitation.profile.fullName }}</b>
                                                    <small class="text-blue">{{ invitation.secondaryTitle }}</small>
                                                </h2>

                                            </div>
                                            <div class="col-5 text-center">
                                                <img :src="invitation.profile.avatar" alt="user-avatar"
                                                     class="img-circle img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="btn-group btn-sm">
                                            <button type="button" class="btn btn-success"
                                                    @click="replyInvitation(invitation,'accept')">Accept
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                    @click="replyInvitation(invitation,'reject')">Reject
                                            </button>
                                        </div>
                                        <!--                                        <a href="javascript:void(0)"-->
                                        <!--                                           class="btn btn-sm btn-primary">-->
                                        <!--                                            <i class="fas fa-check-circle"></i> Accept-->
                                        <!--                                        </a>-->
                                        <span class="float-right">Received: <span
                                            class="text-blue">{{ invitation.sentTime }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
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
            tab: 'search',
            key: '',
            show: false,
            resultShow: true,
            sent_invitations: {},
            received_invitations: {},
            result: [],
            form: {
                profile_id: '',
                tracking_id: '',
                message: '',
            }
        }
    },
    props: ['user'],
    mounted() {
        if (this.user.linkedin_password && this.user.linkedin_login) {
            this.show = true;
        }

    },
    watch: {
        tab: function (val) {
            if (val === 'sent') {
                this.getSentInvitation();
            } else if (val === 'received') {
                this.getReceivedInvitations();
            }else {
                this.key = '';
                this.result = [];
            }
        }
    },
    methods: {

        setFormParams: function (profile) {

            this.form.profile_id = profile.publicIdentifier;
            this.form.tracking_id = profile.trackingId;
            this.form.message = '';
            $('#modal-secondary').modal('show')

        },

        fetchOptions: function (search) {
            this.key = search
            console.log(this.key)
        },
        replyInvitation: function (invitation, action) {
            this.$http.post(`/dashboard/linkedin/profiles/invitations/${invitation.entityUrn}/reply`, {
                sharedSecret: invitation.sharedSecret,
                action: action
            }).then(() => {
                toastr.success('Success');
                this.getReceivedInvitations();
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
        getSentInvitation: function () {
            this.$http.get(`/dashboard/linkedin/profiles/invitations/sent`).then((response) => {
                this.sent_invitations = response.data.invitations;
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
        getReceivedInvitations: function () {
            this.$http.get(`/dashboard/linkedin/profiles/invitations/received`).then((response) => {
                this.received_invitations = response.data.invitations;
            }).catch(() => {
                toastr.error('Something went wrong');
            })
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
        onsubmit: function () {

            this.$http.get(`/dashboard/linkedin/profiles/search?key=${this.key}`).then((response) => {
                this.result = response.data.profiles;
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        }
    }
})
