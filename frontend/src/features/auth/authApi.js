import { http, prepareCsrf } from '../../lib/http'

async function mutate(method, url, data) {
  await prepareCsrf()
  return (await http.request({ method, url, data })).data
}

export const authApi = {
  me: async () => (await http.get('/api/me')).data.data,
  register: (data) => mutate('post', '/api/dang-ky', data),
  login: (data) => mutate('post', '/api/dang-nhap', data),
  logout: () => mutate('post', '/api/dang-xuat'),
  forgotPassword: (data) => mutate('post', '/api/quen-mat-khau', data),
  resetPassword: (data) => mutate('post', '/api/dat-lai-mat-khau', data),
  updateProfile: (data) => mutate('patch', '/api/thong-tin-ca-nhan', data),
  changePassword: (data) => mutate('put', '/api/doi-mat-khau', data),
  sendVerification: (channel) => mutate('post', '/api/xac-minh/gui-ma', { channel }),
  verifyCode: (data) => mutate('post', '/api/xac-minh/kiem-tra', data),
}
