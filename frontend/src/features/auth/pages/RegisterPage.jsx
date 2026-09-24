import { useState } from 'react'
import { AppLink } from '../../../components/ui/AppLink'
import { Field, SelectField } from '../../../components/ui/Field'
import { FormStatus } from '../../../components/ui/FormStatus'
import { SubmitButton } from '../../../components/ui/SubmitButton'
import { navigateTo } from '../../../app/useRoute'
import { getApiErrors } from '../../../lib/http'
import { authApi } from '../authApi'

const initial = { tai_khoan: '', mat_khau: '', mat_khau_confirmation: '', ho_ten: '', ngay_sinh: '', gioi_tinh: '' }

export function RegisterPage() {
  const [form, setForm] = useState(initial)
  const [errors, setErrors] = useState({})
  const [loading, setLoading] = useState(false)
  const update = (event) => setForm({ ...form, [event.target.name]: event.target.value })

  async function submit(event) {
    event.preventDefault(); setErrors({}); setLoading(true)
    try {
      await authApi.register(Object.fromEntries(Object.entries(form).filter(([, value]) => value !== '')))
      navigateTo('/dang-nhap', { state: { message: 'Đăng ký thành công. Bạn có thể đăng nhập.' } })
    } catch (error) { setErrors(getApiErrors(error)) } finally { setLoading(false) }
  }

  return <section className="auth-card wide"><header className="form-heading"><h2>Tạo tài khoản</h2><p>Dùng Gmail hoặc số điện thoại làm tài khoản đăng nhập.</p></header><form className="form" onSubmit={submit}><FormStatus error={errors.general} /><div className="form-grid"><Field label="Họ và tên" name="ho_ten" value={form.ho_ten} onChange={update} error={errors.ho_ten} placeholder="Nguyễn Văn Đức" autoComplete="name" /><Field label="Gmail hoặc số điện thoại" name="tai_khoan" value={form.tai_khoan} onChange={update} error={errors.tai_khoan} placeholder="example@gmail.com" autoComplete="username" /><Field label="Ngày sinh (không bắt buộc)" name="ngay_sinh" type="date" value={form.ngay_sinh} onChange={update} error={errors.ngay_sinh} /><SelectField label="Giới tính (không bắt buộc)" name="gioi_tinh" value={form.gioi_tinh} onChange={update} error={errors.gioi_tinh}><option value="">Chưa chọn</option><option value="nam">Nam</option><option value="nu">Nữ</option><option value="khac">Khác</option></SelectField><Field label="Mật khẩu" name="mat_khau" type="password" value={form.mat_khau} onChange={update} error={errors.mat_khau} hint="8–20 ký tự, bắt đầu bằng chữ hoa và có ký tự đặc biệt." autoComplete="new-password" /><Field label="Nhập lại mật khẩu" name="mat_khau_confirmation" type="password" value={form.mat_khau_confirmation} onChange={update} error={errors.mat_khau_confirmation} autoComplete="new-password" /></div><SubmitButton loading={loading}>Tạo tài khoản</SubmitButton></form><p className="switch-copy">Đã có tài khoản? <AppLink to="/dang-nhap">Đăng nhập</AppLink></p></section>
}
