Vue.component('chat-list', {
    template: `
        <div class="border-right col-lg-5 col-xl-3">
        <div class="px-4 d-none d-md-block">
            <div class="align-items-center media">
                <div class="media-body">
                    <form @submit.prevent="search">
                        <input placeholder="Search..." type="text" name="key" class="my-3 form-control"
                               v-model="searchKey">
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" name="distance" class="form-check-input" value="connections"
                                       v-model="distance">Connections
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" name="distance" class="form-check-input" value="messages"
                                       v-model="distance">Messages
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" name="distance" class="form-check-input" value="all"
                                       v-model="distance">All
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <hr/>
        <div class=" chat-list">
            <a :href="'/dashboard/linkedin/chat#entityUrn:'+conversation.entityUrn"
               @click="getConversation(conversation.entityUrn)"
               :class="selectedConversation && selectedConversation.entityUrn === conversation.entityUrn ? 'active': ''"
               class="border-0 list-group-item-action list-group-item" :key="index"
               v-for="(conversation,index) in sortConversations">
            <span class="float-right badge badge-success" :ref="'conversation_new_message'+conversation.id"
                  :id="'conversation_new_message'+conversation.id"
                  style="display: none">NEW</span>
                <div class="media">
                    <img
                        :src="conversation.connection.image? conversation.connection.image: '/dist/img/lin_def_image.svg'"
                        onerror="this.src='/dist/img/lin_def_image.svg'"
                        class="rounded-circle mr-1"
                        alt="Michelle Bilodeau" width="40" height="40">
                    <div class="ml-3 media-body">{{ conversation.connection.firstName }}
                        {{ conversation.connection.lastName }}
                        <div class="small text-black-50" :ref="'lastMessage'+conversation.id"
                             :id="'lastMessage'+conversation.id">
                            {{ conversation.lastMessage ? conversation.lastMessage.slice(0, 20) + '...' : '' }}
                        </div>
                        <div class="small">
                            {{ conversation.lastActivityAt_diff }}
                        </div>
                    </div>

                </div>
            </a>
        </div>

        <hr class="d-block d-lg-none mt-1 mb-0">
        <a class="border-0 list-group-item-action list-group-item" style="cursor: pointer" @click="loadMore"
           v-if="loadMoreConversation">
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
            start: 0,
            searchKey: '',
            distance: 'all',
        }
    },
    computed: {
        sortConversations: function () {
            return this.conversations.sort(function (a, b) {
                return new Date(b.lastActivityAt) - new Date(a.lastActivityAt);
            });
        },
    },
    props: ['account'],
    mounted() {
        if (window.location.hash) {
            let hash = window.location.hash.substring(1);
            hash = hash.split('_');
            if (hash.length) {
                for (let i in hash) {
                    let split = hash[i].split(':');
                    if (split.length === 2 && split[0] === 'entityUrn') {
                        this.getConversation(split[1])
                    }
                }
            }
        }
        this.getConversations()
        const _this = this;
        $(document).on('newMessage', function (e, conversationId) {
          
            setTimeout(function () {
                document.getElementById('conversation_new_message' + conversationId).style.display = 'block';
            }, 1000);
        });

        $(document).on('changeConversationLastMessage', function (e, obj) {
            // _this.$refs['lastMessage' + obj.conversationId][0].innerHTML = obj.text;
            for (let i in _this.conversations) {
                if (_this.conversations[i].id === obj.conversationId) {
                    _this.conversations[i].lastActivityAt = obj.lastActivityAt
                }
            }
            // _this.sortConversations();
            setTimeout(function () {
                document.getElementById('lastMessage' + obj.conversationId).innerHTML = obj.text ? obj.text.slice(0, 20) + '...' : '';
            }, 1000);

        });


    },
    methods: {
        search: function () {
            this.getConversations(true);
        },
        loadMore: function () {
            this.start += 10;
            this.getConversations();
        },
        getConversations: function (searchable = false) {

            let queryParams = this.searchKey ? '&distance=' + this.distance + '&key=' + this.searchKey : '';

            this.$http.get(`/dashboard/conversations/account/${this.account.id}?start=${this.start}${queryParams ? queryParams : ''}`)
                .then((response) => {

                    if (searchable) {
                        this.conversations = [];
                    }

                    this.conversations.push(...response.data.conversations)

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
                    this.$refs['conversation_new_message' + this.selectedConversation.id][0].style.display = 'none';

                })

        },
    }
})
