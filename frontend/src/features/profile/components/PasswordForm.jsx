import { useState } from 'react'
import { Field } from '../../../components/ui/Field'
import { FormStatus } from '../../../components/ui/FormStatus'
import { SubmitButton } from '../../../components/ui/SubmitButton'
import { getApiErrors } from '../../../lib/http'
import { useAuth } from '../../auth/useAuth'
import { authApi } from '../../auth/authApi'

export function PasswordForm() {
  const { setUser } = useAuth()
  const [form, setForm] = useState({ mat_khau_hien_tai: '', mat_khau: '', mat_khau_confirmation: '' })
  const [errors, setErrors] = useState({})
  const [loading, setLoading] = useState(false)
  const update = (event) => setForm({ ...form, [event.target.name]: event.target.value })
  async function submit(event) {
    event.preventDefault(); setErrors({}); setLoading(true)
    try {
      await authApi.changePassword(form)
      setUser(null)
      window.history.replaceState({ message: 'Đổi mật khẩu thành công. Hãy đăng nhập lại.' }, '', '/dang-nhap')
      window.dispatchEvent(new PopStateEvent('popstate'))
    } catch (error) { setErrors(getApiErrors(error)) } finally { setLoading(false) }
  }
  return <section className="panel"><header className="panel-head"><h2>Đổi mật khẩu</h2><p>Bạn sẽ được đăng xuất khỏi tất cả phiên sau khi đổi.</p></header><form className="form" onSubmit={submit}><FormStatus error={errors.general} /><Field label="Mật khẩu hiện tại" name="mat_khau_hien_tai" type="password" value={form.mat_khau_hien_tai} onChange={update} error={errors.mat_khau_hien_tai} /><Field label="Mật khẩu mới" name="mat_khau" type="password" value={form.mat_khau} onChange={update} error={errors.mat_khau} hint="8–20 ký tự, bắt đầu bằng chữ hoa và có ký tự đặc biệt." /><Field label="Nhập lại mật khẩu mới" name="mat_khau_confirmation" type="password" value={form.mat_khau_confirmation} onChange={update} error={errors.mat_khau_confirmation} /><SubmitButton loading={loading}>Đổi mật khẩu</SubmitButton></form></section>
}
