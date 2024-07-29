import custom_axios from 'axios';
import { EventBus } from '@/eventBus';
const API_URL = 'http://127.0.0.1:8000/apiv1/auth/';

class AuthService {
  login(username, passwd) {
    return custom_axios
      .post(API_URL + 'login', {
        username,
        passwd
      })
      .then(response => {
        if (response.data.success) {
          localStorage.setItem('user', JSON.stringify(response.data.user));
          EventBus.$emit('loginSuccess', response.data.user);
        }
        return response.data;
      });
  }

  logout() {
    localStorage.removeItem('user');
    return custom_axios.post(API_URL + 'logout').then(response => {
      return response.data;
    });
  }

  register(username, passwd) {
    return custom_axios.post(API_URL + 'signup', {
      username,
      passwd
    });
  }
}

export default new AuthService();