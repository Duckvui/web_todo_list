import { useEffect } from 'react'
import { AuthProvider } from './features/auth/AuthProvider'
import { useAuth } from './features/auth/useAuth'
import { useRoute } from './app/useRoute'
import { AuthLayout } from './layouts/AuthLayout'
import { AppShell } from './layouts/AppShell'
import { LoginPage } from './features/auth/pages/LoginPage'
import { RegisterPage } from './features/auth/pages/RegisterPage'
import { ForgotPasswordPage } from './features/auth/pages/ForgotPasswordPage'
import { ResetPasswordPage } from './features/auth/pages/ResetPasswordPage'
import { DashboardPage } from './features/profile/pages/DashboardPage'
import { HomePage } from './features/home/pages/HomePage'

const publicPages = {
  '/dang-nhap': LoginPage,
  '/dang-ky': RegisterPage,
  '/quen-mat-khau': ForgotPasswordPage,
  '/dat-lai-mat-khau': ResetPasswordPage,
}

function AppRouter() {
  const { path, navigate } = useRoute()
  const { user, loading } = useAuth()
  const target = user ? (path === '/tai-khoan' ? path : '/dashboard') : (publicPages[path] ? path : '/dang-nhap')

  useEffect(() => {
    if (path !== target) navigate(target, { replace: true })
  }, [path, target, navigate])

  if (loading) return <div className="page-loader"><span className="spinner" />Đang kiểm tra phiên đăng nhập...</div>
  if (user) return <AppShell>{target === '/tai-khoan' ? <DashboardPage /> : <HomePage key={user.id} />}</AppShell>
  const Page = publicPages[target] ?? LoginPage
  return <AuthLayout><Page /></AuthLayout>
}

export default function App() {
  return <AuthProvider><AppRouter /></AuthProvider>
}
