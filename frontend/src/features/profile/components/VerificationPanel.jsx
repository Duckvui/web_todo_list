import { useState } from 'react'
import { Field } from '../../../components/ui/Field'
import { FormStatus } from '../../../components/ui/FormStatus'
import { getApiErrors } from '../../../lib/http'
import { useAuth } from '../../auth/useAuth'
import { authApi } from '../../auth/authApi'

export function VerificationPanel() {
  const { user, refreshUser } = useAuth()
  const [challenge, setChallenge] = useState(null)
  const [code, setCode] = useState('')
  const [status, setStatus] = useState({})
  const [loading, setLoading] = useState(false)
  const profile = user.thong_tin_tai_khoan

  async function send(channel) {
    setStatus({}); setLoading(true)
    try {
      const response = await authApi.sendVerification(channel)
      setChallenge({ id: response.challenge_id, channel })
      setStatus({ success: response.message })
    } catch (error) { setStatus({ error: getApiErrors(error).general ?? Object.values(getApiErrors(error))[0] }) } finally { setLoading(false) }
  }
  async function verify() {
    setStatus({}); setLoading(true)
    try {
      const response = await authApi.verifyCode({ challenge_id: challenge.id, code })
      setChallenge(null); setCode(''); await refreshUser(); setStatus({ success: response.message })
    } catch (error) { setStatus({ error: getApiErrors(error).general ?? Object.values(getApiErrors(error))[0] }) } finally { setLoading(false) }
  }

  const channels = [
    { key: 'email', label: 'Gmail', value: profile?.email, verified: user.email_verified_at },
    { key: 'sms', label: 'Số điện thoại', value: profile?.so_dien_thoai, verified: user.phone_verified_at },
  ]
  return <section className="panel"><header className="panel-head"><h2>Xác minh tài khoản</h2><p>Xác minh kênh liên hệ trước khi dùng để khôi phục mật khẩu.</p></header><FormStatus {...status} /><div className="verify-list">{channels.map((item) => <div className="verify-row" key={item.key}><div className="verify-info"><strong>{item.label}</strong><span>{item.value ?? 'Chưa có thông tin'}</span></div>{item.verified ? <span className="verified">✓ Đã xác minh</span> : <button className="button button-secondary" disabled={!item.value || loading} onClick={() => send(item.key)}>{loading ? 'Đang gửi...' : 'Gửi mã'}</button>}</div>)}</div>{challenge && <div className="otp-box"><Field label="Mã xác minh 6 số" name="verification_code" value={code} onChange={(event) => setCode(event.target.value)} maxLength="6" inputMode="numeric" placeholder="000000" /><div className="form-meta"><button className="button button-primary" disabled={loading || code.length !== 6} onClick={verify}>Xác minh</button><button className="text-button" onClick={() => setChallenge(null)}>Hủy</button></div></div>}</section>
}
