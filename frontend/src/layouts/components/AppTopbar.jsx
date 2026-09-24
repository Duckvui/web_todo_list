import { AppLink } from '../../components/ui/AppLink'
export function AppTopbar({ user }) {
  const name = user.thong_tin_tai_khoan?.ho_ten || user.tai_khoan || 'Bạn'
  return <header className="workspace-topbar"><span>Không gian cá nhân <span className="topbar-divider">/</span> <strong>Focusly</strong></span><AppLink to="/tai-khoan" className="topbar-profile"><span>{name}</span><span className="avatar">{name.slice(0, 1).toUpperCase()}</span></AppLink></header>
}
