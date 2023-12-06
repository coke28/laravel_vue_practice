<template>
  <div>
    <Teleport to="#modal">
      <Transition name="modal">
        <modal-vue v-if="showModal" :model="parameters.model" :customFormComponent="parameters.customFormComponent"
          :modalMode="modalMode" @close-modal="closeModal()" @form-submit="fetchData(1)">
        </modal-vue>
      </Transition>
    </Teleport>
    <div class="d-flex flex-stack mb-5">

      <!-- listening for searchData and clearSearchData event -->
      <search-bar-vue v-model="search" @update:modelValue="handleSearchUpdate()" @clear-search-data="handleClearSearch()">
      </search-bar-vue>

      <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
        <!--begin::Add special announcement-->
        <button type="button" class="btn btn-primary" @click="openModal(add)">
          <span class="svg-icon svg-icon-2"><i class="bi bi-plus fs-2"></i></span>
          {{ 'Add' + " " + parameters.model }}
        </button>
        <!--end::Add special announcement-->
      </div>
    </div>
    <!-- Start Table -->
    <div class="table-container">
      <loader-vue :isLoading="isLoading"></loader-vue>
      <table class="table table-rounded table-striped border gy-7 gs-7">
        <thead>
          <tr class="fw-semibold fs-6 text-black-800 border-bottom border-gray-200">
            <th v-for="table_column in parameters.table_columns" :key="table_column.header_value"
              @click="table_column.orderable ? sortColumn(table_column.header_value) : null">{{
                table_column.header_name }}
              <i v-if="table_column.orderable" class="bi bi-sort-alpha-down" aria-label='Sort Icon'></i>
            </th>
            <th v-if="parameters.tools">
              Tools
            </th>
          </tr>
        </thead>
        <tbody class="text-black-600 fw-bold">
          <tr v-for="data_row in response_data" :key="data_row.id">
            <td v-for="table_column in parameters.table_columns" :key="table_column.header_value">
              {{ data_row[table_column.header_value] }}
            </td>

            <td v-if="parameters.tools">
              <!-- Actions Dropdown -->
              <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  Actions
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <li v-for="tool in parameters.tools" :key="tool.tool_name">
                    <a v-if="tool.tool_name === 'delete'" href="#" @click.prevent="deleteItem(data_row.id, tool.tool_api)"
                      class="dropdown-item">
                      Delete
                    </a>
                    <a v-if="tool.tool_name === 'edit'" href="#" @click.prevent="openModal(data_row.id)"
                      class="dropdown-item">
                      Edit
                    </a>
                    <a v-if="tool.tool_name === 'redirect'" :href="tool.redirect_url" 
                      class="dropdown-item">
                      Redirect Sample
                    </a>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- End Table -->
    <!-- Pagination Display -->
    <div class="d-flex justify-content-end mb-3">
      <div class="form-group d-flex align-items-center">
        <label for="paginateDisplay" class="me-2">Per page:</label>
        <select class="form-select form-select-sm" id="paginateDisplay" v-model="paginate_display" @change="fetchData()">
          <option v-for="paginate_display in parameters.page_display" :value="paginate_display">{{ paginate_display }}
          </option>
        </select>
      </div>
    </div>
    <!-- Pagination controls -->
    <nav aria-label="Pagination">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
          <a class="page-link" @click="fetchData(pagination.current_page - 1)" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li class="page-item" v-for="page in pagination.last_page" :key="page"
          :class="{ active: page === pagination.current_page }">
          <a class="page-link" @click="fetchData(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
          <a class="page-link" @click="fetchData(pagination.current_page + 1)" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import SearchBar from './SearchBar'
import Modal from '../modal/Modal'

export default {
  components: {
    'search-bar-vue': SearchBar,
    'modal-vue': Modal,
  },
  data() {
    return {
      response_data: [],
      sort: '',
      isLoading: false,
      search: '',
      order: 'asc',
      pagination: {},
      paginate_display: 10,
      showModal: false,
      modalMode: '',
    };
  },
  props: {
    parameters: {
      type: Object,
      required: true
    }
  },
  methods: {
    async fetchData(page = 1) {
      this.isLoading = true;
      try {
        const response = await axios.get(this.parameters.tb_api, {
          params: {
            search: this.search,
            sort: this.sort,
            order: this.order,
            page: page, // Pass the current page parameter
            paginate_display: this.paginate_display,

          },
        });
        this.response_data = response.data.data;
        this.pagination = response.data.pagination;
      } catch (error) {
        console.error("An error occurred:", error);
        // Optionally, show a user-friendly error message to inform the user about the error
      } finally {
        this.isLoading = false; // Always stop the loading state after the API call completes
      }

    },
    sortColumn(column) {
      if (this.sort === column) {
        this.order = this.order === 'asc' ? 'desc' : 'asc';
      } else {
        this.sort = column;
        this.order = 'asc';
      }
      this.fetchData();
    },
    handleSearchUpdate() {
      this.fetchData(1);

    },
    handleClearSearch() {
      this.search = "";
      this.fetchData(1);

    },
    openModal(modalMode) {

      this.modalMode = modalMode;
      console.log(modalMode);
      this.showModal = true;

    },
    closeModal() {
      this.showModal = false;

    },
    deleteItem(id, deleteApiEndpoint) {
      console.log([id, deleteApiEndpoint]);
      const vue = this; // Capture the Vue instance 'this' context
      Swal.fire({
        html: `Are you sure you want to delete ID: ` + id + `?`,
        icon: "info",
        buttonsStyling: false,
        showCancelButton: true,
        confirmButtonText: "Delete",
        cancelButtonText: 'Cancel',
        customClass: {
          confirmButton: "btn btn-primary",
          cancelButton: 'btn btn-danger'
        }
      }).then(function (result) {

        if (result.isConfirmed) {
          axios.post(deleteApiEndpoint + id)
            .then(response => {
              // Handle successful delete here, perhaps refresh the table or show a message
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
              toastr.success(response.data.message, "Success");
              vue.fetchData();
            })
            .catch(error => {
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
              toastr.error(error.message, "Error");
            });

        }
      });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>
