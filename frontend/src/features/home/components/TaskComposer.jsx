import { useState } from 'react'
export function TaskComposer({ onAdd }) {
  const [title, setTitle] = useState('')
  const [dueDate, setDueDate] = useState('')
  const [priority, setPriority] = useState('normal')
  function submit(event) {
    event.preventDefault()
    if (onAdd({ title, dueDate, priority })) { setTitle(''); setDueDate(''); setPriority('normal') }
  }
  return <form className="task-composer" onSubmit={submit}><label htmlFor="task-title">Thêm một việc cần làm</label><div className="composer-title"><span aria-hidden="true">＋</span><input id="task-title" placeholder="Bạn muốn hoàn thành điều gì?" value={title} onChange={(e) => setTitle(e.target.value)} required maxLength={160} /></div><div className="composer-options"><label>Hạn hoàn thành<input type="date" value={dueDate} onChange={(e) => setDueDate(e.target.value)} /></label><label>Ưu tiên<select value={priority} onChange={(e) => setPriority(e.target.value)}><option value="normal">Bình thường</option><option value="high">Quan trọng</option></select></label><button className="button button-primary" type="submit">＋ Thêm công việc</button></div></form>
}
