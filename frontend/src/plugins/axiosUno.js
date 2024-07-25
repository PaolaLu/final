import axios from 'axios';

const custom_axios = axios.create({
  baseURL: 'http://127.0.0.1:8000',  // Cambia esto a 127.0.0.1 en lugar de localhost
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': '*/*'  // Cambia esto para que coincida con la petición de la terminal
  }
});

custom_axios.interceptors.request.use(
  (config) => {
    const token = sessionStorage.getItem('accessToken');
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;
    }
    // Elimina el header 'Content-Type' para peticiones GET
    if (config.method === 'get') {
      delete config.headers['Content-Type'];
    }
    console.log('Request config:', config);
    return config;
  },
  (error) => {
    console.error('Request error:', error);
    return Promise.reject(error);
  }
);

custom_axios.interceptors.response.use(
  (response) => {
    console.log('Response:', response);
    return response;
  },
  (error) => {
    console.error('Response error:', error);
    if (error.response) {
      console.error('Error data:', error.response.data);
      console.error('Error status:', error.response.status);
      console.error('Error headers:', error.response.headers);
    } else if (error.request) {
      console.error('No response received:', error.request);
    } else {
      console.error('Error setting up request:', error.message);
    }
    return Promise.reject(error);
  }
);

export default custom_axios;
  

