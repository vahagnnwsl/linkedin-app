Vue.component('chat-list', {
    template: `
        <div class="border-right col-lg-5 col-xl-3">
        <div class="px-4 d-none d-md-block">
            <div class="align-items-center media">
                <div class="media-body"><input placeholder="Search..." type="text" class="my-3 form-control">
                </div>
            </div>
        </div>
        <div class=" chat-list">
            <a :href="'/dashboard/linkedin/chat#entityUrn:'+conversation.entityUrn"
               @click="getConversation(conversation.entityUrn)"
               :class="selectedConversation && selectedConversation.entityUrn === conversation.entityUrn ? 'active': ''"
               class="border-0 list-group-item-action list-group-item" v-for="(conversation,index) in conversations">
            <span class="float-right badge badge-success" :ref="'conversation_new_message'+conversation.id"
                  style="display: none">NEW</span>
                <div class="media">
                    <img :src="conversation.connection.image? conversation.connection.image: '/dist/img/lin_def_image.svg'"
                         onerror="this.src='/dist/img/lin_def_image.svg'"
                         class="rounded-circle mr-1"
                         alt="Michelle Bilodeau" width="40" height="40">
                    <div class="ml-3 media-body">{{ conversation.connection.firstName }}
                        {{ conversation.connection.lastName }}
                        <div class="small">
                            {{ conversation.lastActivityAt_diff }}
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <hr class="d-block d-lg-none mt-1 mb-0">
        <a class="border-0 list-group-item-action list-group-item" style="cursor: pointer" @click="loadMore" v-if="loadMoreConversation">
            <span class="font-weight-bold">Load more ...</span>
            <i class="fa fa-arrow-circle-down float-right"></i>
        </a>
        </div>
    `,

    data: function () {
        return {
            conversations: [],
            messageStart: 0,
            selectedConversation: null,
            loadMoreConversation: true,
            start: 0
        }
    },
    props: ['account'],
    mounted() {
        if(window.location.hash) {
            let hash = window.location.hash.substring(1);
            hash = hash.split('_');
            if (hash.length){
                for (let i  in hash){
                    let split = hash[i].split(':');
                     if (split.length === 2 && split[0]==='entityUrn'){
                       this.getConversation(split[1])
                     }
                }
            }
        }
        this.getConversations()
    },
    methods: {
        loadMore: function () {
           this.start +=10;
           this.getConversations();
        },
        getConversations: function () {

            let queryParams = this.searchKey ? '&key=' + this.searchKey : '';

            this.$http.get(`/dashboard/conversations/account/${this.account.id}?start=${this.start}${queryParams ? queryParams : ''}`)
                .then((response) => {

                    this.conversations.push(...response.data.conversations)

                    this.sortConversations()
                    if (response.data.conversations.length === 0) {
                        this.loadMoreConversation = false;
                    }
                })
        },
        getConversation: function (entityUrn) {
            this.$http.get(`/dashboard/conversations/entityUrn/${entityUrn}`)
                .then((response) => {
                    this.selectedConversation = response.data.conversation
                    $(document).trigger('selectedConversation', this.selectedConversation);
                })

        },
        sortConversations: function () {
            this.conversations.sort(function (a, b) {
                return new Date(b.lastActivityAt) - new Date(a.lastActivityAt);
            });
        },
    }
})
