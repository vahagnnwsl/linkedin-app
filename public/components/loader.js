

Vue.component('loader', {
    template: `
        <div v-if="show" class="loading" style="z-index: 2000">Loading&#8230;</div>
    `,
    data: function () {
        return {
              show: false
        }
    },
    mounted(){
        let _this = this;
        $(document).on('loader.update', function (e, response) {
            _this.show = response;
        });
    }
})
