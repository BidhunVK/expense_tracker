<template>
  <div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Expense List</h2>
    <BarChart :category-wise-expenses="categoryWiseExpenses" :key="chartKey" />
    <!-- Date Range Picker -->
    <div class="grid cols-3 mb-2">
      <div class="flex justify-center space-x-4 mb-4">
        <div>
          <label
            for="start_date"
            class="block text-sm font-medium text-gray-700"
            >Start Date:</label
          >
          <input
            type="date"
            id="start_date"
            v-model="startDate"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
        </div>

        <div class="px-2">
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
      </div>
      <button
        @click="fetchExpenses"
        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-1 px-2 border border-gray-400 rounded shadow"
      >
        Search
      </button>
    </div>

    <div class="mb-4">
      <button
        @click="openModal('add')"
        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
      >
        Add Expense
      </button>
    </div>

    <div v-if="paginatedExpenses.length > 0">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
        <div
          v-for="expense in paginatedExpenses"
          :key="expense.id"
          class="bg-white shadow-md rounded-lg overflow-hidden"
        >
          <div class="p-4">
            <div class="flex justify-between mb-2">
              <div class="flex-1 pr-4 w-3/4">
                <strong class="text-xl">{{ expense.category.category }}</strong>
                <p class="text-gray-500 truncate">{{ expense.description }}</p>
              </div>
              <div class="text-right flex-shrink-0 w-1/4">
                <p class="text-gray-500">{{ expense.date_of_expense }}</p>
                <p class="text-xl text-indigo-600 font-semibold">
                  Rs. {{ expense.amount }}
                </p>
              </div>
            </div>
            <div class="flex justify-end mt-4">
              <button
                @click="editExpense(expense)"
                class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md mr-2"
              >
                Edit
              </button>
              <button
                @click="deleteExpense(expense.id)"
                class="px-3 py-1 bg-red-600 text-white rounded-md"
              >
                Delete
              </button>
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

    <!-- Modal for Expense Form -->
    <div
      v-if="isModalOpen"
      class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75"
    >
      <div class="bg-white p-6 rounded-lg shadow-md">
        <button
          @click="closeModal"
          class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 focus:outline-none"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
        </button>
        <ExpenseForm
          v-if="modalData.action === 'add'"
          :button-text="'add'"
          :csrf-token="csrfToken"
          :categories-fetched="categories"
        />
        <ExpenseForm
          v-else-if="modalData.action === 'edit'"
          :expense-to-be-edited="modalData.expense"
          :button-text="'edit'"
          :csrf-token="csrfToken"
          :categories-fetched="categories"
        />
      </div>
    </div>
  </div>
</template>

<script>
import ExpenseForm from "./ExpenseForm.vue";
import BarChart from "./BarChart.vue";

export default {
  components: {
    ExpenseForm,
    BarChart,
  },
  data() {
    return {
      csrfToken: "",
      startDate: "",
      endDate: "",
      expenses: [],
      currentPage: 1,
      perPage: 10,
      isModalOpen: false,
      modalData: {
        expense: null,
        action: "",
      },
      categoryWiseExpenses: {},
      chartKey: 0,
      categories:[],
    };
  },
  methods: {
    fetchExpenses() {
      // Assuming you're using Axios for HTTP requests
      //  const auth_token = sessionStorage.getItem('auth_token');
      //  alert(auth_token);
      axios
        .get("/expense", {
          params: {
            start_date: this.startDate,
            end_date: this.endDate,
          },
        })
        .then((response) => {
          this.expenses = response.data;
          // console.log(this.expenses);
          this.categoryWiseExpenses = this.getCategoryWiseExpense(
            this.expenses
          );
          this.chartKey++;
          // console.log(this.categoryWiseExpenses);
        })
        .catch((error) => {
          console.error("Error fetching expenses:", error);
        });
    },

     async fetchCategories() {
      try {
        const response = await axios.get("/fetch-categories");
        this.categories = response.data;
        // console.log(this.categories);
      } catch (error) {
        console.error("Error fetching categories:", error);
      }
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

    deleteExpense(expense_id) {
      let response = confirm("Are you sure to delete the expense?");
      const headers = {
        "X-CSRF-TOKEN": this.csrfToken,
      };
      if (response) {
        axios.delete(`expense/${expense_id}`, { headers }).then((response) => {
          const index = this.expenses.findIndex(
            (expense) => expense.id === expense_id
          );
          this.expenses.splice(index, 1);
          this.chartKey++;
        });
      }
    },

    editExpense(expense) {
      this.modalData.expense = { ...expense };
      //   console.log(this.modalData);
      this.openModal("edit");
    },

    closeModal() {
      this.isModalOpen = false;
    },

    openModal(action) {
      if (action === "add") {
        this.modalData.action = "add";
        this.modalData.expense = null;
        this.isModalOpen = true;
      } else if (action === "edit") {
        this.modalData.action = "edit";
        this.isModalOpen = true;
      }
    },

    getCategoryWiseExpense(expenses) {
      // console.log(expenses);
      return expenses.reduce((accumilator, expense) => {
        const category = expense.category.category;

        const amount = parseFloat(expense.amount);

        if (!accumilator[category]) {
          accumilator[category] = 0;
        }
        accumilator[category] += amount;
        // console.log(accumilator);
        return accumilator;
      }, {});
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
    this.fetchCategories();
    // alert(this.csrfToken);
    this.csrfToken = document.head.querySelector(
      'meta[name="csrf-token"]'
    ).content;
  },
};
</script>

<style scoped>
</style>