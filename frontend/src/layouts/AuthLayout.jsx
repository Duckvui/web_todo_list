export function AuthLayout({ children }) {
  return <div className="auth-layout"><aside className="auth-brand"><div className="brand-mark"><span className="brand-icon">✓</span>Focusly</div><div className="brand-copy"><span className="eyebrow">Làm chủ mỗi ngày</span><h1>Ít xao nhãng.<br />Nhiều việc hoàn thành.</h1><p>Một không gian gọn gàng để lên kế hoạch, theo dõi và hoàn thành những việc thực sự quan trọng.</p></div><span className="brand-foot">TodoApp · Bảo mật bằng phiên máy chủ</span></aside><main className="auth-main">{children}</main></div>
}
