import { useState } from 'react'
import { AppLink } from '../../../components/ui/AppLink'
import { Field, SelectField } from '../../../components/ui/Field'
import { FormStatus } from '../../../components/ui/FormStatus'
import { SubmitButton } from '../../../components/ui/SubmitButton'
import { navigateTo } from '../../../app/useRoute'
import { getApiErrors } from '../../../lib/http'
import { authApi } from '../authApi'

export function ForgotPasswordPage() {
  const [form, setForm] = useState({ tai_khoan: '', channel: 'email' })
  const [errors, setErrors] = useState({})
  const [loading, setLoading] = useState(false)
  const update = (event) => setForm({ ...form, [event.target.name]: event.target.value })
  async function submit(event) {
    event.preventDefault(); setErrors({}); setLoading(true)
    try {
      const response = await authApi.forgotPassword(form)
      navigateTo('/dat-lai-mat-khau', { state: { challengeId: response.challenge_id, message: response.message } })
    } catch (error) { setErrors(getApiErrors(error)) } finally { setLoading(false) }
  }
  return <section className="auth-card"><header className="form-heading"><h2>Lấy lại mật khẩu</h2><p>Chọn kênh đã xác minh để nhận mã khôi phục.</p></header><form className="form" onSubmit={submit}><FormStatus error={errors.general} /><Field label="Tài khoản" name="tai_khoan" value={form.tai_khoan} onChange={update} error={errors.tai_khoan} placeholder="Gmail hoặc số điện thoại" /><SelectField label="Nhận mã qua" name="channel" value={form.channel} onChange={update} error={errors.channel}><option value="email">Gmail</option><option value="sms">Tin nhắn SMS</option></SelectField><SubmitButton loading={loading}>Gửi mã khôi phục</SubmitButton></form><p className="switch-copy"><AppLink to="/dang-nhap">← Quay lại đăng nhập</AppLink></p></section>
}
