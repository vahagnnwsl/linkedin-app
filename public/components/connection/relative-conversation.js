Vue.component('relative-conversation', {

    template: `
        <div class="modal" id="conversationModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="direct-chat-messages" id="messages" style="min-height: 690px;overflow-y: scroll">

                        <div class="direct-chat-msg" v-for="(message,key) in messages" :key="key"
                             :ref="'message_rel'+message.id">
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
                                        <!--                                            {{ selectedRelativeConversation.account.full_name }}-->
                                    </template>
                                    <span style="font-size: 12px"><i class="fa fa-clock"
                                                                     style="color: grey"></i> </span>
                                    <span class="direct-chat-timestamp"><small>{{ message.date_diff }}</small></span>

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
                </div>
            </div>
        </div>
        </div>
    `,

    data: function () {
        return {
            conversation_id: '',
            start: 0,
            messages: []
        }
    },
    mounted() {
        var _this = this;
        $(document).on('getConversationMessages', function (e, id) {
            $('#conversationModal').modal('show')
            _this.conversation_id = id;
            _this.start = 0;
            _this.messages = [];
            _this.getConversationMessages()
        });
    },
    methods: {
        getConversationMessages: function () {

            this.$http.get(`/dashboard/conversations/${this.conversation_id}/messages?type=all`)
                .then((response) => {
                    this.messages.unshift(...response.data.messages)

                    this.start += 10;
                })
        },
    }
})
