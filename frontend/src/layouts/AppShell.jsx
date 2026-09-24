import { useAuth } from '../features/auth/useAuth'

export function AppShell({ children }) {
  const { user, logout } = useAuth()
  return <div className="app-shell"><aside className="sidebar"><div className="brand-mark"><span className="brand-icon">✓</span>Focusly</div><span className="nav-label">Không gian làm việc</span><div className="nav-item active">◎ Tài khoản</div><div className="sidebar-user"><strong>{user.thong_tin_tai_khoan?.ho_ten}</strong><span>{user.tai_khoan}</span><button className="button" onClick={logout}>Đăng xuất</button></div></aside><main className="app-main">{children}</main></div>
}
