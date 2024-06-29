<template>
 <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="max-w-xs p-6 bg-white rounded-lg shadow-md">
          <h2 class="text-2xl font-bold mb-6 text-gray-700 text-center">Add Expense</h2>
          <form @submit.prevent="submitForm">
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-medium mb-2" for="category">Category:</label>
              <select
                id="category"
                v-model="formData.category_id"
                required
                class="block w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-medium mb-2" for="amount">Amount:</label>
              <input
                class="block w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                type="number"
                id="amount"
                v-model="formData.amount"
                required
              />
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-medium mb-2" for="description">Description:</label>
              <textarea
                class="block w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                id="description"
                v-model="formData.description"
              ></textarea>
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-medium mb-2" for="date">Date of Expense:</label>
              <input
                type="date"
                id="date"
                v-model="formData.date_of_expense"
                required
                class="block w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <div class="mb-4 flex items-center justify-center">
              <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                Add Expense
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
  data() {
    return {
      formData: {
        category_id: "",
        amount: null,
        description: "",
        date_of_expense: "",
      },
      categories: [],
    };
  },
   mounted() {
    this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      try {
        const response = await axios.get('/fetch-categories');
        this.categories = response.data;
        console.log(this.categories)
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    submitForm() {
      console.log("Form submitted");
      console.log(this.formData); // Access form data here for further processing
    },
  },
};
</script>

<style>
</style>
