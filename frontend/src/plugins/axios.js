import axios from "axios";
import store from "@/plugins/store";

const custom_axios = axios.create({
    baseURL: 'http://127.0.0.1:8000',
});

custom_axios.interceptors.request.use(
    function(config) {
        const token = store.getters.token; // Asegúrate de que el token sea correcto
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    function(error) {
        return Promise.reject(error);
    }
);

export default custom_axios;
