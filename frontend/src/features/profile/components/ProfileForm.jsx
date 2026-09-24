import { useState } from 'react'
import { Field, SelectField } from '../../../components/ui/Field'
import { FormStatus } from '../../../components/ui/FormStatus'
import { SubmitButton } from '../../../components/ui/SubmitButton'
import { getApiErrors } from '../../../lib/http'
import { useAuth } from '../../auth/useAuth'
import { authApi } from '../../auth/authApi'

export function ProfileForm() {
  const { user, setUser } = useAuth()
  const profile = user.thong_tin_tai_khoan
  const [form, setForm] = useState({ ho_ten: profile?.ho_ten ?? '', ngay_sinh: profile?.ngay_sinh ?? '', gioi_tinh: profile?.gioi_tinh ?? '' })
  const [status, setStatus] = useState({})
  const [loading, setLoading] = useState(false)
  const update = (event) => setForm({ ...form, [event.target.name]: event.target.value })
  async function submit(event) {
    event.preventDefault(); setStatus({}); setLoading(true)
    try {
      const response = await authApi.updateProfile({ ...form, ngay_sinh: form.ngay_sinh || null, gioi_tinh: form.gioi_tinh || null })
      setUser(response.data); setStatus({ success: 'Đã lưu thông tin cá nhân.' })
    } catch (error) { setStatus({ error: getApiErrors(error).general ?? Object.values(getApiErrors(error))[0] }) } finally { setLoading(false) }
  }
  return <section className="panel"><header className="panel-head"><h2>Thông tin cá nhân</h2><p>Cập nhật thông tin hiển thị của tài khoản.</p></header><form className="form" onSubmit={submit}><FormStatus {...status} /><Field label="Họ và tên" name="ho_ten" value={form.ho_ten} onChange={update} /><div className="form-grid"><Field label="Ngày sinh" name="ngay_sinh" type="date" value={form.ngay_sinh} onChange={update} /><SelectField label="Giới tính" name="gioi_tinh" value={form.gioi_tinh} onChange={update}><option value="">Chưa chọn</option><option value="nam">Nam</option><option value="nu">Nữ</option><option value="khac">Khác</option></SelectField></div><SubmitButton loading={loading}>Lưu thay đổi</SubmitButton></form></section>
}
