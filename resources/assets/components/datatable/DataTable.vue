<template>
  <div>
    <div class="d-flex flex-stack mb-5">
      <!-- listening for searchData and clearSearchData event -->
      <search-bar-vue @search-data="handleSearchData" @clear-search-data="handleClearSearchData">
      </search-bar-vue>

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
          <th v-for="table_column in parameters.table_columns" :key="table_column.header_value"
            @click="table_column.orderable ? sortColumn(table_column.header_value) : null">{{
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
import SearchBar from './SearchBar'

export default {
  components: {
    'search-bar-vue': SearchBar,
  },
  data() {
    return {
      response_data: [],
      sort: '',
      order: 'asc',
      pagination: {},
    };
  },
  props: {
    parameters: {
      type: Object,
      required: true
    }
  },
  methods: {
    async fetchData(search = "") {
      const response = await axios.get(this.parameters.tb_api, {
        params: {
          search: search,
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
    handleSearchData(search) {
      this.fetchData(search);
    },
    handleClearSearchData(search) {
      this.fetchData(search);
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>
