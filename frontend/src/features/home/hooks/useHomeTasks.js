import { useEffect, useMemo, useState } from 'react'
import { deadline, localDateKey, taskProgress, taskService, taskStatus } from '../services/taskService'
export function useHomeTasks(userId) {
  const [initial] = useState(() => {
    try { return { tasks: taskService.list(userId), error: '' } } catch { return { tasks: [], error: 'Không đọc được công việc đã lưu. Hãy kiểm tra quyền lưu trữ hoặc tải lại trang.' } }
  })
  const [tasks, setTasks] = useState(initial.tasks)
  const [error, setError] = useState(initial.error)
  const [notice, setNotice] = useState('')
  const [now, setNow] = useState(Date.now())
  const [filter, setFilter] = useState('all')
  const [query, setQuery] = useState('')
  useEffect(() => {
    const refresh = () => setNow(Date.now())
    const timer = setInterval(refresh, 1000)
    window.addEventListener('focus', refresh)
    return () => { clearInterval(timer); window.removeEventListener('focus', refresh) }
  }, [])
  const today = localDateKey(new Date(now))
  const stats = useMemo(() => ({ total: tasks.length, completed: tasks.filter((t) => t.completed).length, today: tasks.filter((t) => t.dueDate.slice(0, 10) === today && !t.completed).length, progress: tasks.length ? Math.round(tasks.reduce((sum, t) => sum + taskProgress(t), 0) / tasks.length) : 0 }), [tasks, today])
  const visibleTasks = tasks.filter((t) => (filter === 'all' || (filter === 'completed' ? t.completed : filter === 'overdue' ? [t, ...t.subtasks].some((s) => taskStatus(s, now).kind === 'overdue') : !t.completed)) && [t, ...t.subtasks].some((s) => s.title.toLocaleLowerCase('vi').includes(query.toLocaleLowerCase('vi'))))
  const notifications = tasks.flatMap((t) => [t, ...t.subtasks.map((s) => ({ ...s, parentTitle: t.title }))]).filter((t) => t.completed || taskStatus(t, now).kind === 'overdue')
  function commit(next) {
    if (initial.error) return false
    try { taskService.save(userId, next); setTasks(next); setError(''); return true } catch { setError('Không lưu được công việc. Vui lòng kiểm tra dung lượng hoặc quyền lưu trữ trình duyệt.'); return false }
  }
  function addTask(values) {
    try { const saved = commit([taskService.create(values), ...tasks]); if (saved) setNotice('Đã thêm công việc và thời hạn.'); return saved } catch (e) { setError(e.message); return false }
  }
  function toggleTask(id, childId) {
    const stamp = new Date().toISOString()
    let changed
    const next = tasks.map((t) => {
      if (t.id !== id || (!childId && t.subtasks.length)) return t
      const toggle = (s) => ({ ...s, completed: !s.completed, completedAt: s.completed ? null : stamp })
      if (!childId) { changed = toggle(t); return changed }
      const subtasks = t.subtasks.map((s) => { if (s.id !== childId) return s; changed = toggle(s); return changed })
      const completed = subtasks.every((s) => s.completed)
      return { ...t, subtasks, completed, completedAt: completed ? (t.completedAt || stamp) : null }
    })
    if (changed && commit(next)) { setNow(Date.now()); setNotice(`${changed.title}: ${taskStatus(changed).text}`) }
  }
  function updateDeadline(id, childId, dueDate) {
    if (!dueDate || !Number.isFinite(new Date(dueDate).getTime())) { setError('Hãy chọn đầy đủ ngày và giờ.'); return }
    const parent = tasks.find((t) => t.id === id)
    if (childId ? deadline(parent) !== null && deadline({ dueDate }) > deadline(parent) : parent.subtasks.some((s) => deadline(s) > deadline({ dueDate }))) { setError('Thời hạn task con không được sau thời hạn task chính.'); return }
    if (commit(tasks.map((t) => t.id !== id ? t : childId ? { ...t, subtasks: t.subtasks.map((s) => s.id === childId ? { ...s, dueDate } : s) } : { ...t, dueDate }))) setNotice('Đã cập nhật thời hạn.')
  }
  return { tasks, visibleTasks, stats, error, notice, now, notifications, filter, setFilter, query, setQuery, addTask, toggleTask, updateDeadline }
}

