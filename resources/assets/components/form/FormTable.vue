<template>
  <div>
    <div class="d-flex flex-stack mb-5">
      <div class="d-flex align-items-center position-relative my-1 mb-2 mb-md-0">
        <div class="input-group input-group-solid">
          <span class="svg-icon svg-icon-1 input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" v-model="search" @input="this.searchData" placeholder="Search...(Minimum search length 3.)"
            class="form-control form-control-lg form-control-solid">
          <button class="input-group-text clearInp">
            <i class="fas fa-times fs-4"></i>
          </button>
        </div>
      </div>

      <!-- <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
        <button type="button" class="btn btn-primary" title="Add Form" data-bs-toggle="modal" data-bs-target="#addForm">
          <span class="svg-icon svg-icon-2"><i class="bi bi-plus fs-2"></i></span>
          Add Form
        </button>
      </div> -->

    </div>
    <table class="table table-rounded table-striped border gy-7 gs-7">
      <thead>
        <tr class="fw-semibold fs-6 text-black-800 border-bottom border-gray-200">
          <!-- <tr class="fw-semibold fs-6 text-black-800 border-bottom border-gray-200"> -->
          <th v-for="table_column in parameters.table_columns" :key="table_column.header_value"
            @click="table_column.orderable ? this.sortColumn(table_column.header_value) : null">{{
              table_column.header_name }}
            <i v-if="table_column.orderable" class="bi bi-sort-alpha-down" aria-label='Sort Icon'></i>
          </th>
        </tr>
      </thead>
      <tbody class="text-black-600 fw-bold">
        <tr v-for="data_row in response_data" :key="data_row.id">
          <td v-for="table_column in parameters.table_columns" :key="table_column.header_value">
            {{ data_row[table_column.header_value] }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>

export default {
  data() {
    return {
      response_data: [],
      search: '',
      sort: '',
      order: 'asc',
      pagination: {},
    };
  },
  props: {
    parameters: {
      type: Object, // or Array, if you want to accept both
      required: true
    }
  },
  methods: {
    async fetchData() {
      const response = await axios.get(this.parameters.tb_api, {
        params: {
          search: this.search,
          sort: this.sort,
          order: this.order,
          page: this.pagination.current_page || 1,
        },
      });
      this.response_data = response.data.data;
      this.pagination = response.data.pagination;
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
  },
  mounted() {
    this.fetchData();
  },
};
</script>
