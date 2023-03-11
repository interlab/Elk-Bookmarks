import axios from 'axios';

// export const HTTP = axios.create({
  // baseURL: `http://jsonplaceholder.typicode.com/`,
  // headers: {
    // Authorization: 'Bearer {token}'
  // }
// })

// https://github.com/axios/axios#installing

const HTTP = axios.create();

// https://stackoverflow.com/a/59299295
function getToken() {
    return axios.CancelToken.source();
}

export {
  HTTP, getToken
}

