Vue.component('user-linkedin', {
    template: `
        <div class="row gutters">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

            <div class="card m-0">

                <!-- Row start -->
                <div class="row no-gutters">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3">
                        <div class="users-container">
                            <div class="chat-search-box">
                                <div class="input-group">
                                    <input class="form-control" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-info">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <ul class="users" style="max-height: 650px;overflow-y: scroll">

                                <li class="person"  :class="conversation_id === thread.id?'active_1':''" data-chat="person1" v-for="(thread,index) in threads"
                                    @click="getConversation(thread)" :key="index">
                                    <div class="user">
                                        <img :src="getParticipant(thread.data.member).picture??'/dist/img/avatar5.png'">
                                    </div>
                                    <p class="name-time">
                                        <span class="name">{{ getParticipant(thread.data.member).firstName }}
                                            {{ getParticipant(thread.data.member).lastName }}</span>
                                        <span class="time">{{ thread.lastActivityAt ?? '' }}</span>
                                    </p>
                                    <span class="badge badge-danger" style="float: right;display: none"
                                          :ref="thread.entityUrn">new</span>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-9 col-9">
                        <div class="selected-user">
                            <span><span class="name">{{ user_name }}</span></span>
                        </div>
                        <div class="chat-container">

                            <div class="card direct-chat direct-chat-primary">

                                <div class="card-body">
                                    <div class="direct-chat-messages" id="messages"
                                         style="min-height: 600px;overflow-y: scroll">

                                        <div class="direct-chat-msg" v-for="(message,i) in messages">
                                            <template v-if="message.user_entityUrn === user.linkedin_entityUrn">
                                                <div class="direct-chat-infos clearfix">
                                                    <!--                                                    <span class="direct-chat-name float-left">You</span>-->
                                                    <span
                                                        class="direct-chat-timestamp float-right">{{ message.date }}</span>
                                                </div>
                                                <img class="direct-chat-img"
                                                     :src="user.avatar ?'/storage/'+user.avatar:'/dist/img/avatar5.png'"
                                                     alt="You" title="You">
                                            </template>

                                            <template v-else>
                                                <div class="direct-chat-infos clearfix">
                                                    <!--                                                    <span class="direct-chat-name float-left">{{ user_name }}</span>-->
                                                    <span
                                                        class="direct-chat-timestamp float-right">  {{ message.date }}</span>
                                                </div>
                                                <img class="direct-chat-img"
                                                     :src="user_avatar?user_avatar:'/dist/img/avatar5.png'"
                                                     :alt="user_name" :title="user_name">
                                            </template>

                                            <!-- /.direct-chat-infos -->

                                            <!-- /.direct-chat-img -->
                                            <div class="direct-chat-text">
                                                {{ message.text }}
                                            </div>
                                            <!-- /.direct-chat-text -->
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
            user_avatar: '',
            user_name: '',
            entityUrn: '',
            conversation_id: '',
            message: ''
        }
    },
    props: ['threads', 'user'],
    mounted() {


        if (this.threads.length) {
            this.getConversation(this.threads[0]);
        }

        let _this = this;

        channel.bind('new-message', function (data) {
            if (_this.entityUrn === data.conversation_entityUrn) {
                _this.messages.push(data)
                _this.scroll();
            } else {
                _this.$refs[data.conversation_entityUrn][0].style.display = 'block'
                console.log()
            }
        });

    },
    methods: {
        submit: function () {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.$http.post(`/dashboard/linkedin-messages`, {
                        text: this.message,
                        conversation_id: this.conversation_id
                    })
                        .then(() => {
                            this.message = '';
                            this.$validator.reset();

                        })
                }
            })
        },
        scroll: function () {
            setTimeout(function () {
                var objDiv = document.getElementById("messages");
                objDiv.scrollTop = objDiv.scrollHeight;
            }, 1000)
        },
        getParticipant(members) {
            let _this = this
            return members.filter(function (member) {
                return member.entityUrn !== _this.user.linkedin_entityUrn
            })[0]
        },
        getConversation: function (thread) {

            let participant = this.getParticipant(thread.data.member);

            this.user_avatar = participant.picture;
            this.user_name = participant.firstName + ' ' + participant.lastName;
            this.entityUrn = thread.entityUrn;
            this.conversation_id = thread.id;
            this.$http.get(`/dashboard/users/${this.user.id}/conversations/${thread.entityUrn}`)
                .then((response) => {
                    this.message = ''
                    this.$refs[thread.entityUrn][0].style.display = 'none'
                    this.messages = response.data.messages;
                    this.scroll();
                })
        }

    }
})
