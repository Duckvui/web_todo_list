import { useState } from 'react'
import { AppLink } from '../../../components/ui/AppLink'
import { Field } from '../../../components/ui/Field'
import { FormStatus } from '../../../components/ui/FormStatus'
import { SubmitButton } from '../../../components/ui/SubmitButton'
import { getApiErrors } from '../../../lib/http'
import { useAuth } from '../useAuth'

export function LoginPage() {
  const { login } = useAuth()
  const [form, setForm] = useState({ tai_khoan: '', mat_khau: '' })
  const [errors, setErrors] = useState({})
  const [loading, setLoading] = useState(false)
  const update = (event) => setForm({ ...form, [event.target.name]: event.target.value })

  async function submit(event) {
    event.preventDefault(); setErrors({}); setLoading(true)
    try { await login(form) } catch (error) { setErrors(getApiErrors(error)) } finally { setLoading(false) }
  }

  return <section className="auth-card"><header className="form-heading"><h2>Chào mừng trở lại</h2><p>Đăng nhập để tiếp tục công việc của bạn.</p></header><form className="form" onSubmit={submit}><FormStatus error={errors.general} /><Field label="Gmail hoặc số điện thoại" name="tai_khoan" value={form.tai_khoan} onChange={update} error={errors.tai_khoan} placeholder="example@gmail.com hoặc 0912345678" autoComplete="username" /><Field label="Mật khẩu" name="mat_khau" type="password" value={form.mat_khau} onChange={update} error={errors.mat_khau} placeholder="Nhập mật khẩu" autoComplete="current-password" /><div className="form-meta"><span /><AppLink to="/quen-mat-khau">Quên mật khẩu?</AppLink></div><SubmitButton loading={loading}>Đăng nhập</SubmitButton></form><p className="switch-copy">Chưa có tài khoản? <AppLink to="/dang-ky">Đăng ký miễn phí</AppLink></p></section>
}
