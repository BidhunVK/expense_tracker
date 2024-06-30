<template>
  <div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Expense List</h2>

    <!-- Date Range Picker -->
    <div class="flex space-x-4 mb-4">
      <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700"
          >Start Date:</label
        >
        <input
          type="date"
          id="start_date"
          v-model="startDate"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>

      <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700"
          >End Date:</label
        >
        <input
          type="date"
          id="end_date"
          v-model="endDate"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>

      <button
        @click="fetchExpenses"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring-indigo active:bg-indigo-700 transition ease-in-out duration-150"
      >
        Fetch Expenses
      </button>
    </div>

    <div v-if="expenses.length > 0">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="expense in expenses"
          :key="expense.id"
          class="bg-white shadow-md rounded-lg overflow-hidden"
        >
          <div class="p-4">
            <div class="flex justify-between mb-2">
              <div>
                <strong class="text-xl">{{ expense.category.category }}</strong>
                <p class="text-gray-500">{{ expense.description }}</p>
              </div>
              <div class="text-right">
                <p class="text-gray-500">{{ expense.date_of_expense }}</p>
                <p class="text-xl text-indigo-600 font-semibold">
                  ${{ expense.amount }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-4">
        <nav class="flex justify-end">
          <ul class="flex">
            <li>
              <button
                @click="prevPage"
                :disabled="currentPage === 1"
                class="px-3 py-1 mx-1 bg-gray-200 rounded-md"
                :class="{ 'cursor-not-allowed': currentPage === 1 }"
              >
                Previous
              </button>
            </li>
            <li v-for="page in totalPages" :key="page" class="mx-1">
              <button
                @click="gotoPage(page)"
                :class="{ 'font-semibold': currentPage === page }"
                class="px-3 py-1 bg-white rounded-md"
              >
                {{ page }}
              </button>
            </li>
            <li>
              <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 mx-1 bg-gray-200 rounded-md"
                :class="{ 'cursor-not-allowed': currentPage === totalPages }"
              >
                Next
              </button>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- No Expenses Message -->
    <div v-else>
      <p class="text-gray-500">No expenses found for the selected period.</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      startDate: "",
      endDate: "",
      expenses: [],
      currentPage: 1,
      perPage: 6,
    };
  },
  methods: {
    fetchExpenses() {
      // Assuming you're using Axios for HTTP requests
      axios
        .get("/expense", {
          params: {
            start_date: this.startDate,
            end_date: this.endDate,
          },
        })
        .then((response) => {
          this.expenses = response.data;
          console.log(this.expenses);
        })
        .catch((error) => {
          console.error("Error fetching expenses:", error);
        });
    },

    fetchLatestMonthExpenses() {
      const currentDate = new Date();
      const year = currentDate.getFullYear();
      const month = currentDate.getMonth() + 1;

      const startDayOfMonth = `01`;
      this.startDate = `${year}-${
        month < 10 ? "0" + month : month
      }-${startDayOfMonth}`;

      const lastDayOfMonth = new Date(year, month, 0).getDate();
      this.endDate = `${year}-${
        month < 10 ? "0" + month : month
      }-${lastDayOfMonth}`;

      this.fetchExpenses();
    },

    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    gotoPage(page) {
      this.currentPage = page;
    },
  },
  computed: {
    totalPages() {
      return Math.ceil(this.expenses.length / this.perPage);
    },
    paginatedExpenses() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.expenses.slice(start, end);
    },
  },
  mounted() {
    this.fetchLatestMonthExpenses();
  },
};
</script>

<style scoped>
/* Add scoped styles here if needed */
</style>