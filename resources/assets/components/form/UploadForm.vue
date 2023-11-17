<template>
    <div>
        <div v-if="isLoading" class="loader-container">
            <div class="loader"></div>
        </div>
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="row mb-6">
                <label class="col-lg-2 col-form-label fw-bold fs-6">Upload File<span class="text-danger">*</span></label>
                <div class="col-lg-10 fv-row">
                    <input type="file" ref="file" name="file" class="form-control form-control-lg form-control-solid"
                        placeholder="Upload File" required autofocus>
                </div>
                <div class="alert alert-danger mt-5" v-if="errors && errors.file">
                    {{ errors.file[0] }}
                </div>

            </div>
            <div class="form-group row mb-6">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Upload File
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            fields: {},
            errors: {},
            isLoading: false,
        }
    },

    props: {
        modalMode: {
            type: [String, Number],
            default: "add"
        },

    },
    mounted() {
    },
    methods: {

        handleFileUpload(){
            this.fields.file = this.$refs.file.files[0];
        },

        submit() {
            this.isLoading = true;
            if (this.modalMode != 'add') {
                this.isLoading = true;
                axios.post('/api/form/edit/' + this.modalMode, this.fields).then(response => {
                    console.log(response);
                    this.fields = {};
                    this.errors = {};
                    this.$emit('form-submit');
                    this.$emit('close-modal');
                }).catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-bottom-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                    toastr.error("Server error", "Error");
                }).finally(() => {
                    this.isLoading = false; // Set isLoading back to false after the request is complete
                });
            } else {
                axios.post('/api/form/add', this.fields).then(response => {
                    toastr.options = {
                        closeButton: false,
                        debug: false,
                        newestOnTop: false,
                        progressBar: false,
                        positionClass: "toast-bottom-right",
                        preventDuplicates: false,
                        onclick: null,
                        showDuration: "300",
                        hideDuration: "1000",
                        timeOut: "5000",
                        extendedTimeOut: "1000",
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                    };
                    toastr.success(response.data.message, "Success");
                    this.fields = {};
                    this.fields.status = "1";
                    this.errors = {};
                    this.$emit('form-submit');
                }).catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-bottom-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                    toastr.error("Something went wrong", "Error");
                }).finally(() => {
                    this.isLoading = false; // Set isLoading back to false after the request is complete
                });
            }
        },
    },
    emits: [''],
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
