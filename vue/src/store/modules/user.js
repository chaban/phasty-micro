import { login, getInfo } from '@/api/login'
import { getToken, setToken, removeToken } from '@/utils/auth'

const user = {
  state: {
    token: getToken(),
    id: null,
    name: '',
    email: '',
    avatar: '',
    role: '',
    centrifugo_token: '',
    centrifugo_timestamp: null,
    centrifugo_info: ''
  },

  mutations: {
    SET_TOKEN: (state, token) => {
      state.token = token
    },
    SET_ID: (state, id) => {
      state.id = id
    },
    SET_NAME: (state, name) => {
      state.name = name
    },
    SET_EMAIL: (state, email) => {
      state.email = email
    },
    SET_AVATAR: (state, avatar) => {
      state.avatar = avatar
    },
    SET_ROLE: (state, role) => {
      state.role = role
    },
    SET_CENTRIFUGO_TOKEN: (state, centrifugo_token) => {
      state.centrifugo_token = centrifugo_token
    },
    SET_CENTRIFUGO_TIMESTAMP: (state, centrifugo_timestamp) => {
      state.centrifugo_timestamp = centrifugo_timestamp
    },
    SET_CENTRIFUGO_INFO: (state, centrifugo_info) => {
      state.centrifugo_info = centrifugo_info
    }
  },

  actions: {
    // 登录
    Login({ commit }, userInfo) {
      const email = userInfo.email.trim()
      return new Promise((resolve, reject) => {
        login(email, userInfo.password).then(response => {
          const data = response.data
          setToken(data.access_token)
          commit('SET_TOKEN', data.access_token)
          commit('SET_EMAIL', email)
          resolve()
        }).catch(error => {
          reject(error)
        })
      })
    },

    // 获取用户信息
    GetInfo({ commit, state }) {
      return new Promise((resolve, reject) => {
        getInfo(state.token).then(response => {
          const data = response.data
          console.log(data)
          commit('SET_ROLE', data.role)
          commit('SET_NAME', data.name)
          commit('SET_ID', data.id)
          commit('SET_CENTRIFUGO_TOKEN', data.centrifugo_token)
          commit('SET_CENTRIFUGO_TIMESTAMP', data.centrifugo_timestamp)
          commit('SET_CENTRIFUGO_INFO', data.centrifugo_info)
          // commit('SET_AVATAR', data.avatar)
          resolve(response)
        }).catch(error => {
          reject(error)
        })
      })
    },

    // 登出
    LogOut({ commit, state }) {
      return new Promise((resolve, reject) => {
        // logout(state.token).then(() => {
        commit('SET_TOKEN', '')
        commit('SET_ROLE', '')
        commit('SET_CENTRIFUGO_TOKEN', '')
        commit('SET_CENTRIFUGO_TIMESTAMP', '')
        commit('SET_CENTRIFUGO_INFO', '')
        removeToken()
        resolve()
        // }).catch(error => {
        //   reject(error)
        // })
      })
    },

    // 前端 登出
    FedLogOut({ commit }) {
      return new Promise(resolve => {
        commit('SET_TOKEN', '')
        removeToken()
        resolve()
      })
    }
  }
}

export default user
