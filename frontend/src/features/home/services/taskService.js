const key = (userId) => `focusly:tasks:${userId}`
export function deadline(task) {
  return task.dueDate ? new Date(task.dueDate.length === 10 ? `${task.dueDate}T23:59:59` : task.dueDate).getTime() : null
}
export function taskStatus(task, now = Date.now()) {
  const due = deadline(task)
  if (task.completed) {
    if (!task.completedAt) return { kind: 'done', text: 'Đã hoàn thành · chưa có thời điểm ghi nhận' }
    const minutes = due === null ? 0 : Math.ceil((new Date(task.completedAt).getTime() - due) / 60000)
    return { kind: minutes > 0 ? 'late' : 'done', text: minutes > 0 ? `Đã hoàn thành (muộn ${minutes} phút)` : 'Đã hoàn thành' }
  }
  return due !== null && now > due ? { kind: 'overdue', text: 'Chưa hoàn thành · đã quá hạn' } : { kind: 'pending', text: 'Chưa hoàn thành' }
}
export function taskProgress(task) {
  const children = task.subtasks || []
  return children.length ? Math.round(children.filter((t) => t.completed).length / children.length * 100) : task.completed ? 100 : 0
}
export function formatTime(value) { return value ? new Date(value.length === 10 ? `${value}T23:59:59` : value).toLocaleString('vi-VN') : 'Chưa đặt thời hạn' }
function createItem({ title, dueDate, priority = 'normal' }) {
  const cleaned = title.trim()
  if (!cleaned || cleaned.length > 160) throw new Error('Tên công việc cần từ 1 đến 160 ký tự.')
  if (!dueDate || !Number.isFinite(new Date(dueDate).getTime())) throw new Error('Hãy đặt ngày và giờ hoàn thành cho từng công việc.')
  return { id: crypto.randomUUID(), title: cleaned, dueDate, priority, completed: false, completedAt: null }
}
export const taskService = {
  list(userId) {
    const data = JSON.parse(localStorage.getItem(key(userId)) || '[]')
    const valid = (t) => t && typeof t.id === 'string' && typeof t.title === 'string' && typeof t.completed === 'boolean' && typeof t.dueDate === 'string'
    if (!Array.isArray(data) || data.some((t) => !valid(t) || (t.subtasks !== undefined && (!Array.isArray(t.subtasks) || t.subtasks.some((s) => !valid(s)))))) throw new Error('Dữ liệu công việc không hợp lệ.')
    return data.map((t) => ({ ...t, subtasks: t.subtasks || [] }))
  },
  save(userId, tasks) { localStorage.setItem(key(userId), JSON.stringify(tasks)) },
  create(values) {
    const task = { ...createItem(values), subtasks: (values.subtasks || []).map(createItem) }
    if (task.subtasks.some((s) => deadline(s) > deadline(task))) throw new Error('Thời hạn task con không được sau thời hạn task chính.')
    return task
  },
}
export function localDateKey(date = new Date()) {
  return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`
}
