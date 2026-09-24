import { useState } from 'react'
import { useAuth } from '../features/auth/useAuth'
import { AppSidebar } from './components/AppSidebar'
import { AppTopbar } from './components/AppTopbar'
import './workspace.css'

export function AppShell({ children }) {
  const { user, logout } = useAuth()
  const [error, setError] = useState('')
  const [busy, setBusy] = useState(false)
  async function signOut() {
    setBusy(true)
    setError('')
    try { await logout() } catch { setError('Không thể đăng xuất. Vui lòng thử lại.') } finally { setBusy(false) }
  }
  return <div className="workspace"><AppSidebar user={user} onLogout={signOut} busy={busy} /><div className="workspace-body"><AppTopbar user={user} /><main className="workspace-main">{error && <p className="alert alert-error" role="alert">{error}</p>}{children}</main><footer className="workspace-footer"><span>Focusly · Từng việc nhỏ, một ngày tốt hơn.</span><span>Không gian của bạn</span></footer></div></div>
}
