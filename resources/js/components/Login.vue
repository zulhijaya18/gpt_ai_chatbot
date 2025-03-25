<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
      
      <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ error }}
      </div>
      
      <form @submit.prevent="login">
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Email</label>
          <input 
            type="email" 
            v-model="email" 
            class="w-full p-2 border rounded"
            required
          >
        </div>
        
        <div class="mb-6">
          <label class="block text-gray-700 mb-2">Password</label>
          <input 
            type="password" 
            v-model="password" 
            class="w-full p-2 border rounded"
            required
          >
        </div>
        
        <button 
          type="submit" 
          class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
          :disabled="loading"
        >
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
      
      <div class="mt-4 text-center">
        <p>Don't have an account? <a href="/register" class="text-blue-500">Register</a></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const email = ref('');
const password = ref('');
const error = ref('');
const loading = ref(false);

const login = async () => {
  loading.value = true;
  error.value = '';
  
  try {
    const response = await axios.post('/api/login', {
      email: email.value,
      password: password.value
    });
    
    localStorage.setItem('token', response.data.access_token);
    window.location.href = '/chat';
  } catch (err) {
    error.value = err.response?.data?.error || 'Login failed';
  } finally {
    loading.value = false;
  }
};
</script>