import axios from "axios";

const custom_axios = axios.create(
    {
        baseURL: 'http://127.0.0.1:8000',
    }
)
export default custom_axios;