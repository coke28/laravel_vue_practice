<template>
    <div class="d-flex align-items-center position-relative my-1 mb-2 mb-md-0">
        <div class="input-group input-group-solid">
            <span class="svg-icon svg-icon-1 input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" v-model="search" @input="searchData()" placeholder="Search...(Minimum search length 3.)"
                class="form-control form-control-lg form-control-solid">
            <button @click="clearSearchData()" class="input-group-text clearInp">
                <i class="fas fa-times fs-4"></i>
            </button>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            search: '',
        }
    },
    mounted() {

    },
    emits: ['search-data', 'clear-search-data'], // prevents warnings in vue-tools
    methods: {
        searchData() {
            if (this.search.length > 0) {
                //emiting event to listener in parent component
                this.$emit('search-data',this.search)
            }
        },
        clearSearchData() {
            this.search = "";
            this.$emit('clear-search-data',this.search)
        },

    }
}
</script>