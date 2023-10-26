<template>
    <div class="d-flex align-items-center position-relative my-1 mb-2 mb-md-0">
        <div class="input-group input-group-solid">
            <span class="svg-icon svg-icon-1 input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" :value="modelValue"  @input="checkSearchData($event)" 
            placeholder="Search..."
                class="form-control form-control-lg form-control-solid">
            <button @click="clearSearchData()" class="input-group-text clearInp">
                <i class="fas fa-times fs-4"></i>
            </button>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        modelValue: String
    },
    mounted() {

    },
    emits: ['update:modelValue', 'clear-search-data'], // prevents warnings in vue-tools
    methods: {
        checkSearchData($event){
            this.$emit('update:modelValue', $event.target.value)

        },

        clearSearchData() {
            this.$emit('clear-search-data')
        },

    }
}
</script>