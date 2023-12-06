<template>
    <div v-if="isLoading" class="loader-container">
        <div class="loader"></div>
    </div>
</template>

<script>
export default {
    props: {
        isLoading: {
            type: Boolean,
            default: false
        }
    },

}


</script>

<style>
.table-container {
    position: relative;
    /* Make this the reference for the absolutely positioned loader */
}

.loader-container {
    position: absolute;
    /* Position loader above the table */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.7);
    /* Optional: white background with opacity for loader to look more apparent */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
    /* Ensure loader is above the table content */
}

.loader {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>