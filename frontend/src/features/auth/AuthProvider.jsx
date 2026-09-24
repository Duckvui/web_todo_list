import { useEffect, useMemo, useState } from 'react'
import { navigateTo } from '../../app/useRoute'
import { authApi } from './authApi'

import { AuthContext } from './authContext'

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    authApi.me().then(setUser).catch(() => setUser(null)).finally(() => setLoading(false))
  }, [])

  const value = useMemo(() => ({
    user, loading, setUser,
    async login(credentials) {
      const response = await authApi.login(credentials)
      setUser(response.data)
      navigateTo('/dashboard')
    },
    async logout() {
      await authApi.logout()
      setUser(null)
      navigateTo('/dang-nhap')
    },
    async refreshUser() {
      setUser(await authApi.me())
    },
  }), [user, loading])

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}
