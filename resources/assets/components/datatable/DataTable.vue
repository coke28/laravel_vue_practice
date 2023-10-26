<template>
  <div>
    <div class="d-flex flex-stack mb-5">
      <!-- listening for searchData and clearSearchData event -->
      <search-bar-vue v-model="search" 
      @update:modelValue="handleSearchUpdate()" 
      @clear-search-data="handleClearSearch()">
      </search-bar-vue>
    </div>
    <!-- Start Table -->
    <!-- Start page display -->

    <!-- End page display -->

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

export default {
  components: {
    'search-bar-vue': SearchBar,
  },
  data() {
    return {
      response_data: [],
      sort: '',
      search: '',
      order: 'asc',
      pagination: {},
      paginate_display: 10,
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
    handleSearchUpdate(){
      this.fetchData(1);

    },
    handleClearSearch(){
      this.search = "";
      this.fetchData(1);

    }
  },
  mounted() {
    this.fetchData();
  },
};
</script>
