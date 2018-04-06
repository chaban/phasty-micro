import request from '@/utils/request'
const url = '/users'

export function getAll(query) {
  return request({
    url: url,
    method: 'get',
    params: query
  })
}

export function getItem(id) {
  return request({
    url: url + '/' + id,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url,
    method: 'post',
    data
  })
}

export function updateItem(id, data) {
  return request({
    url: url + '/' + id,
    method: 'patch',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: url + '/' + id,
    method: 'delete'
  })
}

export const rules = {
  name: [
    {
      required: true,
      message: 'Please input user name',
      trigger: 'blur'
    },
    {
      min: 3,
      max: 255,
      message: 'Length should be 3 to 255 characters',
      trigger: 'blur'
    }
  ],
  email: [
    { required: true, message: 'Please input email address', trigger: 'blur' },
    { type: 'email', message: 'Please input correct email address', trigger: 'blur,change' }
  ],
  password: [
    { required: true, message: 'Please input password', trigger: 'blur' },
    { min: 6, max: 255, message: 'Length should be 6 to 255 characters', trigger: 'blur' }
  ]
}

export const roles = [{
  value: 'user',
  label: 'User'
}, {
  value: 'admin',
  label: 'Administrator'
}]
