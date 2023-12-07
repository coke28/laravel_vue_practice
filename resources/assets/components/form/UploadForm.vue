<template>
    <div>
        <loader-vue :isLoading="isLoading"></loader-vue>
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="row mb-6">
                <label class="col-lg-2 col-form-label fw-bold fs-6">Name<span class="text-danger">*</span></label>
                <div class="col-lg-10 fv-row">
                    <input type="text" name="form_name" class="form-control form-control-lg form-control-solid"
                        v-model="fields.form_name" placeholder="Enter Form Name" required autofocus>
                </div>
                <div class="alert alert-danger mt-5" v-if="errors && errors.form_name">
                    {{ errors.form_name[0] }}
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-2 col-form-label fw-bold fs-6">Upload File<span class="text-danger">*</span></label>
                <div class="col-lg-10 fv-row">
                    <input type="file" class="form-control form-control-lg form-control-solid" id="customFile" ref="file"
                        @change="handleFileObject()" required autofocus>
                </div>
                <div class="alert alert-danger mt-5" v-if="errors && errors.file">
                    {{ concatenateFileUploadErrors }}
                </div>
            </div>
            <div class="form-group row mb-6">
                <button type="submit" class="btn btn-primary">
                    Upload File
                </button>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            fields: {},
            file: {},
            errors: {},
            isLoading: false,
        }
    },
    computed: {
        concatenateFileUploadErrors() {
            var error_message = "";
            if (this.errors.file && this.errors.file.length > 0) {
                // Use join to concatenate the error messages with a separator
                error_message = this.errors.file.join('');
            }
            return error_message;
        }
    },
    mounted() {
    },
    methods: {

        handleFileObject() {
            this.file = this.$refs.file.files[0]
        },

        submit() {
            this.isLoading = true;
            this.errors = {};
            let formData = new FormData()
            formData.append('file', this.file)
            // Get the key and value paris of the fields array and append it to the formData variable
            Object.entries(this.fields).forEach(([key, value]) => {
                formData.append(key, value);
            });

            axios.post('/api/form/upload', formData,
                {
                    headers: {
                        'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
                    }

                }).then(response => {

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
                    this.file = {};
                    if (this.$refs.file && this.$refs.file.files) {
                        this.$refs.file.value = ''; // Clear the file input
                    }
                    this.errors = {};
                    this.$emit('form-submit');
                }).catch(error => {
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

                    if (error.response.status == 419) {
                        toastr.error(error.response.data.errors.file, "Error");
                    } else {
                        toastr.error("Something went wrong", "Error");
                    }

                    if (error.response.status == 418) {
                        let errorArray = [];
                        for (let error of error.response.data.errors.file) {
                            errorArray.push("On row #" + error.row + ", " + error.errors[0]);
                        }
                        this.errors.file = errorArray;
                    }

                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                }).finally(() => {
                    this.isLoading = false; // Set isLoading back to false after the request is complete
                });

        },
    },
    emits: ['form-submit'],
}
</script>
