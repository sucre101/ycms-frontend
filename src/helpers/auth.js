import { setAuthorization } from "./general"

export function login(credentials) {
  return new Promise((res, rej) => {
    axios.post('/auth/login', credentials)
      .then((response) => {
        setAuthorization(response.data.access_token)
        res(response.data)
      })
      .catch((err) =>{
        rej("Wrong email or password")
      })
  })
}

export function register(credentials) {
  return new Promise(((resolve, reject) => {
    axios.post('/auth/register', credentials)
        .then((res) => {
          setAuthorization(res.data.user.access_token);
          res(res.data.user);
        })
        .catch((err) => {
          reject('Something was wrong. Try again')
        })
  }))
}

export function refreshToken(credentials) {
  return new Promise((res, rej) => {
    axios.post('/auth/refresh', credentials)
      .then((response) => {
        setAuthorization(response.data.access_token);
        res(response.data);
      })
      .catch((err) => {
        rej(err)
      })
  })
}

export function getLocalUser() {
  const userStr = localStorage.getItem("user");

  if(!userStr) {
    return null;
  }

  return JSON.parse(userStr);
}
