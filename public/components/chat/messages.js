Vue.component('chat-messages', {
    template: `
        <div class="col-lg-7 col-xl-9">
        <div class="py-2 px-4 border-bottom d-none d-lg-block">
            <div class="align-items-center py-1 media">
                <div class="position-relative" v-if="connection">
                    <img
                        :src="connection.image? connection.image: '/dist/img/lin_def_image.svg'"
                        class="rounded-circle mr-1" alt="Kathie Burton" width="40"
                        height="40">
                </div>
                <div class="pl-3 media-body" v-if="connection">
                    <strong></strong>
                    <a target="_blank" :href="'https://www.linkedin.com/in/'+connection.entityUrn"><span class="text-bold text-blue">{{ connection.fullName }}</span></a>
                    <br/> <small class="text-black-50 font-weight-bold">{{ connection.occupation }}</small>
                </div>
                <div v-if="selectedConversation">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu" role="menu" style="">
                            <a href="#" class="dropdown-item" @click="syncLastMessages(selectedConversation.id)">Sync last messages</a>

                        </div>
                    </div>

                </div>
            </div>
            <div class="position-relative py-1 text-right" v-if="selectedConversation.connection && selectedConversation.connection.conversations">
                <span v-for="(item,index) in selectedConversation.connection.conversations" :key="index"
                      class="badge mr-1"
                      style="color: rgba(0,0,0,.5)!important;border: 1px solid rgba(0,0,0,.5)!important;"
                      v-if="account.id !==item.account.id ">  {{ item.account.full_name }}</span>


            </div>
        </div>
        <div class="position-relative" style="height: 510px;max-height: 510px">
            <div class="chat-messages p-4" id="messages">
                <chat-message :key="index" :message="message" :account="account"
                              v-for="(message,index) in messages"></chat-message>

            </div>
        </div>
        <div class="flex-grow-0 py-3 px-4 border-top">
            <form @submit.prevent="sendMessages" v-if="entityUrn">
                <div class="input-group" >
                    <div class="input-group-prepend" v-if="loadMore" @click="getMessages">
                        <button type="button" class="btn btn-outline-primary" title="Load more ..."><i
                            class="fa fa-arrow-circle-down"></i></button>
                    </div>
                    <input placeholder="Type your message" type="text" class="form-control sendMessageGroup"
                           v-model="form.message" >

                    <div class="input-group-append sendMessageGroup">
                        <button class="btn btn-primary">Send</button>
                    </div>
                </div>
            </form>

        </div>


        </div>
    `,
    data: function () {
        return {
            start: 0,
            messages: [],
            connection: null,
            entityUrn: '',
            selectedConversation: null,
            loadMore: true,
            form: {
                message: ''
            }


        }
    },
    props: ['account'],
    mounted() {
        let _this = this

        $(document).on('selectedConversation', function (e, selectedConversation) {
            console.log(selectedConversation)
            _this.messages = [];
            _this.start = 0;
            _this.loadMore = true;
            _this.selectedConversation = selectedConversation;
            _this.entityUrn = selectedConversation.entityUrn;
            _this.connection = selectedConversation.connection;
            _this.getMessages();
        });
        channel.bind('newMessage', function (message) {

            // _this.mapConversationAndSetLastActivityAt(message.conversation_id,message.date);
            // _this.sortConversations();

            if (_this.selectedConversation.id && _this.selectedConversation.id === message.conversation_id) {
                _this.messages.push(message);
                _this.scroll();

            } else {
                $(document).trigger('newMessage', message.conversation_id);
            }

            $(document).trigger('changeConversationLastMessage', {
                conversationId: message.conversation_id,
                text: message.text,
                lastActivityAt: message.date,
                date_diff: message.date_diff,
            });
        })

    },
    methods: {
        getMessages: function () {

            this.$http.get(`/dashboard/conversations/${this.entityUrn}/messages?start=${this.start}&relative=true`)
                .then((response) => {
                    this.messages.unshift(...response.data.messages)
                    this.start += 10;
                    if (response.data.messages.length < 10) {
                        this.loadMore = false
                    }
                })
        },
        scroll: function () {
            setTimeout(function () {
                $("#messages").scrollTop($("#messages")[0].scrollHeight);

            }, 1000)
        },
        sendMessages: function () {

            if (!this.form.message.length) {
                return;
            }

            this.$http.post(`/dashboard/messages`, {
                text: this.form.message,
                conversation_id: this.selectedConversation.id
            }).then((response) => {
                if (response.data.limitError) {
                    toastr.error(response.data.limitError);
                } else {
                    this.messages.push(response.data.message);
                    this.form.message = ''
                }
                $(document).trigger('changeConversationLastMessage', {
                    conversationId: response.data.message.conversation_id,
                    text: response.data.message.text,
                    lastActivityAt: response.data.message.date,
                    date_diff: response.data.message.date_diff,

                });
                this.scroll();
            }).catch((e) => {
                console.log(e)
                toastr.error('Something went wrong');
            })
        },

        syncLastMessages: function (id) {
            this.$http.post(`/dashboard/conversations/${id}/sync-last-messages`).then(() => {
                toastr.success('Your request on process');
            }).catch(() => {
                toastr.error('Something went wrong');
            })
        }

    }


})
