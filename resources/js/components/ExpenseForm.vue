<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12 sm:col-md-6">
        <div class="max-w-xs p-6 bg-white rounded-lg shadow-md">
          <h2 class="text-2xl font-bold mb-6 text-gray-700 text-center">
            Add Expense
          </h2>
          <form @submit.prevent="submitForm">
            <div class="mb-4">
              <label
                class="block text-gray-700 text-sm font-medium mb-2"
                for="category"
                >Category:</label
              >
              <select
                id="category"
                v-model="formData.category_id"
                required
                :class="{ 'border-red-500': errors.category_id }"
                class="w-full sm:w-1/2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Select a category</option>
                <option
                  v-for="category in categories"
                  :key="category.id"
                  :value="category.id"
                >
                  {{ category.category }}
                </option>
              </select>
              <span v-if="errors.category_id" class="text-red-500 text-sm">{{
                errors.category_id[0]
              }}</span>
            </div>
            <div class="mb-4">
              <label
                class="block text-gray-700 text-sm font-medium mb-2"
                for="amount"
                >Amount:</label
              >
              <input
                class="w-full sm:w-1/2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                id="amount"
                v-model="formData.amount"
                :class="{ 'border-red-500': errors.amount }"
              />
              <span v-if="errors.amount" class="text-red-500 text-sm">{{
                errors.amount[0]
              }}</span>
            </div>
            <div class="mb-4">
              <label
                class="block text-gray-700 text-sm font-medium mb-2"
                for="description"
                >Description:</label
              >
              <textarea
                class="w-full sm:w-1/2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                id="description"
                v-model="formData.description"
                :class="{ 'border-red-500': errors.description }"
              ></textarea>
              <span v-if="errors.description" class="text-red-500 text-sm">{{
                errors.description[0]
              }}</span>
            </div>
            <div class="mb-4">
              <label
                class="block text-gray-700 text-sm font-medium mb-2"
                for="date"
                >Date of Expense:</label
              >
              <input
                type="date"
                id="date"
                v-model="formData.date_of_expense"
                required
                class="w-full sm:w-1/2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.date_of_expense }"
              />
              <span
                v-if="errors.date_of_expense"
                class="text-red-500 text-sm"
                >{{ errors.date_of_expense[0] }}</span
              >
            </div>
            <div class="mb-4 flex items-center justify-center">
              <button
                class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded"
              >
                {{ buttonText === "add" ? "Add Expense" : "Update" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    expenseToBeEdited: {
      type: Object,
      default: null,
    },
    buttonText: {
      type: String,
      required: true,
    },
    csrfToken: {
      type: String,
      required: true,
    },
    categoriesFetched:{
      type:Array,
    }
  },
  data() {
    return {
      formData: {
        category_id: "",
        amount: null,
        description: "",
        date_of_expense: "",
        _token: "",
      },
      categories: [],
      errors: {},
    };
  },
  mounted() {
    this.categories = this.categoriesFetched

    if (this.$props.expenseToBeEdited) {
      this.formData.category_id = this.expenseToBeEdited.category_id;
      this.formData.amount = this.expenseToBeEdited.amount;
      this.formData.description = this.expenseToBeEdited.description;
      this.formData.date_of_expense = this.expenseToBeEdited.date_of_expense;
    }
  },
  methods: {
    async submitForm() {
      try {
        this.errors = {};

        this.formData._token = this.$props.csrfToken;

        // Client-side validation
        if (!this.formData.category_id) {
          this.errors.category_id = ["Please select a category."];
        }
        if (!this.formData.amount) {
          this.errors.amount = ["Please enter the amount."];
        }
        if (this.formData.amount <= 0 || isNaN(this.formData.amount)) {
          this.errors.amount = ["Please enter a valid amount."];
        }
        if (this.formData.description.length > 200) {
          this.errors.description = [
            "Description should be less than 200 characters",
          ];
        }
        if (!this.formData.date_of_expense) {
          this.errors.date_of_expense = ["Please select a date."];
        } else {
          const selectedDate = new Date(this.formData.date_of_expense);
          const currentDate = new Date();

          if (selectedDate > currentDate) {
            this.errors.date_of_expense = ["Future date cannot be added"];
          }
        }

        if (Object.keys(this.errors).length > 0) {
          return;
        }

        let response;
        if (this.buttonText === "add") {
          response = await axios.post("/expense", this.formData);
        } else if (this.buttonText === "edit" && this.expenseToBeEdited) {
          const url = `/expense/${this.expenseToBeEdited.id}`;
          response = await axios.put(url, this.formData);
        }

        if (response && response.data && response.data.redirect) {
          window.location = response.data.redirect;
        }
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          console.error("Error submitting form:", error);
        }
      }
    },
  },
};
</script>

<style>
</style>
