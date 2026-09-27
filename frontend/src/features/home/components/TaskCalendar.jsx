import { useMemo, useState } from 'react'
import { formatTime, localDateKey, taskStatus } from '../services/taskService'

export function TaskCalendar({ tasks, selectedDate, onSelectDate, now, onToggle }) {
  const [month, setMonth] = useState(() => selectedDate.slice(0, 7))
  const [year, monthNumber] = month.split('-').map(Number)
  const first = new Date(year, monthNumber - 1, 1)
  const offset = (first.getDay() + 6) % 7
  const days = new Date(year, monthNumber, 0).getDate()
  const today = localDateKey(new Date(now))
  const entries = useMemo(() => tasks.flatMap((task) => [
    { ...task, parentId: null },
    ...task.subtasks.map((child) => ({ ...child, parentId: task.id, parentTitle: task.title })),
  ]), [tasks])
  const byDate = useMemo(() => {
    const result = {}
    for (const entry of entries) {
      const date = entry.dueDate.slice(0, 10)
      if (date) (result[date] ||= []).push(entry)
    }
    return result
  }, [entries])
  const agenda = [...(byDate[selectedDate] || [])].sort((a, b) => a.dueDate.localeCompare(b.dueDate))
  function changeMonth(delta) { setMonth(localDateKey(new Date(year, monthNumber - 1 + delta, 1)).slice(0, 7)) }
  function goToday() { setMonth(today.slice(0, 7)); onSelectDate(today) }
  return <section className="home-card task-calendar" aria-labelledby="calendar-title">
    <div className="calendar-heading"><div><h2 id="calendar-title">Lịch công việc</h2><p>Chọn ngày để xem việc đến hạn hoặc lên kế hoạch mới.</p></div><button type="button" className="task-text-button" onClick={goToday}>Hôm nay</button></div>
    <div className="calendar-navigation"><button type="button" className="task-text-button" aria-label="Tháng trước" onClick={() => changeMonth(-1)}>←</button><strong>{first.toLocaleDateString('vi-VN', { month: 'long', year: 'numeric' })}</strong><button type="button" className="task-text-button" aria-label="Tháng sau" onClick={() => changeMonth(1)}>→</button></div>
    <div className="calendar-grid"><div className="calendar-weekdays">{['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'].map((day) => <span key={day}>{day}</span>)}</div><div className="calendar-days">
      {Array.from({ length: offset }, (_, i) => <span key={`blank-${i}`} aria-hidden="true" />)}
      {Array.from({ length: days }, (_, i) => {
        const date = localDateKey(new Date(year, monthNumber - 1, i + 1))
        const items = byDate[date] || []
        const pending = items.filter((t) => !t.completed).length
        const overdue = items.some((t) => taskStatus(t, now).kind === 'overdue')
        return <button type="button" key={date} className={`calendar-day${date === today ? ' is-today' : ''}${overdue ? ' has-overdue' : ''}`} aria-pressed={date === selectedDate} aria-current={date === today ? 'date' : undefined} aria-label={`${i + 1}/${monthNumber}/${year}: ${items.length} task, ${pending} chưa hoàn thành`} onClick={() => onSelectDate(date)}><span>{i + 1}</span>{items.length > 0 && <small>{items.length} task<span className="calendar-dot" /></small>}</button>
      })}
    </div></div><p className="task-helper">Số task gồm cả task chính và task con đến hạn. Chấm đỏ: có task quá hạn.</p>
    <div className="calendar-agenda"><div className="calendar-heading"><h3>Ngày {new Date(`${selectedDate}T00:00:00`).toLocaleDateString('vi-VN')}</h3><button type="button" className="task-text-button" onClick={() => { document.getElementById('task-title')?.focus(); document.getElementById('task-title')?.scrollIntoView({ behavior: 'smooth', block: 'center' }) }}>＋ Đặt việc ngày này</button></div>
      {agenda.length ? <ul>{agenda.map((task) => {
        const status = taskStatus(task, now)
        return <li key={task.id}><input type="checkbox" checked={task.completed} disabled={!task.parentId && task.subtasks?.length > 0} aria-label={`Hoàn thành: ${task.title}`} onChange={() => onToggle(task.parentId || task.id, task.parentId ? task.id : undefined)} /><div><strong>{task.title}</strong><small>{task.parentTitle ? `Task con của: ${task.parentTitle}` : task.subtasks.length ? 'Task chính · tự hoàn thành khi xong các task con' : 'Task chính'}</small><small>Hạn: {formatTime(task.dueDate)}</small><span className={`task-status ${status.kind}`}>{status.text}</span></div></li>
      })}</ul> : <p className="calendar-empty">Ngày này chưa có công việc. Bấm “Đặt việc ngày này” để thêm.</p>}
    </div>
  </section>
}
