import axios from "axios";
import store from "@/plugins/store";

const custom_axios = axios.create(
    {
        baseURL: 'http://127.0.0.1:8000',
    }
)

custom_axios.interceptors.request.use(
    function(config){
        const token = store.getters.token;
        if (token.length > 0) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config;
    }
)

export default custom_axios;