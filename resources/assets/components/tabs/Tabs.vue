<template>
    <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
        <li class="nav-item" v-for="(tab, index) in tabs" :key="index" @click="selectTab(index)">
            <a class="nav-link" data-bs-toggle="tab">{{ tab.label }}</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <Transition name="fade">
            <component :is="selectedTab.component"></component>
        </Transition>
    </div>
</template>
  
<script>
import CustomForm from '../form/CustomForm.vue'
export default {
    components: {
        CustomForm
    },
    props: {
        tabs: {
            type: Array,
            default: [
                { label: 'Tab 1', component: 'Tab1Component' },
                { label: 'Tab 2', component: 'Tab2Component' },
                { label: 'Tab 3', component: 'Tab3Component' },
            ]
        }
    },
    data() {
        return {
            selectedTab: this.tabs[0],
        };
    },
    mounted() {
        // Set the default tab on component mount
        this.selectedTab = this.tabs[0];
    },
    methods: {
        selectTab(index) {
            this.selectedTab = this.tabs[index];
        },
    },
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
  