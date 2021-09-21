Vue.component('chat-message', {
    template: `
        <template>

                <div class="chat-message-left pb-4" v-if="message.account_id">
                    <div><img :src="account.image? account.image: '/dist/img/lin_def_image.svg'"
                              class="rounded-circle mr-1" alt="You"
                              width="40" height="40">
                        <div class="text-muted small text-nowrap mt-2">{{ message.date_diff }}</div>
                    </div>
                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                        <div class="font-weight-bold mb-1">You</div>
                        {{message.text}}
                    </div>
                </div>

                <div class="chat-message-left pb-4" v-else>
                    <div><img :src="message.connection.image? message.connection.image: '/dist/img/lin_def_image.svg'"
                              class="rounded-circle mr-1"
                              alt="Kathie Burton" width="40" height="40">
                        <div class="text-muted small text-nowrap mt-2">{{ message.date_diff }}</div>
                    </div>
                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                        <div class="font-weight-bold mb-1">{{ message.connection.firstName }} {{ message.connection.lastName }}</div>
                        {{message.text}}
                        <p v-if="message.media"><img :src="message.media.url"></p>
                        <p v-if="message.attachments">
                            <template v-if="message.attachments.mediaType.includes('application') || message.attachments.mediaType.includes('text')">
                                <a  target="_blank" :href="message.attachments.reference"> {{ message.attachments.name }}</a>
                            </template>
                            <template v-else>
                                <img  :src="message.attachments.reference" width="100">
                            </template>
                        </p>
                    </div>
                </div>

            </template>

    `,
    data: function () {
        return {}
    },
    props: ['message','account'],
    mounted() {
    },

})
