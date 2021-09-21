Vue.component('chat-index', {
    template: `
        <div class="card">
        <div class="no-gutters row">
            <chat-list  :account="account" ></chat-list>
            <chat-messages :account="account"></chat-messages>
        </div>
        </div>
    `,
    data: function () {
        return {}
    },
    props: ['account'],
    mounted() {
    },

})
