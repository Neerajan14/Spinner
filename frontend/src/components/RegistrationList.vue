<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const registrants = ref([])

onMounted(async () => {
  try {
    const resp = await axios.get(`${API}/index`)
    // const resp = await axios.get('{.netlify/functions/index}')
    registrants.value = resp.data
  } catch (error) {
    console.error("Failed to load registrants:", error)
  }
})
</script>

<template>
  <div class="registration-list">
    <h2>Participants List</h2>
    <div class="table-wrapper">
      <div class="table-scroll">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Number</th>
              <th>Interested</th>
              <th>Address</th>
              <th>Resume</th>
              <th>Won Item</th>
              <th>Created At</th>
              <th>Updated At</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in registrants" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.number }}</td>
              <td>{{ user.interested }}</td>
              <td>{{ user.address || '-' }}</td>
              <td>{{ user.resume_file_name || '-' }}</td>
              <td>{{ user.won_item || '-' }}</td>
              <td>{{ new Date(user.created_at).toLocaleString() }}</td>
              <td>{{ new Date(user.updated_at).toLocaleString() }}</td>
            </tr>
            <tr v-if="registrants.length === 0">
              <td colspan="10">No participants found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>
.registration-list {
  padding: 30px;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
  background-color: #f0f2f5;
}

.registration-list h2 {
  margin-bottom: 20px;
  color: #4f46e5;
  /* modern purple */
  font-size: 1.8rem;
  letter-spacing: 0.5px;
}

.table-wrapper {
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  background-color: #fff;
  padding: 15px;
}

/* Horizontal scroll container */
.table-scroll {
  overflow-x: auto;
}

table {
  width: 100%;
  min-width: 900px;
  /* ensures scroll if viewport is smaller */
  border-collapse: separate;
  border-spacing: 0;
}

thead {
  background-color: #4f46e5;
  color: #fff;
  font-weight: 600;
  position: sticky;
  top: 0;
  z-index: 1;
}

th,
td {
  padding: 15px 20px;
  text-align: left;
  border-bottom: 1px solid #e0e0e0;
}

tbody tr:nth-child(even) {
  background-color: #f9f9ff;
}

tbody tr:hover {
  background-color: #e0e7ff;
  cursor: default;
  transition: background-color 0.2s ease;
}

tbody td {
  vertical-align: middle;
}
</style>
