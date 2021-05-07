Vue.component('linkedin-chat', {
    template: `
        <div class="row ">

        <div class="col-md-4">
            <div class="users-container p-2 border scrollpane">
                <!--                <div class="chat-search-box">-->
                <!--                    <div class="input-group">-->
                <!--                        <input class="form-control" placeholder="Search" v-model="searchKey">-->
                <!--                        <div class="input-group-btn">-->
                <!--                            <button type="button" class="btn btn-info" @click="searchConversations">-->
                <!--                                <i class="fa fa-search"></i>-->
                <!--                            </button>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <ul class="users border conversations" style="max-height: 685px;overflow-y: scroll">

                    <li class="person " style="position: relative" v-for="(conversation,index) in conversations"
                        @click="selectConversation(conversation)" :class="selectedConversation.id === conversation.id?'active2':''">
                        <div class="user">
                            <img :src="conversation.connection.image" onerror="this.src='/dist/img/lin_def_image.svg'">
                        </div>
                        <p class="name-time">
                                        <span class="name">
                                            {{ conversation.connection.firstName }}
                                            {{ conversation.connection.lastName }}
                                        </span>
                            <br/>
                            <span style="font-size: 11px;margin-right: 5px">
                                           <i class="fa fa-clock" style="color: grey"></i>
                                       </span>
                            <span class="lastActivityAt"
                                  style="font-size: 11px;margin-right: 5px">{{ conversation.lastActivityAt_diff }}</span>

                        </p>


                        <span style="position: absolute;right: 5px;bottom: 5px;display: none" :ref="'conversation_new_message'+conversation.id"
                              class="badge badge-danger new-message" >new</span>
                    </li>
                </ul>

                <a href="javascript:void(0)" class="w-100 text-center loadMoreButton p-2" @click="getConversations">
                    Load more
                    <i class="fa fa-arrow-circle-down"></i>
                </a>

            </div>
        </div>

        <div class="col-md-8">
            <div class="chat-container">
                <div class="card direct-chat direct-chat-primary p-1" style="background-color: #f7f7f7">
                    <div class="card-header" v-if="selectedConversation.connection">
                         <h2>{{selectedConversation.connection.firstName}} {{selectedConversation.connection.lastName}}
                             <i class="fa fa-sync-alt float-right" style="cursor: pointer" @click="syncLastMessages(selectedConversation.id)" title="Sync lat messages"></i>
                         </h2>
                    </div>
                    <div class="card-body" style="position: relative">
                        <div class="direct-chat-messages" id="messages" style="min-height: 690px;overflow-y: scroll">

                            <div class="direct-chat-msg" v-for="(message,key) in messages" :key="key"
                                 :ref="'message_'+message.id">
                                <template v-if="message.connection">
                                    <img class="direct-chat-img" style="margin-top: 8px;"
                                         :src="message.connection.image"
                                         onerror="this.src='/dist/img/lin_def_image.svg'">
                                </template>
                                <template v-else>
                                    <img class="direct-chat-img" style="margin-top: 8px;"
                                         src="/dist/img/lin_def_image.svg">
                                </template>


                                <div class="direct-chat-text"
                                     style="background: #f7f7f7;position: inherit;border: 1px solid #f7f7f7">
                                    <p class="text-left text-gray-dark text-bold">
                                        <template v-if="message.connection">
                                            {{ message.connection.firstName }}   {{ message.connection.lastName }}
                                        </template>
                                        <template v-else>
                                            {{ account.full_name }}
                                        </template>
                                        <span style="font-size: 12px"><i class="fa fa-clock"
                                                                         style="color: grey"></i> </span>
                                        <span
                                            class="direct-chat-timestamp"><small>{{ message.date_diff }}</small></span>

                                        <!--                                        <span class="float-right text-danger" title="Resend" style="cursor: pointer"-->
                                        <!--                                              v-if="!message.status" @click="resend(message.id)"-->
                                        <!--                                              :ref="'message_resend'+message.id">-->
                                        <!--                                            <i class="fa fa-reply"></i>-->
                                        <!--                                        </span> -->

                                        <span class="float-right text-danger" title="Destroy" style="cursor: pointer"
                                              @click="destroy(message.id)"
                                              :ref="'message_resend'+message.id">
                                            <i class="fas fa-trash"></i>
                                        </span>

                                        <span class="float-right text-danger  mr-2" title="Resend"
                                              style="cursor: pointer"
                                              v-if="!message.status"
                                              @click="resend(message.id)"
                                              :ref="'message_resend'+message.id">
                                            <i class="fa fa-reply"></i>
                                        </span>


                                    </p>
                                    <p v-if="message.text"> {{ message.text }}</p>
                                    <p v-if="message.media"><img :src="message.media.url"></p>
                                    <p v-if="message.attachments">
                                        <template v-if="message.attachments.mediaType.includes('application')">
                                            <a :href="message.attachments.reference"> {{ message.attachments.name }}</a>
                                        </template>
                                        <template>
                                            <img v-else :src="message.attachments.reference">
                                        </template>
                                    </p>
                                </div>


                            </div>

                        </div>
                        <button v-if="messages.length" @click="getConversationMessages(selectedConversation.id)"
                                class="btn btn-default"
                                style="float: right" title="load more messages"><i class="fa fa-arrow-circle-down"></i>
                        </button>
                    </div>
                    <div class="card-footer" v-if="selectedConversation.id">
                        <form @submit.prevent="submit">
                            <div class="input-group">
                                <input type="text" name="message" placeholder="Type Message ..." class="form-control"
                                       v-model="form.message">
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
    `,

    data: function () {
        return {
            selectedConversation: {
                id: '',
                connection: null
            },
            conversations: [],
            messages: [],
            start: 0,
            messageStart: 0,
            searchKey: '',
            form: {
                message: ''
            }


        }
    },
    props: ['account'],
    watch: {

        messages: function (val) {
            this.scroll();
        }
    },
    mounted() {

        let _this = this;

        channel.bind('newMessage', function (message) {

            _this.mapConversationAndSetLastActivityAt(message.conversation_id,message.date);
            _this.sortConversations();

             if (_this.selectedConversation.id && _this.selectedConversation.id  === message.conversation_id){
                 _this.messages.push(message);
                 _this.scroll();

             }else {
                 _this.$refs['conversation_new_message' + message.conversation_id][0].style.display = 'block';
             }


        })

        this.getConversations()
    },
    methods: {
        searchConversations: function () {
            this.start = 0;

            this.getConversations()
        },
        getConversations: function () {

            let queryParams = this.searchKey ? '&key=' + this.searchKey : '';

            this.$http.get(`/dashboard/accounts/${this.account.id}/conversations?start=${this.start}${queryParams ? queryParams : ''}`)
                .then((response) => {

                    this.conversations.push(...response.data.conversations)

                    this.start += 10;
                    console.log(this.conversations)
                    this.sortConversations()
                })
        },
        selectConversation: function (conversation) {
            this.messageStart = 0;
            this.messages = [];
            this.selectedConversation.id = conversation.id;
            this.selectedConversation.connection = conversation.connection;
            this.getConversationMessages(conversation.id)
        },
        getConversationMessages: function (id) {
            this.$refs['conversation_new_message' +id][0].style.display = 'none';

            this.$http.get(`/dashboard/conversations/${id}/messages?start=${this.messageStart}`)
                .then((response) => {

                    this.messages.unshift(...response.data.messages)
                    this.messageStart += 10;
                })
        },
        scroll: function () {
            setTimeout(function () {
                $("#messages").scrollTop($("#messages")[0].scrollHeight);

            }, 1000)
        },
        destroy: function (id) {
            this.$http.put(`/dashboard/messages/${id}/destroy`)
                .then(() => {
                    this.$refs['message_' + id][0].remove();

                }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
        resend: function (id) {
            this.$http.post(`/dashboard/messages/${id}/resend`)
                .then(() => {
                    this.$refs['message_resend' + id][0].remove();

                }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
        submit: function () {

            if (!this.form.message.length) {
                return;
            }

            this.$http.post(`/dashboard/messages`, {
                text: this.form.message,
                conversation_id: this.selectedConversation.id
            }).then((response) => {
                this.messages.push(response.data.message);
                this.mapConversationAndSetLastActivityAt(response.data.message.conversation_id,response.data.message.date);
                this.sortConversations();
                this.form.message = ''
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        },
        mapConversationAndSetLastActivityAt: function (conversationId, date) {
            this.conversations.map(function (conversation) {
                if (conversation.id === conversationId) {
                    conversation.lastActivityAt = date
                }
            })
        },
        sortConversations: function (){
            this.conversations.sort(function(a,b){
                return new Date(b.lastActivityAt) - new Date(a.lastActivityAt);
            });
        },
        syncLastMessages:function(id){
            this.$http.post(`/dashboard/conversations/${id}/sync-last-messages`).then(() => {
                toastr.success('Your request on process');
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        }
    }
})
