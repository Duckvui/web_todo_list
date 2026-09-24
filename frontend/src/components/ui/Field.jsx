import { useState } from 'react'

export function Field({ label, error, hint, type = 'text', ...props }) {
  const [visible, setVisible] = useState(false)
  const password = type === 'password'
  return <div className="field">
    <label htmlFor={props.name}>{label}</label>
    <div className={password ? 'input-wrap' : undefined}>
      <input id={props.name} type={password && visible ? 'text' : type} aria-invalid={Boolean(error)} {...props} />
      {password && <button type="button" className="text-button input-action" onClick={() => setVisible((value) => !value)}>{visible ? 'Ẩn' : 'Hiện'}</button>}
    </div>
    {hint && !error && <span className="field-hint">{hint}</span>}
    {error && <span className="field-error">{error}</span>}
  </div>
}

export function SelectField({ label, error, children, ...props }) {
  return <div className="field"><label htmlFor={props.name}>{label}</label><select id={props.name} aria-invalid={Boolean(error)} {...props}>{children}</select>{error && <span className="field-error">{error}</span>}</div>
}
