import { useAuth } from '../../auth/useAuth'
import { PasswordForm } from '../components/PasswordForm'
import { ProfileForm } from '../components/ProfileForm'
import { VerificationPanel } from '../components/VerificationPanel'

export function DashboardPage() {
  const { user } = useAuth()
  return <><header className="page-header"><div><h1>Cài đặt tài khoản</h1><p>Quản lý hồ sơ, xác minh liên hệ và bảo mật đăng nhập.</p></div><span className="status-pill">{user.trang_thai === 'hoat_dong' ? '● Đang hoạt động' : user.trang_thai}</span></header><div className="dashboard-grid"><div><ProfileForm /><PasswordForm /></div><VerificationPanel /></div></>
}
