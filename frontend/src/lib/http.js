import axios from 'axios'

export const http = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost:8000',
  withCredentials: true,
  withXSRFToken: true,
  headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
})

export async function prepareCsrf() {
  await http.get('/sanctum/csrf-cookie')
}

export function getApiErrors(error) {
  const errors = error.response?.data?.errors
  if (errors) return Object.fromEntries(Object.entries(errors).map(([key, messages]) => [key, messages[0]]))
  return { general: error.response?.data?.message ?? 'Không thể kết nối máy chủ. Vui lòng thử lại.' }
}
