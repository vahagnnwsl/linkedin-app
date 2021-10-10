Vue.component('chat-list', {
    template: `
        <div class="border-right col-lg-5 col-xl-3">
        <div class="d-md-block">
            <div class="align-items-center media">
                <div class="media-body">
                    <form @submit.prevent="search">
                        <div class="row p-2">
                            <div class="col-sm-12">
                                <input placeholder="Search..." type="text" name="key" class="my-3 form-control" v-model="searchKey" style="border-radius: 0">

                            </div>
                            <div class="col-sm-6">
                                <!-- checkbox -->
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="customRadio1" name="condition" value="answered" v-model="condition">
                                        <label for="customRadio1" class="custom-control-label">Answered</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="customRadio2" name="condition" value="not_answered"  v-model="condition">
                                        <label for="customRadio2" class="custom-control-label">Not Answered</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="customRadio3" name="condition" value="all"  v-model="condition">
                                        <label for="customRadio3" class="custom-control-label">All</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <!-- radio -->
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input custom-control-input-danger" type="radio" id="customRadio4" name="distance" value="connections"  v-model="distance">
                                        <label for="customRadio4" class="custom-control-label">Connections</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input custom-control-input-danger" type="radio" id="customRadio5" name="distance" value="messages"  v-model="distance">
                                        <label for="customRadio5" class="custom-control-label">Messages</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input custom-control-input-danger" type="radio" id="customRadio6" name="distance" value="all" v-model="distance">
                                        <label for="customRadio6" class="custom-control-label">All</label>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--                        <div class="row p-1 border border-light"  style="margin-right: 0!important;margin-left: 0!important;">-->
<!--                            <div class="btn-group w-100">-->
<!--                                <button type="button" class="btn inActiveTab condition" id="condition_answered"-->
<!--                                        @click="setCondition('answered')">Answered-->
<!--                                </button>-->
<!--                                <button type="button" class="btn inActiveTab  condition" id="condition_not_answered"-->
<!--                                        @click="setCondition('not_answered')">Not Answered-->
<!--                                </button>-->
<!--                                <button type="button" class="btn activeTab condition" id="condition_all"-->
<!--                                        @click="setCondition('all')">All-->
<!--                                </button>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                       <div class="row  p-1 border border-light" style="margin-right: 0!important;margin-left: 0!important;">-->
<!--                           <input placeholder="Search..." type="text" name="key" class="my-3 form-control"-->
<!--                                  v-model="searchKey" style="border-radius: 0">-->
<!--                           <div class="btn-group btn-block ">-->
<!--                               <button type="button" class="btn inActiveTab distance" id="distance_connections"-->
<!--                                       @click="setDistance('connections')">Connections-->
<!--                               </button>-->
<!--                               <button type="button" class="btn inActiveTab  distance" id="distance_messages"-->
<!--                                       @click="setDistance('messages')">Messages-->
<!--                               </button>-->
<!--                               <button type="button" class="btn activeTab distance" id="distance_all"-->
<!--                                       @click="setDistance('all')">All-->
<!--                               </button>-->
<!--                           </div>-->
<!--                       </div>-->
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
                    <div class="ml-3 media-body">
                        {{ conversation.connection.fullName }}
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
            condition: 'all',
        }
    },
    watch: {
        condition: function () {
            this.getConversations(true)
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
                console.log( 'conversation_new_message' + conversationId)
                document.getElementById('conversation_new_message' + conversationId).style.display = 'block';
            }, 1000);
        });

        $(document).on('changeConversationLastMessage', function (e, obj) {
            // _this.$refs['lastMessage' + obj.conversationId][0].innerHTML = obj.text;
            for (let i in _this.conversations) {
                if (_this.conversations[i].id === obj.conversationId) {
                    _this.conversations[i].lastActivityAt = obj.lastActivityAt
                    _this.conversations[i].lastActivityAt_diff = obj.date_diff
                }
            }
            // _this.sortConversations();
            setTimeout(function () {
                document.getElementById('lastMessage' + obj.conversationId).innerHTML = obj.text ? obj.text.slice(0, 20) + '...' : '';
            }, 1000);

        });


    },
    methods: {

        setCondition: function (condition) {

            this.condition = condition;

            const elements = document.getElementsByClassName('condition');
            for (let el of elements) {
                el.classList.remove('activeTab');
                el.classList.remove('inActiveTab');

                if (el.id === 'condition_' + condition) {
                    el.classList.add('activeTab');
                } else {
                    el.classList.add('inActiveTab');
                }
            }
        },
        setDistance: function (distance) {
            this.distance = distance;
            const elements = document.getElementsByClassName('distance');
            for (let el of elements) {
                el.classList.remove('activeTab');
                el.classList.remove('inActiveTab');

                if (el.id === 'distance_' + distance) {
                    el.classList.add('activeTab');
                } else {
                    el.classList.add('inActiveTab');
                }
            }

        },
        search: function () {
            this.getConversations(true);
        },
        loadMore: function () {
            this.start += 10;
            this.getConversations();
        },
        getConversations: function (searchable = false) {

            let queryParams = '&distance=' + this.distance + '&condition=' + this.condition;


            queryParams = this.searchKey ? queryParams + '&key=' + this.searchKey : queryParams + '';


            this.$http.get(`/dashboard/conversations/account/${this.account.id}?start=${this.start}${queryParams}`)
                .then((response) => {

                    if (searchable) {
                        this.conversations = [];
                    }

                    this.conversations.push(...response.data.conversations)

                    if (this.searchKey) {
                        this.loadMoreConversation = false;
                    } else {
                        if (response.data.conversations.length === 0 || response.data.conversations.length < 10) {
                            this.loadMoreConversation = false;
                        }else {
                            this.loadMoreConversation = true;
                        }
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
