import { useState } from 'react'
import { AppLink } from '../../../components/ui/AppLink'
import { Field } from '../../../components/ui/Field'
import { FormStatus } from '../../../components/ui/FormStatus'
import { SubmitButton } from '../../../components/ui/SubmitButton'
import { navigateTo } from '../../../app/useRoute'
import { getApiErrors } from '../../../lib/http'
import { authApi } from '../authApi'

export function ResetPasswordPage() {
  const state = window.history.state ?? {}
  const [form, setForm] = useState({ challenge_id: state.challengeId ?? '', code: '', mat_khau: '', mat_khau_confirmation: '' })
  const [errors, setErrors] = useState({})
  const [loading, setLoading] = useState(false)
  const update = (event) => setForm({ ...form, [event.target.name]: event.target.value })
  async function submit(event) {
    event.preventDefault(); setErrors({}); setLoading(true)
    try {
      await authApi.resetPassword(form)
      navigateTo('/dang-nhap', { state: { message: 'Đặt lại mật khẩu thành công.' } })
    } catch (error) { setErrors(getApiErrors(error)) } finally { setLoading(false) }
  }
  return <section className="auth-card"><header className="form-heading"><h2>Đặt mật khẩu mới</h2><p>Nhập mã 6 số đã nhận và mật khẩu mới.</p></header><form className="form" onSubmit={submit}><FormStatus error={errors.general} success={state.message} /><Field label="Mã yêu cầu" name="challenge_id" value={form.challenge_id} onChange={update} error={errors.challenge_id} placeholder="Tự điền sau khi gửi mã" /><Field label="Mã xác minh" name="code" value={form.code} onChange={update} error={errors.code} inputMode="numeric" maxLength="6" placeholder="000000" /><Field label="Mật khẩu mới" name="mat_khau" type="password" value={form.mat_khau} onChange={update} error={errors.mat_khau} hint="8–20 ký tự, bắt đầu bằng chữ hoa và có ký tự đặc biệt." /><Field label="Nhập lại mật khẩu" name="mat_khau_confirmation" type="password" value={form.mat_khau_confirmation} onChange={update} error={errors.mat_khau_confirmation} /><SubmitButton loading={loading}>Đặt lại mật khẩu</SubmitButton></form><p className="switch-copy"><AppLink to="/dang-nhap">← Quay lại đăng nhập</AppLink></p></section>
}
