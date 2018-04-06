import request from '@/utils/request'

export function login(email, password) {
  return request({
    url: '/auth/login',
    method: 'post',
    data: {
      email,
      password
    }
  })
}

export function getInfo(token) {
  return request({
    url: '/auth/getUserInfo',
    method: 'get'
  })
}

export function logout(token) {
  return request({
    url: '/auth/logout',
    method: 'delete',
    data: { 'access_token': token }
  })
}
