import { useMemo, useState } from 'react'
import { localDateKey, taskService } from '../services/taskService'
export function useHomeTasks(userId) {
  const [initial] = useState(() => {
    try { return { tasks: taskService.list(userId), error: '' } } catch { return { tasks: [], error: 'Không đọc được công việc đã lưu. Hãy kiểm tra quyền lưu trữ hoặc tải lại trang.' } }
  })
  const [tasks, setTasks] = useState(initial.tasks)
  const [error, setError] = useState(initial.error)
  const [filter, setFilter] = useState('all')
  const [query, setQuery] = useState('')
  const today = localDateKey()
  const stats = useMemo(() => ({ total: tasks.length, completed: tasks.filter((t) => t.completed).length, today: tasks.filter((t) => t.dueDate === today && !t.completed).length }), [tasks, today])
  const visibleTasks = tasks.filter((t) => (filter === 'all' || (filter === 'completed' ? t.completed : !t.completed)) && t.title.toLocaleLowerCase('vi').includes(query.toLocaleLowerCase('vi')))
  function commit(next) {
    if (initial.error) return false
    try { taskService.save(userId, next); setTasks(next); setError(''); return true } catch { setError('Không lưu được công việc. Vui lòng kiểm tra dung lượng hoặc quyền lưu trữ trình duyệt.'); return false }
  }
  function addTask(values) {
    try { return commit([taskService.create(values), ...tasks]) } catch (e) { setError(e.message); return false }
  }
  function toggleTask(id) { commit(tasks.map((t) => t.id === id ? { ...t, completed: !t.completed } : t)) }
  return { visibleTasks, stats, error, filter, setFilter, query, setQuery, addTask, toggleTask }
}
