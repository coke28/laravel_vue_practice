<template>
  <div>
    <input type="text" v-model="search" @input="fetchData" placeholder="Search">

    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          Add more columns as needed
          <!-- <th @click="sort('name')">Name</th>
            <th @click="sort('email')">Email</th>
            Add more columns as needed -->
        </tr>
      </thead>
      <tbody>
        <!-- <tr v-for="item in items" :key="item.id">
            <td>{{ item.name }}</td>
            <td>{{ item.email }}</td>
            Add more columns as needed
          </tr> -->
          <tr>
            <td>BOB</td>
            <td>bob@gmail</td>
          </tr>
      </tbody>
    </table>

    <div v-if="pagination.total > pagination.per_page">
      <button @click="prevPage" :disabled="pagination.current_page === 1">Previous</button>
      <span>Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
      <button @click="nextPage" :disabled="pagination.current_page === pagination.last_page">Next</button>
    </div>
  </div>
</template>
  
<script>
export default {
  data() {
    return {
      items: [],
      search: '',
      sort: '',
      order: 'asc',
      pagination: {},
    };
  },
  methods: {
    async fetchData() {
      const response = await axios.get('/api/data', {
        params: {
          search: this.search,
          sort: this.sort,
          order: this.order,
          page: this.pagination.current_page,
        },
      });
      this.items = response.data.data;
      this.pagination = response.data.meta.pagination;
    },
    sort(column) {
      if (this.sort === column) {
        this.order = this.order === 'asc' ? 'desc' : 'asc';
      } else {
        this.sort = column;
        this.order = 'asc';
      }
      this.fetchData();
    },
    nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        this.fetchData();
      }
    },
    prevPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        this.fetchData();
      }
    },
  },
  mounted() {
    // this.fetchData();
  },
};
</script>
  