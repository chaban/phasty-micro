const getters = {
  sidebar: state => state.app.sidebar,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  name: state => state.user.name,
  role: state => state.user.role,
  id: state => state.user.id,
  centrifugo_token: state => state.user.centrifugo_token,
  centrifugo_timestamp: state => state.user.centrifugo_timestamp,
  centrifugo_info: state => state.user.centrifugo_info
}
export default getters
