<template>
    <div>
        <loader-vue :isLoading="isLoading"></loader-vue>
        <form @submit.prevent="submit">
            <input v-if="modalMode != 'add'" type="hidden" name="id" v-model="fields.id">
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
                <label class="col-lg-2 col-form-label fw-bold fs-6">Data Set<span class="text-danger">*</span></label>
                <div class="col-lg-10 fv-row">
                    <input type="text" name="data_set" class="form-control form-control-lg form-control-solid"
                        placeholder="Enter Comma Seperated Dataset" v-model="fields.data_set" required autofocus>
                </div>
                <div class="alert alert-danger mt-5" v-if="errors && errors.data_set">
                    {{ errors.data_set[0] }}
                </div>

            </div>
            <div class="row mb-6">
                <label class="col-lg-2 col-form-label fw-bold fs-6">File Template URL<span
                        class="text-danger">*</span></label>
                <div class="col-lg-10 fv-row">
                    <input type="text" name="file_template_url" class="form-control form-control-lg form-control-solid"
                        placeholder="Enter File Template URL" v-model="fields.file_template_url" required autofocus>
                </div>
                <div class="alert alert-danger mt-5" v-if="errors && errors.file_template_url">
                    {{ errors.file_template_url[0] }}
                </div>

            </div>

            <div class="row mb-6">
                <label class="col-lg-2 col-form-label fw-bold fs-6">Status</label>
                <div class="col-lg-10 fv-row">
                    <select class="form-control selectpicker" name="status" id="status" v-model="fields.status" required
                        autofocus>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="alert alert-danger mt-5" v-if="errors && errors.status">
                    {{ errors.status[0] }}
                </div>
            </div>
            <div class="form-group row mb-6">
                <button type="submit" class="btn btn-primary">
                    Submit Form
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
        //set default select input option
        this.fields.status = "1";
        if (this.modalMode != 'add') {
            this.fetchForm();
        }
    },
    methods: {
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

        fetchForm() {
            this.isLoading = true;
            axios.get('/api/form/get/' + this.modalMode).then(response => {
                console.log(response);
                this.fields = response.data;
                this.errors = {};
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
                toastr.error("Server error", "Error");
            }).finally(() => {
                this.isLoading = false; // Set isLoading back to false after the request is complete
            });
        }
    },
    emits: ['close-modal', 'form-submit'],
}
</script>

