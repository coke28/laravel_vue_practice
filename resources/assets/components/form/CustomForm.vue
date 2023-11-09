<template>
    <div>
        <form @submit.prevent="submit">
            <!-- <div class="alert alert-success" v-show="success">Ticket created successfully.</div> -->

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

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary" :disabled="isLoading">
                        <span v-if="isLoading">Submitting Form...</span>
                        <span v-else="!isLoading">Submit Form</span>
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
            categories: null,
            fields: {},
            success: false,
            errors: {},
            isLoading: false,
        }
    },
    mounted() {
        // axios.get('/api/categories').then(response => {
        //     this.categories = response.data.data;
        // }).catch(error => {
        //     if (error.response.status == 422) {
        //         this.errors = error.response.data.errors;
        //     }
        //     console.log('Error');
        // });
    },
    methods: {
        submit() {

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
                this.success = true;
                this.errors = {};
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
    }
}
</script>