import { AppLink } from '../../components/ui/AppLink'
import { useRoute } from '../../app/useRoute'

export function AppSidebar({ user, onLogout, busy }) {
  const { path } = useRoute()
  return <aside className="workspace-sidebar">
    <AppLink to="/dashboard" className="brand-mark"><span className="brand-icon">✓</span>Focusly.</AppLink>
    <p className="workspace-nav-label">KHÔNG GIAN LÀM VIỆC</p>
    <nav aria-label="Điều hướng chính">{[['/dashboard', '▦', 'Trang chủ'], ['/tai-khoan', '⚙', 'Tài khoản']].map(([to, icon, label]) => <AppLink key={to} to={to} className={`workspace-nav ${path === to ? 'selected' : ''}`} aria-current={path === to ? 'page' : undefined}><span aria-hidden="true">{icon}</span>{label}</AppLink>)}</nav>
    <div className="sidebar-note"><span>✳</span><strong>Ít phân tâm.<br />Nhiều tập trung hơn.</strong><p>Dành chỗ cho những việc thực sự quan trọng.</p></div>
    <div className="workspace-account"><strong>{user.thong_tin_tai_khoan?.ho_ten || 'Tài khoản của bạn'}</strong><small>{user.tai_khoan}</small><button onClick={onLogout} disabled={busy}>{busy ? 'Đang đăng xuất…' : 'Đăng xuất ↗'}</button></div>
  </aside>
}
