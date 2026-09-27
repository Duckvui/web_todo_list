import { useEffect, useState } from 'react'
export function TaskComposer({ onAdd, selectedDate }) {
  const [title, setTitle] = useState('')
  const [dueDate, setDueDate] = useState(`${selectedDate}T09:00`)
  const [priority, setPriority] = useState('normal')
  const [subtasks, setSubtasks] = useState([])
  useEffect(() => { setDueDate((current) => selectedDate + 'T' + (current.slice(11) || '09:00')) }, [selectedDate])
  function updateChild(id, field, value) { setSubtasks(subtasks.map((s) => s.id === id ? { ...s, [field]: value } : s)) }
  function submit(event) {
    event.preventDefault()
    if (onAdd({ title, dueDate, priority, subtasks })) { setTitle(''); setDueDate(selectedDate + 'T09:00'); setPriority('normal'); setSubtasks([]) }
  }
  return <form className="task-composer" onSubmit={submit}>
    <label htmlFor="task-title">Thêm một việc cần làm</label>
    <div className="composer-title"><span aria-hidden="true">＋</span><input id="task-title" placeholder="Bạn muốn hoàn thành điều gì?" value={title} onChange={(e) => setTitle(e.target.value)} required maxLength={160} /></div>
    <div className="composer-options"><label>Hạn task chính<input type="datetime-local" value={dueDate} onChange={(e) => setDueDate(e.target.value)} required /></label><label>Ưu tiên<select value={priority} onChange={(e) => setPriority(e.target.value)}><option value="normal">Bình thường</option><option value="high">Quan trọng</option></select></label></div>
    <div className="subtask-composer"><p>Việc nhỏ: một task chính. Việc lớn: thêm các bước nhỏ bên dưới.</p>
      {subtasks.map((s, index) => <div className="subtask-draft" key={s.id}><label>Bước {index + 1}<input aria-label={`Tên task con ${index + 1}`} placeholder="Tên task con" value={s.title} maxLength={160} required onChange={(e) => updateChild(s.id, 'title', e.target.value)} /></label><label>Hạn task con<input type="datetime-local" value={s.dueDate} max={dueDate || undefined} required onChange={(e) => updateChild(s.id, 'dueDate', e.target.value)} /></label><button type="button" className="task-text-button" onClick={() => setSubtasks(subtasks.filter((item) => item.id !== s.id))} aria-label={`Xóa bước ${index + 1}`}>Xóa</button></div>)}
    </div><div className="composer-actions"><button type="button" className="task-text-button" onClick={() => setSubtasks([...subtasks, { id: crypto.randomUUID(), title: '', dueDate: '' }])}>＋ Thêm task con</button><button className="button button-primary" type="submit">Thêm công việc</button></div>
  </form>
}

