Vue.component('profile-linkedin-chat', {
    template: `
        <div class="row gutters">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

            <div class="card m-0">
                <div class="row no-gutters">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3">
                        <div class="users-container p-2 border">
                            <div class="chat-search-box">
                                <div class="input-group">
                                    <input class="form-control" placeholder="Search"
                                           @keyup="filterThreads">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-info">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" title="Get invitations">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
<!--                                        <button type="button" class="btn btn-tool" title="Sync conversation list"-->
<!--                                                @click="syncConversations">-->
<!--                                            <i class="fas fa-sync"></i>-->
<!--                                        </button>-->
                                    </div>
                                </div>
                            </div>

                            <ul class="users border" style="max-height: 685px;overflow-y: scroll">

                                <li class="person" style="position: relative"
                                    :class="selectedConversation.id === thread.id?'active_1':''"
                                    data-chat="person1" v-for="(thread,index) in threads"
                                    @click="getConversation(thread)" :key="index"
                                    :ref="'thread_'+thread.entityUrn"
                                >
                                    <div class="user">
                                        <img
                                            :src="thread.connection.image??'/dist/img/avatar3.png'">
                                    </div>
                                    <p class="name-time">
                                        <span class="name">{{thread.connection.firstName }}
                                            {{ thread.connection.lastName }}
                                        </span>
                                        <br/>
                                        <span style="font-size: 11px;margin-right: 5px">
                                           <i class="fa fa-clock" style="color: grey"></i>
                                       </span>
                                        <span class="lastActivityAt"
                                              style="font-size: 11px;margin-right: 5px">{{ thread.lastActivityAt_diff }}</span>

                                    </p>


                                    <span style="position: absolute;right: 5px;bottom: 5px;display: none"
                                          class="badge badge-danger new-message" :ref="thread.entityUrn">new</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-9 col-9">
                        <div class="selected-user " style="position: relative;line-height: 30px">
                            <span class=" align-middle"> <strong
                                style="font-size: 1.5rem;color: grey">{{ selectedConversation.fullName }}</strong></span>
                            <div class="float-right">
                                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false"
                                   style="position: absolute;top: 10px;right: 15px">
                                    <i class="fas fa-ellipsis-v font-weight-bold"></i>
                                    <span class="badge badge-warning navbar-badge "></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                                     style="left: inherit; right: 0px;">
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" @click="syncMessages(selectedConversation.id)" class="dropdown-item">
                                        <i class="fas fa-sync-alt mr-2"></i> Sync with Linkedin
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="chat-container">
                            <div class="card direct-chat direct-chat-primary" style="background-color: #f7f7f7">

                                <div class="card-body">
                                    <div class="direct-chat-messages" id="messages"
                                         style="min-height: 675px;overflow-y: scroll">

                                        <div class="direct-chat-msg" v-for="(message,key) in messages" :key="key">

                                            <img class="direct-chat-img" style="margin-top: 8px;">
                                            <div class="direct-chat-text"
                                                 style="border: none;background: #f7f7f7;position: inherit;border: 1px solid #f7f7f7">
                                                <p style="margin-bottom: 0!important;line-height: 1.42857">
                                                    <span style="font-weight: 600;margin-right: 10px">{{ messageHelper(message).userFullName }}</span>
                                                    <span style="font-size: 12px"><i class="fa fa-clock" style="color: grey"></i> </span>
                                                    <span v-if="!message.status" class="float-right"
                                                          style="font-size: 15px">
                                                        <i class="fas fa-reply text-blue no-send-message"
                                                           style="cursor: pointer" title="Resend"
                                                           @click="resendMessage(message)"></i>
                                                        <i class="fas fa-info-circle text-red"
                                                           title="Message is not sennded"></i>
                                                     </span>
                                                    <span class="direct-chat-timestamp"><small>{{ messageHelper(message).date_diff }}</small></span>
                                                </p>
                                                <p> {{ messageHelper(message).text }}</p>
                                                <p v-if="messageHelper(message).media">
                                                    <img :src="messageHelper(message).media.url">
                                                </p>
                                                <p v-if="messageHelper(message).attachments">
                                                    <a :href="messageHelper(message).attachments.reference" v-if="messageHelper(message).attachments.mediaType.includes('application')"> {{messageHelper(message).attachments.name}}</a>
                                                    <img v-else :src="messageHelper(message).attachments.reference">
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <form @submit.prevent="submit">
                                            <div class="input-group">
                                                <input type="text" name="message" placeholder="Type Message ..."
                                                       class="form-control" v-model="message"
                                                       v-validate="'required|min:3'">
                                                <span class="input-group-append">
                                                <button type="submit" class="btn btn-primary">Send</button>
                                                </span>
                                            </div>
                                            <span class="error invalid-feedback d-block"
                                                  ref="attachment-error">{{ errors.first('message') }}</span>
                                        </form>
                                    </div>
                                </div>
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
            messages: [],
            entityUrn: '',
            conversation_id: '',
            message: '',
            filterKey: '',
            defThreads: {},
            selectedConversation: {
                fullName: '',
                avatar: '',
                entityUrn: '',
                id: ''
            },
            hash: ''

        }
    },
    props: ['threads', 'user'],
    mounted() {

        console.log(this.user)

        this.defThreads = this.threads;


        console.log( this.threads)

        if (this.threads.length) {
           this.getConversation(this.threads[0]);
        }

        let _this = this;


        channel.bind('sync-conversations', function (data) {
            _this.getThreads();
            _this.getConversation();
            $('.person').first().find('.new-message').css('display','block')

        });

        channel.bind('newMessage', function (data) {

            _this.$refs['thread_' + data.conversation_entityUrn][0].getElementsByClassName('lastActivityAt')[0].innerHTML = data.date_diff;

            _this.threads = _this.threads.map(function (item) {
                if (item.entityUrn === data.conversation_entityUrn) {
                    item.lastActivityAt = data.date;
                }
                return item;
            })

            _this.sortThreads();

            if (_this.selectedConversation.entityUrn === data.conversation_entityUrn) {
                _this.messages[data.hash] = data
                _this.messages = {..._this.messages}
                _this.scroll(data.conversation_entityUrn);
            }

            $('.person').first().find('.new-message').css('display','block')

            if (data.user_entityUrn && data.user_entityUrn !== _this.user.linkedin_urn_id) {
                _this.audio();
            }

            console.log(data,1)

        });

    },

    methods: {
        audio: function () {
            let snd = new Audio('/juntos.mp3');
            snd.play();
        },
        filterThreads: function (event) {

            this.sortThreads(event.target.value);

        },
        sortThreads: function (key = '') {
            this.threads = this.defThreads;
            let a = this.threads;

            if (key.length) {
                a = this.threads.filter(function (item) {
                    let con = false;

                    if (item.connection.firstName.toLowerCase().includes(key.toLowerCase()) || item.connection.lastName.toLowerCase().includes(key.toLowerCase())) {
                        con = true;
                    }
                    return con;
                });
            }

            this.threads = a.sort(function (a, b) {
                let c = new Date(a.lastActivityAt);

                let d = new Date(b.lastActivityAt);

                return d - c;
            });
        },
        messageHelper: function (message) {

            let newMessage = {};
            newMessage.text = message.text
            newMessage.media = message.media;
            newMessage.attachments = message.attachments;
            newMessage.date_diff = message.date_diff
            if (message.connection  && Object.keys(message.connection).length){
                newMessage.userFullName = message.connection.firstName + ' ' + message.connection.lastName;
                newMessage.userAvatar = message.connection.image ?? '/dist/img/avatar5.png';
            } else {
                newMessage.userFullName = this.user.full_name;
                newMessage.userAvatar = '/dist/img/avatar5.png';
            }

            return newMessage;
        },
        submit: function () {

            this.$validator.validate().then(valid => {

                if (valid) {
                    this.$http.post(`/dashboard/linkedin/message`, {
                        text: this.message,
                        conversation_id: this.selectedConversation.id
                    }).then((response) => {
                        this.message = '';
                        this.$validator.reset();
                        this.messages[response.data.message.hash] = response.data.message
                        this.scroll(response.data.message.conversation_entityUrn);
                        this.threads = this.threads.map(function (item) {
                            if (item.entityUrn === response.data.message.conversation_entityUrn) {
                                item.lastActivityAt = response.data.message.date;
                            }
                            return item;
                        })
                        this.sortThreads();

                    })
                        .catch(() => {
                            toastr.error('Something went wrong');
                        })
                }
            })
        },
        scroll: function (id) {
            let _this = this;
            setTimeout(function () {
                var objDiv = document.getElementById("messages");
                objDiv.scrollTop = objDiv.scrollHeight;
                _this.$refs[id][0].style.display = 'none'

            }, 1000)
        },
        getParticipant(members) {
            let _this = this
            return members.filter(function (member) {
                return member &&  member.entityUrn !== _this.user.linkedin_entityUrn
            })
        },
        resendMessage: function (message) {
            this.$http.post(`/dashboard/linkedin/conversations/${this.selectedConversation.id}/messages/${message.id}/resend`)
                .then((response) => {
                    if (response.data.message) {

                        this.messages[response.data.message.hash] = response.data.message;
                    }
                    toastr.success('Successfully resend');
                }).catch(() => {
                toastr.error('Something went wrong');

            })
        },
        mapMessagesChangeStatus: function (data) {
            return this.messages.map(function (item, key) {
                let i = item;
                if (item.entityUrn === data.entityUrn) {
                    i = data;
                }
                return i;
            });
        },
        getConversation: function (thread = {}, syncData = []) {


            if (Object.keys(thread).length) {
                let participant = thread.connection;
                this.selectedConversation.fullName = participant.firstName + ' ' + participant.lastName;
                this.selectedConversation.avatar = participant.picture ?? '/dist/img/avatar3.png';
                this.selectedConversation.entityUrn = thread.entityUrn;
                this.selectedConversation.id = thread.id;
            }

            console.log(thread)
            console.log(this.selectedConversation)


            this.$http.get(`/dashboard/conversations/${this.selectedConversation.id}/messages`)
                .then((response) => {
                    this.message = ''
                    this.messages = response.data.messages;
                    this.scroll(this.selectedConversation.entityUrn);
                })
        },
        syncMessages: function (id = false) {

            if (!id){
                id = this.selectedConversation.id;
            }
            console.log(this.selectedConversation,id)

            this.$http.post(`/dashboard/conversations/${id}/messages-sync`)
                .then(() => {
                    // this.getConversation({}, this.selectedConversation)
                    // toastr.success('Successfully synced');
                }).catch(() => {
                toastr.error('Something went wrong');

            })
        },
        syncConversations: function () {
            this.$http.post(`/dashboard/linkedin/conversations/sync`)
                .then(() => {
                    location.reload();
                }).catch(() => {
                toastr.error('Something went wrong');
            })
        },

        getThreads: function () {
            this.$http.get(window.location.href)
                .then((response) => {
                    this.threads = response.data.threads;
                    this.defThreads = this.threads;

                }).catch(() => {
                toastr.error('Something went wrong');
            })
        },


    }
})
